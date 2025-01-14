<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\DecesHop;
use App\Models\NaissHop;
use App\Models\SousAdmin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PDF; 
use Carbon\Carbon; 

class StatController extends Controller
{
    public function index(Request $request)
    {
        $sousadmin = Auth::guard('sous_admin')->user();
        $communeAdmin = $sousadmin->nomHop;
        $sousAdminId = $sousadmin->id; // Récupérer l'ID du sous-administrateur
    
        // Récupérer le mois et l'année sélectionnés
        $selectedMonth = $request->input('month', date('m'));
        $selectedYear = $request->input('year', date('Y'));
    
        // Compter le total des déclarations de naissance et de décès pour le mois sélectionné
        
        $naisshop = NaissHop::where('NomEnf', $communeAdmin)
            ->whereMonth('created_at', $selectedMonth)
            ->where('sous_admin_id', $sousAdminId) // Filtrer par ID de sous-administrateur
            ->whereYear('created_at', $selectedYear)
            ->count();
    
        $deceshop = DecesHop::where('nomHop', $communeAdmin)
            ->whereMonth('created_at', $selectedMonth)
            ->where('sous_admin_id', $sousAdminId) // Filtrer par ID de sous-administrateur
            ->whereYear('created_at', $selectedYear)
            ->count();

         // Récupérer les données pour les graphiques
        $naissData = NaissHop::where('NomEnf', $communeAdmin)
             ->where('sous_admin_id', $sousAdminId) // Filtrer par ID de sous-administrateur
             ->whereMonth('created_at', $selectedMonth)
             ->whereYear('created_at', $selectedYear)
             ->selectRaw('CAST(strftime("%m", created_at) AS INTEGER) as month, COUNT(*) as count')
             ->groupBy('month')
             ->orderBy('month')
             ->pluck('count', 'month')->toArray();
             // Remplir les données manquantes
        $naissData = array_replace(array_fill(1, 12, 0), $naissData);
        $total = $naisshop + $deceshop ;
             // Récupérer les données de décès
        $decesData = DecesHop::where('nomHop', $communeAdmin)
             ->where('sous_admin_id', $sousAdminId) // Filtrer par ID de sous-administrateur
             ->whereMonth('created_at', $selectedMonth)
             ->whereYear('created_at', $selectedYear)
             ->selectRaw('CAST(strftime("%m", created_at) AS INTEGER) as month, COUNT(*) as count')
             ->groupBy('month')
             ->orderBy('month')
             ->pluck('count', 'month')->toArray();
            // Remplir les données manquantes
        $decesData = array_replace(array_fill(1, 12, 0), $decesData);
    
        // Vérifier si le téléchargement en PDF est demandé
        if ($request->has('download_pdf')) {
            $pdf = PDF::loadView('stat.pdf', compact('naisshop', 'deceshop', 'total', 'selectedMonth', 'selectedYear', 'naissData', 'decesData'));
            return $pdf->download('statistiques.pdf');
        }
    
        return view('stat.index', compact('naisshop', 'deceshop', 'total', 'selectedMonth', 'selectedYear', 'naissData', 'decesData'));
    }

    
    public function download(Request $request)
{
    // Définir la locale de Carbon en français
    Carbon::setLocale('fr');
    $sousadmin = Auth::guard('sous_admin')->user();
    $hopitalName = $sousadmin->nomHop;
    $sousAdminId = $sousadmin->id; // Récupérer l'ID du sous-administrateur
    // Récupérer le mois et l'année sélectionnés
    $selectedMonth = $request->input('month', date('m'));
    $selectedYear = $request->input('year', date('Y'));

    $naisshopCount = NaissHop::where('sous_admin_id', $sousAdminId)->count(); // Compte des naissances pour un hôpital spécifique
    $deceshopCount = DecesHop::where('sous_admin_id', $sousAdminId)->count();

    // Récupérer le nom de l'hôpital
    $sousadmin = Auth::guard('sous_admin')->user();
    $hopitalName = $sousadmin->nomHop;

    // Récupérer les données par mois et nomHop pour les naissances
    $naissData = NaissHop::selectRaw('strftime("%m", created_at) as month, NomEnf, COUNT(*) as count')
        ->where('sous_admin_id', $sousAdminId) // Filtrer par ID de sous-administrateur
        ->whereYear('created_at', $selectedYear)   // Filtre uniquement par année
        ->selectRaw('CAST(strftime("%m", created_at) AS INTEGER) as month, COUNT(*) as count')
        ->groupBy('month')
        ->orderBy('month')
        ->pluck('count', 'month')
        ->toArray();

    // Récupérer les données par mois et nomHop pour les décès
    $decesData = DecesHop::
        where('sous_admin_id', $sousAdminId) // Filtrer par ID de sous-administrateur
        ->whereYear('created_at', $selectedYear)   // Filtre uniquement par année
        ->selectRaw('CAST(strftime("%m", created_at) AS INTEGER) as month, COUNT(*) as count')
        ->groupBy('month')
        ->orderBy('month')
        ->pluck('count', 'month')
        ->toArray();

    // Préparer les données pour le PDF
    $data = [
        'naisshopCount' => $naisshopCount,
        'deceshopCount' => $deceshopCount,
        'hopitalName' => $hopitalName,
        'naissData' => $naissData,
        'decesData' => $decesData,
    ];

    // Générer le PDF
    $pdf = PDF::loadView('stat.pdf', $data);

    // Retourner le PDF en téléchargement
    return $pdf->download('statistiques.pdf');
}

