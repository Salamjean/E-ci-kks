<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateDecesHopRequest;
use App\Models\Alert;
use App\Models\DecesHop;
use App\Models\Doctor;
use Exception;
use Illuminate\Http\Request;
use PDF;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\QrCode;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DecesHopController extends Controller
{
    public function index(){
         // Récupérer l'administrateur connecté
         $sousadmin = Auth::guard('sous_admin')->user();
        
         // Récupérer la commune de l'administrateur
         $communeAdmin = $sousadmin->nomHop; 
     
         // Récupérer les déclarations de naissances filtrées par la commune de l'administrateur
         $deceshops = DecesHop::where('nomHop', $communeAdmin)->get();
        return view('decesHop.index', ['deceshops' => $deceshops]);
    }

    public function superindex(){
        $alerts = Alert::where('is_read', false)
        ->whereIn('type', ['naissance', 'mariage', 'deces','decesHop','naissHop'])  
        ->latest()
        ->get();
        $deceshops = DecesHop::all();
       return view('decesHop.superindex',compact('deceshops','alerts'));
   }

    public function create(){
        $doctor = Doctor::all();
        return view('decesHop.create', compact('doctor'));
    
    }

    public function edit(DecesHop $deceshop){
        return view('DecesHop.edit', compact('deceshop'));
    }

    public function delete(DecesHop $deceshop){
        try {
            $deceshop->delete();
            return redirect()->route('decesHop.index')->with('success1','La declaration a été supprimé avec succès.');
        } catch (Exception $e) {
            // dd($e);
            throw new Exception('error','Une erreur est survenue lors de la suppression de la declaration');
        }
    }

    public function show($id)
    {
        $deceshop = DecesHop::findOrFail($id); 
        return view('decesHop.details', compact('deceshop'));
    }
    public function mairieshow($id)
    {
       // Récupérer les alertes
       $alerts = Alert::where('is_read', false)
       ->whereIn('type', ['naissance', 'mariage', 'deces','decesHop','naissHop'])  
       ->latest()
       ->get();
        $deceshop = DecesHop::findOrFail($id); 
        return view('decesHop.mariedetails', compact('deceshop','alerts'));
    }

    public function update(UpdateDecesHopRequest $request,DecesHop $deceshop){
        try {
            $deceshop->NomM = $request->NomM;
            $deceshop->PrM = $request->PrM;
            $deceshop->DateNaissance = $request->DateNaissance;
            $deceshop->DateDeces = $request->DateDeces;
            $deceshop->Remarques = $request->Remarques;
            $deceshop->nomHop = $request->nomHop;
            $deceshop->commune = $request->commune;
            $deceshop->update();

            return redirect()->route('decesHop.index')->with('success','Vos informations ont été mises à jour avec succès.');
        } catch (Exception $e) {
            dd($e);
        }
    }


    public function store(Request $request)
{
    // Validation des données
    $validatedData = $request->validate([
        'NomM' => 'required',
        'PrM' => 'required',
        'DateNaissance' => 'required|date',
        'DateDeces' => 'required|date',
        'nomHop' => 'required',
        'commune' => 'required',
        'Remarques' => 'nullable|string',
    ]);
    $sousadmin = Auth::guard('sous_admin')->user();
    // Création dans la base de données
    $decesHop = DecesHop::create([
        'NomM' => $validatedData['NomM'],
        'PrM' => $validatedData['PrM'],
        'DateNaissance' => $validatedData['DateNaissance'],
        'DateDeces' => $validatedData['DateDeces'],
        'nomHop' => $validatedData['nomHop'],
        'commune' => $validatedData['commune'],
        'Remarques' => $validatedData['Remarques'] ?? null,
        'sous_admin_id' => $sousadmin->id, 
    ]);

    // Génération des codes
    $anneeDeces = date('Y', strtotime($decesHop->DateDeces));
    $id = $decesHop->id;
    $codeDM = "DM{$anneeDeces}{$id}225";
    $codeCMD = "CMD{$anneeDeces}{$id}225";

    $decesHop->update([
        'codeDM' => $codeDM,
        'codeCMD' => $codeCMD,
    ]);

    // Génération du QR code
    $qrCodeData = "Les details de décès:\n" .
    "Nom du défunt: {$validatedData['NomM']}\n" . 
    "Prénom du défunt: {$validatedData['PrM']}\n" .
    "Date de naissance  du défunt: {$validatedData['DateNaissance']}\n" .
    "Date de décès: {$validatedData['DateDeces']}\n" .
    "Hôpital: {$validatedData['nomHop']}\n" .
    "Commune: {$validatedData['commune']}\n" .
    "Cause de décès: {$validatedData['Remarques']}";

    $qrCode = QrCode::create($qrCodeData)
        ->setSize(300)
        ->setMargin(10);

    // Écrire le QR code dans un fichier
    $writer = new PngWriter();
    $result = $writer->write($qrCode);

    // Sauvegarder l'image
    $qrCodePath = storage_path("app/public/deces_hops/qrcode_{$decesHop->id}.png");
    $result->saveToFile($qrCodePath);

    // Récupérer les informations du sous-admin
    $sousadmin = Auth::guard('sous_admin')->user();

    // Générer le PDF
    $pdf = PDF::loadView('decesHop.pdf', compact('decesHop', 'codeDM', 'codeCMD', 'sousadmin', 'qrCodePath'));
    $pdfFileName = "declaration_deces_{$decesHop->id}.pdf";
    $pdf->save(storage_path("app/public/deces_hops/{$pdfFileName}"));


    // Générer la contagion PDF
    $pdf3 = PDF::loadView('decesHop.contagionpdf', compact('decesHop', 'codeDM', 'codeCMD', 'sousadmin', 'qrCodePath'))
    ->setPaper('a4', 'landscape'); 
    $pdfFileName3 = "contagion_{$decesHop->id}.pdf";
    $pdf3->save(storage_path("app/public/deces_hops/{$pdfFileName3}"));
    
    Alert::create([
        'type' => 'decesHop',
        'message' => "Une nouvelle déclaration de décès a été enregistrée par : {$decesHop->nomHop}.",
    ]);

    // Retourner le PDF pour téléchargement direct
    return redirect()->route('decesHop.index')->with('success', 'Déclaration de décès effectuée avec succès');
}

    // Dans votre contrôleur, par exemple DecesController.php

    public function verifierCodeDMD(Request $request)
    {
        $validatedData = $request->validate([
            'codeCMN' => 'required|string|max:50', // Validation de l'entrée
        ]);
    
        $codeCMN = $validatedData['codeCMN'];
    
        // Recherche du dossier médical
        $decesHop = DecesHop::where('codeCMD', $codeCMN)->first();
    
        if ($decesHop) {
            return response()->json([
                'existe' => true,
                'nomHopital' => $decesHop->nomHop,
                'nomDefunt' => $decesHop->NomM . ' ' . $decesHop->PrM,
                'dateNaiss' => $decesHop->DateNaissance,
                'dateDeces' => $decesHop->DateDeces,
                'lieuNaiss' => $decesHop->commune,
            ]);
        }
    
        return response()->json(['existe' => false]);
    }
    
public function download($id)
{
    // Récupérer l'objet NaissHop
    $decesHop = DecesHop::findOrFail($id);

    // Récupérer les informations du sous-admin connecté
    $sousadmin = Auth::guard('sous_admin')->user(); // Supposons que le sous-admin est connecté via `auth`

    if (!$sousadmin) {
        return back()->withErrors(['error' => 'Sous-admin non authentifié.']);
    }

    // Générer le PDF avec les données
    $pdf = PDF::loadView('decesHop.pdf', compact('decesHop', 'sousadmin'));

    // Retourner le PDF pour téléchargement direct
    return $pdf->download("declaration_{$decesHop->id}.pdf");
}

public function downloadcontagion($id)
{
    // Récupérer l'objet NaissHop
    $decesHop = DecesHop::findOrFail($id);

    // Récupérer les informations du sous-admin connecté
    $sousadmin = Auth::guard('sous_admin')->user(); // Supposons que le sous-admin est connecté via `auth`

    if (!$sousadmin) {
        return back()->withErrors(['error' => 'Sous-admin non authentifié.']);
    }

    // Générer le PDF avec les données
    $pdf = PDF::loadView('decesHop.contagionpdf', compact('decesHop', 'sousadmin'));

    // Retourner le PDF pour téléchargement direct
    return $pdf->download("contagion_{$decesHop->id}.pdf");
}

    
}
