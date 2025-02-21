<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateAgentRequest;
use App\Http\Requests\UpdateAjointrequest;
use App\Models\Ajoint;
use App\Models\Alert;
use App\Models\Deces;
use App\Models\Decesdeja;
use App\Models\DecesHop;
use App\Models\Mariage;
use App\Models\Naissance;
use App\Models\NaissanceD;
use App\Models\NaissHop;
use App\Models\ResetCodePasswordAjoint;
use App\Notifications\SendEmailToAjointAfterRegistrationNotification;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;

class AjointController extends Controller
{
    public function ajointcreate(){
        $alerts = Alert::all();
        return view('vendor.ajoint.create', compact('alerts'));
     }

     public function ajointstore(Request $request){
        // Validation des données
        $request->validate([
           'name' => 'required|string|max:255',
           'prenom' => 'required|string|max:255',
           'email' => 'required|email|unique:ajoints,email',
           'contact' => 'required|string|max:10',
           'commune' => 'required|string|max:255',
       
       ],[
        'name.required' => 'Le nom est requis.',
        'name.string' => 'Le nom doit être une chaîne de caractères.',
        'name.max' => 'Le nom ne doit pas dépasser 255 caractères.',
        'prenom.required' => 'Le prénom est requis.',
        'prenom.string' => 'Le prénom doit être une chaîne de caractères.',
        'prenom.max' => 'Le prénom ne doit pas dépasser 255 caractères.',
        'email.required' => 'L\'e-mail est requis.',
        'email.email' => 'L\'e-mail n\'est pas valide.',
        'email.unique' => 'Cet e-mail est déjà utilisé.',
        'contact.required' => 'Le numéro de contact est requis.',
        'contact.max' => 'Le numéro de contact ne doit pas dépasser 10 chiffres.',
        'commune.required' => 'La commune est requise.',
        'commune.string' => 'La commune doit être une chaîne de caractères.',
       ]);
       try {
           // Récupérer le vendor connecté
           $vendor = Auth::guard('vendor')->user();
           if (!$vendor || !$vendor->name) {
               return redirect()->back()->withErrors(['error' => 'Impossible de récupérer les informations du vendor.']);
           }
           // Création de l'ajoint au maire
           $ajoint = new Ajoint();
           $ajoint->name = $request->name;
           $ajoint->prenom = $request->prenom;
           $ajoint->email = $request->email;
           $ajoint->contact = $request->contact;
           $ajoint->password = Hash::make('default');
           
           if ($request->hasFile('profile_picture')) {
               $ajoint->profile_picture = $request->file('profile_picture')->store('profile_pictures', 'public');
           }
           $ajoint->commune = $request->commune;
           $ajoint->communeM = $vendor->name;
           
           $ajoint->save();
           // Envoi de l'e-mail de vérification
           ResetCodePasswordAjoint::where('email', $ajoint->email)->delete();
           $code = rand(100000, 400000);
           ResetCodePasswordAjoint::create([
               'code' => $code,
               'email' => $ajoint->email,
           ]);
           Notification::route('mail', $ajoint->email)
               ->notify(new SendEmailToAjointAfterRegistrationNotification($code, $ajoint->email));
           return redirect()->route('ajoint.index')
               ->with('success', 'Ajoint au maire enregistré avec succès.');
       } catch (\Exception $e) {
           return redirect()->back()->withErrors(['error' => 'Une erreur est survenue : ' . $e->getMessage()]);
       }
    }

    public function ajointindex(){
        $admin = Auth::guard('vendor')->user();
        $alerts = Alert::all();
        $ajoints = Ajoint::whereNull('archived_at')
            ->where('communeM', $admin->name)
            ->paginate(10);
        return view('vendor.ajoint.index', compact('ajoints','alerts'));
     }

     public function superindex(){
        $alerts = Alert::all();
        $ajoints = Ajoint::all();
        return view('vendor.ajoint.superindex', compact('ajoints','alerts'));
     }

     public function ajointedit(Ajoint $ajoint){
        $alerts = Alert::all();
        return view('vendor.ajoint.edit', compact('ajoint','alerts'));
     }

     public function ajointupdate(UpdateAjointrequest $request ,Ajoint $ajoint){
        try {
            $ajoint->name = $request->name;
            $ajoint->prenom = $request->prenom;
            $ajoint->email = $request->email;
            $ajoint->contact = $request->contact;
            $ajoint->commune = $request->commune;
            $ajoint->update();
            return redirect()->route('ajoint.index')->with('success','Les informations ajoint au maire mises à jour avec succès.');
        } catch (Exception $e) {
            // dd($e);
            throw new Exception('error','Une erreur est survenue lors de la modification du Docteur');
        }
     }