    public function superindex(Request $request)
    {
        $sousadmin = Auth::guard('doctor')->user();
        $communeAdmin = $sousadmin->nomHop;
        $sousAdminId = $sousadmin->id; // Récupérer l'ID du sous-administrateur
        // Récupérer le mois et l'année sélectionnés
        $selectedMonth = $request->input('month', date('m'));
        $selectedYear = $request->input('year', date('Y'));
        
        // Compter le total des déclarations de naissance et de décès pour le mois sélectionné
        $docteur = SousAdmin::where('nomHop', $communeAdmin)
            ->whereMonth('created_at', $selectedMonth)
            ->whereYear('created_at', $selectedYear)
            ->count();

        $naisshop = NaissHop::where('NomEnf', $communeAdmin)
            ->whereMonth('created_at', $selectedMonth)
            ->whereYear('created_at', $selectedYear)
            ->count();
    
        $deceshop = DecesHop::where('nomHop', $communeAdmin)
            ->whereMonth('created_at', $selectedMonth)
            ->whereYear('created_at', $selectedYear)
            ->count();

          // Récupérer les données pour les graphiques
        $naissData = NaissHop::where('NomEnf', $communeAdmin)
            
             ->whereMonth('created_at', $selectedMonth)
             ->whereYear('created_at', $selectedYear)
             ->selectRaw('CAST(strftime("%m", created_at) AS INTEGER) as month, COUNT(*) as count')
             ->groupBy('month')
             ->orderBy('month')
             ->pluck('count', 'month')->toArray();
             // Remplir les données manquantes
        $naissData = array_replace(array_fill(1, 12, 0), $naissData);
        $total = $naisshop + $deceshop ;
             // Récupérer les données de décès
        $decesData = DecesHop::where('nomHop', $communeAdmin)
            
             ->whereMonth('created_at', $selectedMonth)
             ->whereYear('created_at', $selectedYear)
             ->selectRaw('CAST(strftime("%m", created_at) AS INTEGER) as month, COUNT(*) as count')
             ->groupBy('month')
             ->orderBy('month')
             ->pluck('count', 'month')->toArray();
            // Remplir les données manquantes
        $decesData = array_replace(array_fill(1, 12, 0), $decesData);
    
        // Vérifier si le téléchargement en PDF est demandé
        if ($request->has('download_pdf')) {
            $pdf = PDF::loadView('stat.superpdf', compact('naisshop', 'deceshop', 'total', 'selectedMonth', 'selectedYear', 'naissData', 'decesData'));
            return $pdf->download('statistiques.pdf');
        }
    
        return view('stat.superindex', compact('naisshop', 'deceshop','docteur', 'total', 'selectedMonth', 'selectedYear', 'naissData', 'decesData'));
    }

    
    public function superdownload(Request $request)
    {
        $sousadmin = Auth::guard('doctor')->user();
        $communeAdmin = $sousadmin->nomHop;
        $hopitalName = $sousadmin->nomHop;
        // Récupérer le mois et l'année sélectionnés
        $selectedMonth = $request->input('month', date('m'));
        $selectedYear = $request->input('year', date('Y'));
        // Définir la locale de Carbon en français
        Carbon::setLocale('fr');

        // Récupérer les données
        $naisshopCount = NaissHop::count(); // Compte des naissances
        $deceshopCount = DecesHop::count(); // Compte des décès

         // Récupérer les données par mois et nomHop pour les naissances
        $naissData = NaissHop::where('NomEnf', $communeAdmin) 
                 ->whereYear('created_at', $selectedYear)   // Filtre uniquement par année
                 ->selectRaw('CAST(strftime("%m", created_at) AS INTEGER) as month, COUNT(*) as count')
                 ->groupBy('month')
                 ->orderBy('month')
                 ->pluck('count', 'month')
                 ->toArray();
                // Récupérer les données par mois et nomHop pour les décès
        $decesData = DecesHop::where('nomHop', $communeAdmin) 
                 ->whereYear('created_at', $selectedYear)   // Filtre uniquement par année
                 ->selectRaw('CAST(strftime("%m", created_at) AS INTEGER) as month, COUNT(*) as count')
                 ->groupBy('month')
                 ->orderBy('month')
                 ->pluck('count', 'month')
                 ->toArray();
        $data = [
            'naisshopCount' => $naisshopCount,
            'deceshopCount' => $deceshopCount,
            'hopitalName' => $hopitalName,
            'naissData' => $naissData,
            'decesData' => $decesData,
        ];

        // Générer le PDF
        $pdf = PDF::loadView('stat.pdf', $data);

        // Retourner le PDF en téléchargement
        return $pdf->download('statistiques.pdf');
    }


