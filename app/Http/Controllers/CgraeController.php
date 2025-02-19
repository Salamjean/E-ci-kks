<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\AgenceCgrae;
use App\Models\Alert;
use App\Models\Cgrae;
use App\Models\CgraeAgent;
use App\Models\CgraeSearchHistory;
use App\Models\DecesHop;
use App\Models\NaissHop;
use App\Models\ResetCodePasswordCgrae;
use App\Notifications\SendEmailToCgraeAfterRegistrationNotification;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;

class CgraeController extends Controller
{
    public function dashboard(Request $request)
{
    $admin = Auth::guard('cgrae')->user();
    $cgraeagent = CgraeAgent::where('communeM', $admin->siege)->count();
    $deceshops = DecesHop::count();
    $agences = AgenceCgrae::count();
    $agents = CgraeAgent::count();
    $rechercheInfo = CgraeSearchHistory::latest()->take(7)->get();

    // Récupérer l'historique des recherches depuis la session avec une clé spécifique à la CGRAE
    $searchHistory = session('cgrae_search_history', []);
    $searchHistory = array_slice($searchHistory, -5);

    return view('superadmin.cgrae.dashboard', compact(
        'cgraeagent',
        'deceshops',
        'searchHistory',
        'agences',
        'rechercheInfo',
        'agents',
    ));
}

 public function recherche(Request $request){
    $rechercheInfo = CgraeSearchHistory::latest()->paginate(15);
    return view('superadmin.cgrae.recherche', compact('rechercheInfo'));
 }

    function indexdeclaration(){
        $deceshops = DecesHop::all();
        return view('superadmin.cgrae.cgraeindex', compact('deceshops'));
    }

    function agentslistes(){
        $agents = CgraeAgent::all();
        return view('superadmin.cgrae.indexagent', compact('agents'));
    }
    
    
    public function create(){
        $alerts = Alert::all();
        $admin = Auth::guard('super_admin')->user();
        return view('superadmin.cgrae.create', compact('alerts'));
    }

    public function store(Request $request)
    {
        // Validation des données
        $request->validate([
            'siege' => 'required|unique:cgraes,siege',
            'email' => 'required|email|unique:cgraes,email',
        ],[
            'siege.unique' => 'Cet siège de la cnps existe déjà verifier dans l\'archive',
            'siege.required' => 'Le nom est obligatoire.',
            'email.required' => 'L\'adresse e-mail est obligatoire.',
            'email.email' => 'Veuillez fournir une adresse e-mail valide.',
            'email.unique' => 'Cette adresse e-mail existe déjà.',
        ]);
    
        try {
            // Récupérer le vendor connecté
            $vendor = Auth::guard('super_admin')->user();
    
            if (!$vendor) {
                return redirect()->back()->withErrors(['error' => 'Impossible de récupérer les informations du vendor.']);
            }
    
            // Création du docteur
            $cgrae = new Cgrae();
            $cgrae->name = $request->name;
            $cgrae->siege = $request->siege;
            $cgrae->email = $request->email;
            $cgrae->password = Hash::make('password');
            $cgrae->save();
    
            // Envoi de l'e-mail de vérification
            ResetCodePasswordCgrae::where('email', $cgrae->email)->delete();
            $code = rand(10000, 40000);
    
            ResetCodePasswordCgrae::create([
                'code' => $code,
                'email' => $cgrae->email,
            ]);
    
            Notification::route('mail', $cgrae->email)
                ->notify(new SendEmailToCgraeAfterRegistrationNotification($code, $cgrae->email));
    
            return redirect()->route('cgrae.index')
                ->with('success', 'CGRAE enregistré avec succès.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Une erreur est survenue : ' . $e->getMessage()]);
        }
    }

    public function index(Request $request)
    {
        // Récupérer uniquement les mairies non archivées
        $cgraes = Cgrae::whereNull('archived_at')->get();
    
        // Compter les agents par commune
        // $agentsCountByCommune = CnpsAgent::select('communeM', DB::raw('count(*) as total'))
            // ->groupBy('communeM')
            // ->get();
        // $agentsCount = [];
        // foreach ($agentsCountByCommune as $item) {
            // $agentsCount[$item->communeM] = $item->total;
        // }
    
        return view('superadmin.cgrae.index', compact('cgraes'));
    }

    public function cgraesarchive(Cgrae $cgraes){
        try {
            $cgraes->archive();
            return redirect()->route('cgrae.indexarchive')->with('success1','CGRAE archivé avec succès.');
        } catch (Exception $e) {
            // dd($e);
            throw new Exception('error','Une erreur est survenue lors de la archivation CGRAE');
        }
     }

     public function archive(){
        // Récupérer uniquement les mairies non archivées
        $cgraes = Cgrae::whereNotNull('archived_at')->get();
        // Compter les agents par commune
        $agentsCountByCommune = CgraeAgent::select('communeM', DB::raw('count(*) as total'))
        ->groupBy('communeM')
        ->get();
        $agentsCount = [];
        foreach ($agentsCountByCommune as $item) {
        $agentsCount[$item->communeM] = $item->total;
    }
        // Calculer le solde total restant après toutes les déductions
        
    return view('superadmin.cgrae.archive', compact('cgraes','agentsCount'));
   }

   public function unarchive($id)
{
    $cgraes = Cgrae::find($id);
   
    if ($cgraes && $cgraes->archived_at) {
        $cgraes->archived_at = null;
        $cgraes->save();
   
        return redirect()->route('cgrae.index')->with('success', 'L\'élément a été désarchivé avec succès.');
    }
   
    // Si l'élément n'existe pas ou n'est pas archivé, rediriger avec un message d'erreur
    return redirect()->back()->with('error', 'L\'élément n\'a pas pu être désarchivé.');
}

