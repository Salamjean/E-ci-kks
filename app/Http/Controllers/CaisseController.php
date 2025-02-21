<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateAgentRequest;
use App\Models\Alert;
use App\Models\Caisse;
use App\Models\Deces;
use App\Models\Decesdeja;
use App\Models\Mariage;
use App\Models\Naissance;
use App\Models\NaissanceD;
use App\Models\ResetCodePasswordAgent;
use App\Models\ResetCodePasswordCaisse;
use App\Models\Vendor;
use App\Notifications\SendEmailToCaisseAfterRegistrationNotification;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;

class CaisseController extends Controller
{
    public function caissecreate(){
        $alerts = Alert::all();
        return view('vendor.caisse.create', compact('alerts'));
     }
     public function caisseindex(){
        $admin = Auth::guard('vendor')->user();
        $alerts = Alert::all();
        $caisses = Caisse::whereNull('archived_at')
            ->where('communeM', $admin->name)
            ->paginate(10);
        return view('vendor.caisse.index', compact('caisses','alerts'));
     }

     public function superindex(){
        $alerts = Alert::all();
        $caisses = Caisse::all();
        return view('vendor.caisse.superindex', compact('caisses','alerts'));
     }
     

     public function caissestore(Request $request){
        // Validation des données
       
        $request->validate([
           'name' => 'required|string|max:255',
           'prenom' => 'required|string|max:255',
           'email' => 'required|email|unique:caisses,email',
           'contact' => 'required|string|min:10',
           'commune' => 'required|string|max:255',
           'profile_picture' => 'nullable|image|max:2048',
       
       ],[
              'name.required' => 'Le nom est obligatoire.',
              'name.string' => 'Le nom doit être une chaîne de caractères.',
              'name.max' => 'Le nom ne doit pas dépasser :max caractères.',
              'prenom.required' => 'Le prénom est obligatoire.',
              'prenom.string' => 'Le prénom doit être une chaîne de caractères.',
              'prenom.max' => 'Le prénom ne doit pas dépasser :max caractères.',
              'email.required' => 'L\'adresse mail est obligatoire.',
              'email.email' => 'L\'adresse mail doit être valide.',
              'email.unique' => 'L\'adresse mail est déjà utilisée.',
              'contact.required' => 'Le contact est obligatoire.',
              'contact.string' => 'Le contact doit être une chaîne de caractères.',
              'contact.min' => 'Le contact doit avoir au moins :min caractères.',
              'commune.required' => 'La commune est obligatoire.',
              'commune.string' => 'La commune doit être une chaîne de caractères.',
              'commune.max' => 'La commune ne doit pas dépasser :max caractères.',
       ]);
   
       try {
           // Récupérer le vendor connecté
           $vendor = Auth::guard('vendor')->user();
   
           if (!$vendor || !$vendor->name) {
               return redirect()->back()->withErrors(['error' => 'Impossible de récupérer les informations du vendor.']);
           }
   
           // Création du docteur
           $caisse = new Caisse();
           $caisse->name = $request->name;
           $caisse->prenom = $request->prenom;
           $caisse->email = $request->email;
           $caisse->contact = $request->contact;
           $caisse->password = Hash::make('default');
           
           if ($request->hasFile('profile_picture')) {
               $caisse->profile_picture = $request->file('profile_picture')->store('profile_pictures', 'public');
           }
   
           $caisse->commune = $request->commune;
           $caisse->communeM = $vendor->name;
           
           $caisse->save();
   
           // Envoi de l'e-mail de vérification
           ResetCodePasswordCaisse::where('email', $caisse->email)->delete();
           $code1 = rand(10000, 40000);
           $code = $code1.''.$caisse->id;
   
           ResetCodePasswordCaisse::create([
               'code' => $code,
               'email' => $caisse->email,
           ]);
   
           Notification::route('mail', $caisse->email)
               ->notify(new SendEmailToCaisseAfterRegistrationNotification($code, $caisse->email));
   
           return redirect()->route('caisse.index')
               ->with('success', 'Caissié enregistré avec succès.');
       } catch (\Exception $e) {
           return redirect()->back()->withErrors(['error' => 'Une erreur est survenue : ' . $e->getMessage()]);
       }
    }
    public function caisseedit(Caisse $caisse){
        $alerts = Alert::all();
        return view('vendor.caisse.edit', compact('caisse','alerts'));
     }

     public function caisseupdate(UpdateAgentRequest $request ,Caisse $caisse){
        try {
            $caisse->name = $request->name;
            $caisse->prenom = $request->prenom;
            $caisse->email = $request->email;
            $caisse->contact = $request->contact;
            $caisse->commune = $request->commune;
            $caisse->update();
    
            return redirect()->route('caisse.index')->with('success','Les informations du caissié mises à jour avec succès.');
        } catch (Exception $e) {
            // dd($e);
            throw new Exception('error','Une erreur est survenue lors de la modification du Docteur');
        }
     }

     public function caissedelete(Caisse $caisse){
        try {
            $caisse->archive();
            return redirect()->route('caisse.index')->with('success1','Caissié supprimé avec succès.');
        } catch (Exception $e) {
            // dd($e);
            throw new Exception('error','Une erreur est survenue lors de la suppression ajoint');
        }
     }    