     public function ajointdelete(Ajoint $ajoint){
        try {
            $ajoint->archive();
            return redirect()->route('ajoint.index')->with('success1','Ajoint supprimé avec succès.');
        } catch (Exception $e) {
            // dd($e);
            throw new Exception('error','Une erreur est survenue lors de la suppression ajoint');
        }
     }
     public function ajointvue(Request $request) {
            // Récupérer l'admin connecté
        $admin = Auth::guard('ajoint')->user();

        // Récupérer le mois et l'année sélectionnés pour les naissances, décès et mariages
        $selectedMonth = $request->input('month', date('m'));
        $selectedYear = $request->input('year', date('Y'));

        // Récupérer le mois et l'année sélectionnés pour les naisshops et deceshops
        $selectedMonthHops = $request->input('month_hops', date('m'));
        $selectedYearHops = $request->input('year_hops', date('Y'));

        // Récupérer les données associées à la commune de cet admin pour le mois sélectionné
        // Données pour naissances, décès, et mariages
        $naissances = Naissance::where('commune', $admin->communeM)
            ->whereMonth('created_at', $selectedMonth)
            ->whereYear('created_at', $selectedYear)
            ->orderBy('created_at', 'desc')
            ->get();

        $naissancesD = NaissanceD::where('commune', $admin->communeM)
            ->whereMonth('created_at', $selectedMonth)
            ->whereYear('created_at', $selectedYear)
            ->orderBy('created_at', 'desc')
            ->get();

        $deces = Deces::where('commune', $admin->communeM)
            ->whereMonth('created_at', $selectedMonth)
            ->whereYear('created_at', $selectedYear)
            ->orderBy('created_at', 'desc')
            ->get();

        $decesdeja = Decesdeja::where('commune', $admin->communeM)
            ->whereMonth('created_at', $selectedMonth)
            ->whereYear('created_at', $selectedYear)
            ->orderBy('created_at', 'desc')
            ->get();
        $mariages = Mariage::where('commune', $admin->communeM)
            ->whereMonth('created_at', $selectedMonth)
            ->whereYear('created_at', $selectedYear)
            ->orderBy('created_at', 'desc')
            ->get();

        // Données pour naisshops et deceshops
        $naisshops = NaissHop::where('commune', $admin->communeM)
            ->whereMonth('created_at', $selectedMonthHops)
            ->whereYear('created_at', $selectedYearHops)
            ->orderBy('created_at', 'desc')
            ->get();

        $deceshops = DecesHop::where('commune', $admin->communeM)
            ->whereMonth('created_at', $selectedMonthHops)
            ->whereYear('created_at', $selectedYearHops)
            ->orderBy('created_at', 'desc')
            ->get();

        // Calcul des données globales
        $totalData = $naissances->count() + $naissancesD->count() + $decesdeja->count() + $deces->count() + $mariages->count();
        $totalDataHops = $naisshops->count() + $deceshops->count();

        // Pourcentages
        $naissancePercentage = $totalData > 0 ? ($naissances->count() / $totalData) * 100 : 0;
        $naissanceDPercentage = $totalData > 0 ? ($naissancesD->count() / $totalData) * 100 : 0;
        $decesPercentage = $totalData > 0 ? ($deces->count() / $totalData) * 100 : 0;
        $decesdejaPercentage = $totalData > 0 ? ($decesdeja->count() / $totalData) * 100 : 0;
        $mariagePercentage = $totalData > 0 ? ($mariages->count() / $totalData) * 100 : 0;
        $naisshopPercentage = $totalDataHops > 0 ? ($naisshops->count() / $totalDataHops) * 100 : 0;
        $deceshopPercentage = $totalDataHops > 0 ? ($deceshops->count() / $totalDataHops) * 100 : 0;

        $NaissP = $naissancePercentage + $naissanceDPercentage;    
        $DecesP = $decesPercentage + $decesdejaPercentage;    
        $NaissHop = $naisshopPercentage + $deceshopPercentage; 

        // Données pour le tableau de bord
        $naissancedash = $naissances->count();
        $decesdash = $deces->count();
        $decesdejadash = $decesdeja->count();
        $mariagedash = $mariages->count();
        $naissanceDdash = $naissancesD->count();
        $naisshopsdash = $naisshops->count();
        $deceshopsdash = $deceshops->count();
        $Naiss = $naissancedash + $naissanceDdash;
        $Dece = $decesdash + $decesdejadash;
        $NaissHopTotal = $naisshopsdash + $deceshopsdash;

        // Récupération des données récentes (3 derniers éléments)
        $recentNaissances = $naissances->take(2);
        $recentNaissancesd = $naissancesD->take(2);
        $recentDeces = $deces->take(2);
        $recentDecesdeja = $decesdeja->take(2);
        $recentMariages = $mariages->take(2);
        $recentNaisshops = $naisshops->take(2);
        $recentDeceshops = $deceshops->take(2);

        $alerts = Alert::where('is_read', false)
        ->whereIn('type', ['naissance','decesdeja', 'mariage', 'deces','decesHop','naissHop'])  
        ->latest()
        ->get();

        // Retourne la vue avec les données
        return view('vendor.ajoint.dashboard', compact(
            'naissancedash', 'naisshopsdash', 'deceshopsdash','decesdejadash', 
            'NaissHopTotal', 'decesdash', 'NaissP','DecesP', 'mariagedash', 
            'naissances', 'naissancesD', 'deces','decesdeja', 'mariages', 
            'totalDataHops', 'totalData', 'naissancePercentage', 
            'naissanceDPercentage', 'decesPercentage', 'mariagePercentage', 
            'naisshopPercentage', 'deceshopPercentage', 
            'recentNaissances', 'recentDeces', 'recentMariages', 
            'alerts', 'Naiss','Dece','recentDecesdeja', 'NaissHop', 
            'selectedMonth', 'selectedYear', 
            'selectedMonthHops', 'selectedYearHops','recentNaisshops', 'recentDeceshops','recentNaissancesd'
        ));
    }

