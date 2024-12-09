<?php

namespace App\Http\Controllers;

use App\Models\Alert;
use App\Models\Naissance;
use App\Models\Vendor;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class VendorController extends Controller
{
    //pour les vues de doctor
    public function dashboard(){
        return view('vendor.dashboard');
    }


    // Pour l'authentification
    
    public function register(){
        return view('vendor.auth.register');
    }

    public function login(){
        return view('vendor.auth.login');
    }


    public function handleRegister(Request $request)
    {
        // Validation des données
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:vendors,email',
            'password' => 'required|min:8',
        ], [
            'name.required' => 'Le nom est obligatoire.',
            'email.required' => 'L\'adresse e-mail est obligatoire.',
            'email.email' => 'Veuillez fournir une adresse e-mail valide.',
            'email.unique' => 'Cette adresse e-mail existe déjà.',
            'password.required' => 'Le mot de passe est obligatoire.',
            'password.min' => 'Le mot de passe doit avoir au moins 8 caractères.',
        ]);
    
        try {
            // Création du nouveau compte
            Vendor::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);
    
            // Redirection avec un message de succès
            return redirect()->route('vendor.login')->with('success', 'Votre compte a été créé avec succès. Vous pouvez vous connecter.');
        } catch (\Exception $e) {
            // En cas d'erreur inattendue
            return back()->withErrors(['error' => 'Une erreur est survenue. Veuillez réessayer.'])->withInput();
        }
    }
    

    public function handleLogin(Request $request)
    {
        $request->validate([
            'email' =>'required|exists:vendors,email',
            'password' => 'required|min:8',
        ], [
            
            
            'email.required' => 'Le mail est obligatoire.',
            'email.exists' => 'Cette adresse mail n\'existe pas.',
            'password.required' => 'Le mot de passe est obligatoire.',
            'password.min' => 'Le mot de passe doit avoir au moins 8 caractères.',
        ]);

        try {
            if(auth('vendor')->attempt($request->only('email', 'password')))
            {
                return redirect()->route('vendor.dashboard')->with('Bienvenu sur votre page ');
            }else{
                return redirect()->back()->with('error', 'Votre mot de passe ou votre adresse mail est incorrect.');
            }
        } catch (Exception $e) {
            dd($e);
        }
    }


    public function edit($id)
{
    $alerts = Alert::all();
    $naissance = Naissance::findOrFail($id);

    // Les états possibles à afficher dans le formulaire
    $etats = ['en attente', 'accepté', 'refusé'];

    return view('naissances.edit', compact('naissance', 'etats','alerts'));
}
    public function updateEtat(Request $request, $id)
{
    $naissance = Naissance::findOrFail($id);
    
    // Validation de l'état (si nécessaire)
    $request->validate([
        'etat' => 'required|string|in:en attente,accepté,refusé', // Ajouter les états possibles
    ]);

    // Mise à jour de l'état
    $naissance->etat = $request->etat;
    $naissance->save();
    
    return redirect()->route('naissance.index')->with('success', 'L\'état de la demande a été mis à jour.');
}



}