    public function directeurindex(Request $request)
    {
        $sousadmin = Auth::guard('directeur')->user();
        $communeAdmin = $sousadmin->nomHop;
        $sousAdminId = $sousadmin->id; // Récupérer l'ID du sous-administrateur
        // Récupérer le mois et l'année sélectionnés
        $selectedMonth = $request->input('month', date('m'));
        $selectedYear = $request->input('year', date('Y'));
        
        // Compter le total des déclarations de naissance et de décès pour le mois sélectionné
        $docteur = SousAdmin::where('nomHop', $communeAdmin)
            ->whereMonth('created_at', $selectedMonth)
            ->whereYear('created_at', $selectedYear)
            ->count();
        $naisshop = NaissHop::where('NomEnf', $communeAdmin)
            ->whereMonth('created_at', $selectedMonth)
            ->whereYear('created_at', $selectedYear)
            ->count();
    
        $deceshop = DecesHop::where('nomHop', $communeAdmin)
            ->whereMonth('created_at', $selectedMonth)
            ->whereYear('created_at', $selectedYear)
            ->count();
          // Récupérer les données pour les graphiques
        $naissData = NaissHop::where('NomEnf', $communeAdmin)
            
             ->whereMonth('created_at', $selectedMonth)
             ->whereYear('created_at', $selectedYear)
             ->selectRaw('CAST(strftime("%m", created_at) AS INTEGER) as month, COUNT(*) as count')
             ->groupBy('month')
             ->orderBy('month')
             ->pluck('count', 'month')->toArray();
             // Remplir les données manquantes
        $naissData = array_replace(array_fill(1, 12, 0), $naissData);
        $total = $naisshop + $deceshop ;
             // Récupérer les données de décès
        $decesData = DecesHop::where('nomHop', $communeAdmin)
            
             ->whereMonth('created_at', $selectedMonth)
             ->whereYear('created_at', $selectedYear)
             ->selectRaw('CAST(strftime("%m", created_at) AS INTEGER) as month, COUNT(*) as count')
             ->groupBy('month')
             ->orderBy('month')
             ->pluck('count', 'month')->toArray();
            // Remplir les données manquantes
        $decesData = array_replace(array_fill(1, 12, 0), $decesData);
    
        // Vérifier si le téléchargement en PDF est demandé
        if ($request->has('download_pdf')) {
            $pdf = PDF::loadView('stat.pdf', compact('naisshop', 'deceshop', 'total', 'selectedMonth', 'selectedYear', 'naissData', 'decesData'));
            return $pdf->download('statistiques.pdf');
        }
    
        return view('stat.directeurindex', compact('naisshop', 'deceshop','docteur', 'total', 'selectedMonth', 'selectedYear', 'naissData', 'decesData'));
    }


    public function directeurdownload(Request $request)
    {
        $sousadmin = Auth::guard('directeur')->user();
        $communeAdmin = $sousadmin->nomHop;
        // Récupérer le mois et l'année sélectionnés
        $selectedMonth = $request->input('month', date('m'));
        $selectedYear = $request->input('year', date('Y'));
        // Définir la locale de Carbon en français
        Carbon::setLocale('fr');
        // Récupérer les données
        $naisshopCount = NaissHop::count(); // Compte des naissances
        $deceshopCount = DecesHop::count(); // Compte des décès
        
         // Récupérer les données par mois et nomHop pour les naissances
        $naissData = NaissHop::where('NomEnf', $communeAdmin) 
                 ->whereYear('created_at', $selectedYear)   // Filtre uniquement par année
                 ->selectRaw('CAST(strftime("%m", created_at) AS INTEGER) as month, COUNT(*) as count')
                 ->groupBy('month')
                 ->orderBy('month')
                 ->pluck('count', 'month')
                 ->toArray();
                // Récupérer les données par mois et nomHop pour les décès
        $decesData = DecesHop::where('nomHop', $communeAdmin) 
                 ->whereYear('created_at', $selectedYear)   // Filtre uniquement par année
                 ->selectRaw('CAST(strftime("%m", created_at) AS INTEGER) as month, COUNT(*) as count')
                 ->groupBy('month')
                 ->orderBy('month')
                 ->pluck('count', 'month')
                 ->toArray();
        $data = [
            'naisshopCount' => $naisshopCount,
            'deceshopCount' => $deceshopCount,
            'communeAdmin' => $communeAdmin,
            'naissData' => $naissData,
            'decesData' => $decesData,
        ];
        // Générer le PDF
        $pdf = PDF::loadView('stat.pdf', $data);
        // Retourner le PDF en téléchargement
        return $pdf->download('statistiques.pdf');
    }
}