     public function logout(){
        Auth::guard('ajoint')->logout();
        return redirect()->route('ajoint.login');
    }

     public function login(){
        return view('vendor.ajoint.auth.login');
    }
    

    public function handleLogin(Request $request)
{
    // Validation des champs du formulaire
    $request->validate([
        'email' => 'required|exists:ajoints,email',
        'password' => 'required|min:8',
    ], [
        'email.required' => 'Le mail est obligatoire.',
        'email.exists' => 'Cette adresse mail n\'existe pas.',
        'password.required' => 'Le mot de passe est obligatoire.',
        'password.min' => 'Le mot de passe doit avoir au moins 8 caractères.',
    ]);

    try {
        // Récupérer l'adjoint par son email
        $ajoint = Ajoint::where('email', $request->email)->first();

        // Vérifier si l'adjoint est archivé
        if ($ajoint && $ajoint->archived_at !== null) {
            return redirect()->back()->with('error', 'Votre compte a été supprimé. Vous ne pouvez pas vous connecter.');
        }

        // Tenter la connexion
        if (auth('ajoint')->attempt($request->only('email', 'password'))) {
            return redirect()->route('ajoint.dashboard')->with('success', 'Bienvenue sur votre page.');
        } else {
            return redirect()->back()->with('error', 'Votre mot de passe ou votre adresse mail est incorrect.');
        }
    } catch (Exception $e) {
        // Gérer les erreurs
        return redirect()->back()->with('error', 'Une erreur s\'est produite lors de la connexion.');
    }
}

     public function defineAccess($email){
        //Vérification si le sous-admin existe déjà
        $checkSousadminExiste = Ajoint::where('email', $email)->first();
    
        if($checkSousadminExiste){
            return view('vendor.ajoint.auth.register', compact('email'));
        }else{
            return redirect()->route('ajoint.login');
        };
    }

    public function submitDefineAccess(Request $request){

        // Validation des données
        $request->validate([
                'code'=>'required|exists:reset_code_password_ajoints,code',
                'password' => 'required|same:confirme_password',
                'confirme_password' => 'required|same:password',
                
            ], [
                'code.exists' => 'Le code de réinitialisation est invalide.',
                'code.required' => 'Le code de réinitialisation est obligatoire verifié votre mail.',
                'password.required' => 'Le mot de passe est obligatoire.',
                'password.same' => 'Les mots de passe doivent être identiques.',
                'confirme_password.same' => 'Les mots de passe doivent être identiques.',
                'confirme_password.required' => 'Le mot de passe de confirmation est obligatoire.',
        ]);
        try {
            $ajoint = Ajoint::where('email', $request->email)->first();
    
            if ($ajoint) {
                // Mise à jour du mot de passe
                $ajoint->password = Hash::make($request->password);
    
                // Vérifier si une image est uploadée
                if ($request->hasFile('profile_picture')) {
                    // Supprimer l'ancienne photo si elle existe
                    if ($ajoint->profile_picture) {
                        Storage::delete('public/' . $ajoint->profile_picture);
                    }
    
                    // Stocker la nouvelle image
                    $imagePath = $request->file('profile_picture')->store('profile_pictures', 'public');
                    $ajoint->profile_picture = $imagePath;
                }
                $ajoint->update();
    
                if($ajoint){
                   $existingcodeajoint =  ResetCodePasswordAjoint::where('email', $ajoint->email)->count();
    
                   if($existingcodeajoint > 1){
                    ResetCodePasswordAjoint::where('email', $ajoint->email)->delete();
                   }
                }
    
                return redirect()->route('ajoint.login')->with('success', 'Compte mis à jour avec succès');
            } else {
                return redirect()->route('ajoint.login')->with('error', 'Email inconnu');
            }
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Une erreur est survenue : ' . $e->getMessage());
        }
    }
}