public function cgraesdelete(Cgrae $cgraes){
    try {
        $cgraes->delete();
        return redirect()->route('cgrae.indexarchive')->with('success1','CGRAE supprimée avec succès.');
    } catch (Exception $e) {
        // dd($e);
        throw new Exception('error','Une erreur est survenue lors de la suppression CGRAE');
    }
 }

 public function register(){
    return view('superadmin.cgrae.auth.validate');
}

public function login(){
    return view('superadmin.cgrae.auth.login');
}


public function handleLogin(Request $request)
{
    $request->validate([
        'email' =>'required|exists:cgraes,email',
        'password' => 'required|min:8',
    ], [
        
        
        'email.required' => 'Le mail est obligatoire.',
        'email.exists' => 'Cette adresse mail n\'existe pas.',
        'password.required' => 'Le mot de passe est obligatoire.',
        'password.min'=> 'Le mot de passe doit avoir au moins 8 caractères.',
    ]);
    try {
        if(auth('cgrae')->attempt($request->only('email', 'password')))
        {
            return redirect()->route('cgraes.dashboard')->with('Bienvenu sur votre page ');
        }else{
            return redirect()->back()->with('error', 'Votre mot de passe ou votre adresse mail est incorrect.');
        }
    } catch (Exception $e) {
        dd($e);
    }
}

public function logout(){
    Auth::guard('cgrae')->logout();
    return redirect()->route('cgraes.login');
}

 // definissons les acces de la cnps 

 public function defineAccess($email){
    //Vérification si le sous-admin existe déjà
    $checkSousadminExiste = Cgrae::where('email', $email)->first();

    if($checkSousadminExiste){
        return view('superadmin.cgrae.auth.validate', compact('email'));
    }else{
        return redirect()->route('cgraes.login');
    };
}

public function submitDefineAccess(Request $request){

    // Validation des données
    $request->validate([
            'code'=>'required|exists:reset_code_password_cgraes,code',
            'password' => 'required|same:confirme_password',
            'confirme_password' => 'required|same:password',
        ], [
            'code.exists' => 'Le code de réinitialisation est invalide.',
            'password.same' => 'Les mots de passe doivent être identiques.',
            'confirme_password.same' => 'Les mots de passe doivent être identiques.',
    ]);
    try {
        $cgrae = Cgrae::where('email', $request->email)->first();

        if ($cgrae) {
            // Mise à jour du mot de passe
            $cgrae->password = Hash::make($request->password);
            $cgrae->update();

            if($cgrae){
               $existingcodehop =  ResetCodePasswordCgrae::where('email', $cgrae->email)->count();

               if($existingcodehop > 1){
                ResetCodePasswordCgrae::where('email', $cgrae->email)->delete();
               }
            }

            return redirect()->route('cgraes.login')->with('success', 'Compte mis à jour avec succès');
        } else {
            return redirect()->route('cgraes.login')->with('error', 'Email inconnu');
        }
    } catch (Exception $e) {
        return redirect()->back()->with('error', 'Une erreur est survenue : ' . $e->getMessage());
    }
}


}
