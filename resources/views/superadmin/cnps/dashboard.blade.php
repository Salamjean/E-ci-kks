@extends('superadmin.cnps.layouts.template')

@section('content')
<style>
    .dashboard-background {
        background-image: url("{{ asset('assets/images/profiles/cnps.jpg') }}");
        background-size: 30%;
        background-position: center;
        background-repeat: no-repeat;
        background-attachment: fixed;
        min-height: 100vh;
        padding: 20px 20px 20px 40px;
        border-radius: 10px;
    }

    /* Style pour ajouter une ombre au tableau */
    .shadow-table {
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Ombre légère */
        border-radius: 8px; /* Coins arrondis */
        overflow: hidden; /* Pour que l'ombre s'applique correctement */
    }

    /* Supprimer les styles de centrage si vous utilisez col-12 */
    /*
    .center-card-container {
        display: flex;
        justify-content: center;
        align-items: center;
        width: 100%;
        margin-bottom: 20px;
    }

    .center-card {
        max-width: 1000px;
        width: 90%;
    }
    */

</style>
<div class="dashboard-background">
    <h1 class="text-center text-black mb-4">Bienvenue sur le tableau de bord</h1>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row mt-4">
                            <div class="col-sm-4">
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title text-center" style="font-size: 20px;">Nombre total d'agence</h3>
                                        <div class="text-center">
                                            <i class="fa fa-home d-block" style="font-size: 30px; color:orange"></i>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <h1 class="display-4  text-center">{{ $agences }}</h1>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title text-center" style="font-size: 20px;">Nombre total d'agent</h3>
                                        <div class="text-center">
                                            <i class="fa fa-user d-block" style="font-size: 30px; color:orange"></i>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <h1 class="display-4  text-center">{{ $cnpsagent }}</h1>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title text-center" style="font-size: 20px;">Nombre total de déclaration</h3>
                                        <div class="text-center">
                                            <i class="fa fa-church d-block" style="font-size: 30px; color:orange"></i>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <h1 class="display-4 text-center">{{ $deceshops }}</h1>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div><br><br>

    <div class="row justify-content-center">
        <div class="card shadow mb-4 col-11">
            <div class="card-header py-3" style="background-color: orange">
                <h6 class="font-weight-bold text-white text-center" style="font-size: 30px">Les recherches effectuées récemment</h6>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="searchHistoryTable" width="100%" cellspacing="0">
                        <thead>
                            <tr class="text-center">
                                <th>Agent</th>
                                <th>Défunt trouvé</th>
                                <th>Code CMD</th>
                                <th>Terme de recherche</th>
                                <th>Date et Heure</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($rechercheInfo as $history)
                                <tr class="text-center">
                                    <td>{{ $history->agent_name }} {{ $history->agent_prenom }}</td>
                                    <td>{{ $history->defunt_nom ? $history->defunt_nom : 'Défunt non trouvé' }} {{ $history->defunt_prenom }}</td>
                                    <td>{{ $history->codeCMD ? $history->codeCMD : 'Défunt non trouvé' }}</td>
                                    <td>{{ $history->search_term }}</td>
                                    <td>{{ $history->created_at }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
@endsection