     public function dashboard()
{
    // Récupérer l'utilisateur connecté
    $admin = Auth::guard('caisse')->user();

    // Récupérer les alertes
    $alerts = Alert::all();

    // Récupérer les caisses de la commune de l'utilisateur
    $caisses = Caisse::where('communeM', $admin->communeM)->paginate(10);

    // Compter les demandes par type pour la commune de l'utilisateur
    $decesnombre = Deces::where('commune', $admin->communeM)->count();
    $decesdejanombre = Decesdeja::where('commune', $admin->communeM)->count();
    $mariagenombre = Mariage::where('commune', $admin->communeM)->count();
    $naissancenombre = Naissance::where('commune', $admin->communeM)->count();
    $naissanceDnombre = NaissanceD::where('commune', $admin->communeM)->count();
    $total = $decesnombre + $decesdejanombre + $naissancenombre + $naissanceDnombre + $mariagenombre;

    // Récupérer le solde total des mairies de la commune de l'utilisateur
    $soldeCommune = Vendor::where('name', $admin->communeM)
        ->whereNull('archived_at')
        ->sum('solde');

    // Solde initial (solde total de la commune)
    $soldeActuel = $soldeCommune;

    // Déduction pour chaque nouvelle demande
    $debit = 500; // Montant à déduire pour chaque demande
    $soldeDebite = $total * $debit; // Total débité basé sur le nombre de demandes
    $soldeRestant = $soldeActuel - $soldeDebite; // Calcul du solde restant

    // Récupérer les demandes récentes
    $demandesNaissance1 = Naissance::where('commune', $admin->communeM)->latest()->take(3)->get();
    $demandesNaissanceD = NaissanceD::where('commune', $admin->communeM)->latest()->take(2)->get();
    $demandesNaissance = $demandesNaissance1->concat($demandesNaissanceD);
    $demandesDeces1 = Deces::where('commune', $admin->communeM)->latest()->take(5)->get();
    $demandesDecesdeja = Decesdeja::where('commune', $admin->communeM)->latest()->take(5)->get();
    $demandesDeces = $demandesDeces1->concat($demandesDecesdeja);
    $demandesMariage = Mariage::where('commune', $admin->communeM)->latest()->take(5)->get();

    return view('vendor.caisse.dashboard', compact(
        'alerts', 'total', 'soldeActuel', 'soldeDebite', 'soldeRestant',
        'decesnombre', 'decesdejanombre', 'naissancenombre', 'naissanceDnombre', 'mariagenombre',
        'demandesNaissance', 'demandesDeces', 'demandesMariage', 'soldeCommune'
    ));
}

     public function logout(){
        Auth::guard('caisse')->logout();
        return redirect()->route('caisse.login');
    }
    public function login(){
        return view('vendor.caisse.auth.login');
    }

    public function handleLogin(Request $request)
{
    // Validation des champs du formulaire
    $request->validate([
        'email' => 'required|exists:caisses,email',
        'password' => 'required|min:8',
    ], [
        'email.required' => 'Le mail est obligatoire.',
        'email.exists' => 'Cette adresse mail n\'existe pas.',
        'password.required' => 'Le mot de passe est obligatoire.',
        'password.min' => 'Le mot de passe doit avoir au moins 8 caractères.',
    ]);

    try {
        // Récupérer la caisse par son email
        $caisse = Caisse::where('email', $request->email)->first();

        // Vérifier si la caisse est archivée
        if ($caisse && $caisse->archived_at !== null) {
            return redirect()->back()->with('error', 'Votre compte a été suprrimé. Vous ne pouvez pas vous connecter.');
        }

        // Tenter la connexion
        if (auth('caisse')->attempt($request->only('email', 'password'))) {
            return redirect()->route('caisse.dashboard')->with('success', 'Bienvenue sur votre page.');
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
        $checkSousadminExiste = Caisse::where('email', $email)->first();
    
        if($checkSousadminExiste){
            return view('vendor.caisse.auth.register', compact('email'));
        }else{
            return redirect()->route('caisse.login');
        };
    }

    public function submitDefineAccess(Request $request){
        // Validation des données
        $request->validate([
                'code'=>'required|exists:reset_code_password_caisses,code',
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
            $caisse = Caisse::where('email', $request->email)->first();
        
            if ($caisse) {
                // Mise à jour du mot de passe
                $caisse->password = Hash::make($request->password);
        
                // Vérifier si une image est uploadée
                if ($request->hasFile('profile_picture')) {
                    // Supprimer l'ancienne photo si elle existe
                    if ($caisse->profile_picture) {
                        Storage::delete('public/' . $caisse->profile_picture);
                    }
        
                    // Stocker la nouvelle image
                    $imagePath = $request->file('profile_picture')->store('profile_pictures', 'public');
                    $caisse->profile_picture = $imagePath;
                }
                $caisse->update();
        
                if($caisse){
                   $existingcodeajoint =  ResetCodePasswordCaisse::where('email', $caisse->email)->count();
        
                   if($existingcodeajoint > 1){
                    ResetCodePasswordCaisse::where('email', $caisse->email)->delete();
                   }
                }
        
                return redirect()->route('caisse.login')->with('success', 'Compte mis à jour avec succès');
            } else {
                return redirect()->route('caisse.login')->with('error', 'Email inconnu');
            }
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Une erreur est survenue : ' . $e->getMessage());
        }
    }
}
