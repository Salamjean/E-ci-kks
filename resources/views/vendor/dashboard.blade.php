@extends('vendor.layouts.template')

@section('content')

<div class="container-fluid" id="container-wrapper">
  <div class="d-sm-flex align-items-center justify-content-between mb-1">
    <div class="font-semibold text-xl text-gray-800 text-center" style="display: flex; text-align:center; font-weight:bold; font-size:20px;">
      Bienvenue sur la page de la mairie de {{ Auth::guard('vendor')->user()->name }}
    </div>
    <br>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{ route('vendor.dashboard') }}">Accueil</a></li>
      <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
    </ol>
  </div>

  <div class="row mb-0">
    <!-- Total Demande Extrait Naissance -->
    <div class="col-xl-3 col-md-6 mb-2">
      <div class="card h-100">
        <div class="card-body text-center">
          <div class="text-xs font-weight-bold text-uppercase mb-4">Total Demande Extrait Naissance</div>
          <div class="h2 mb-0 font-weight-bold text-gray-800">{{ $Naiss }}</div>
          <i class="fas fa-user fa-2x text-primary"></i>
        </div>
      </div>
    </div>

    <!-- Total Demande Extrait Décès -->
    <div class="col-xl-3 col-md-6 mb-2">
      <div class="card h-100">
        <div class="card-body text-center">
          <div class="text-xs font-weight-bold text-uppercase mb-4">Total Demande Extrait Décès</div>
          <div class="h2 mb-0 font-weight-bold text-gray-800">{{ $decesdash + $decesdejadash }}</div>
          <i class="fas fa-school fa-2x text-success"></i>
        </div>
      </div>
    </div>

    <!-- Total Demande Acte Mariage -->
    <div class="col-xl-3 col-md-6 mb-2">
      <div class="card h-100">
        <div class="card-body text-center">
          <div class="text-xs font-weight-bold text-uppercase mb-4">Total Demande Acte Mariage</div>
          <div class="h2 mb-0 font-weight-bold text-gray-800">{{ $mariagedash }}</div>
          <i class="fas fa-ring fa-2x text-warning"></i>
        </div>
      </div>
    </div>

    <!-- Total Demandes -->
    <div class="col-xl-3 col-md-6 mb-2">
      <div class="card h-100">
        <div class="card-body text-center">
          <div class="text-xs font-weight-bold text-uppercase mb-4">Total Demandes</div>
          <div class="h2 mb-0 font-weight-bold text-gray-800">{{ $totalData }}</div>
          <i class="fas fa-list fa-2x text-danger"></i>
        </div>
      </div>
    </div>
  </div>

  <div class="row mb-3">
    <!-- Total déclaration de Naisshop -->
    <div class="col-xl-4 col-md-6 mb-4">
      <div class="card h-100">
        <div class="card-body text-center">
          <div class="text-xs font-weight-bold text-uppercase mb-4">Total Declarations Naissance</div>
          <div class="h2 mb-0 font-weight-bold text-gray-800">{{ $naisshopsdash }}</div>
          <i class="fas fa-baby fa-2x text-info"></i>
        </div>
      </div>
    </div>

    <!-- Total déclaration de Deceshop -->
    <div class="col-xl-4 col-md-6 mb-4">
      <div class="card h-100">
        <div class="card-body text-center">
          <div class="text-xs font-weight-bold text-uppercase mb-4">Total Declarations Deces</div>
          <div class="h2 mb-0 font-weight-bold text-gray-800">{{ $deceshopsdash }}</div>
          <i class="fas fa-school fa-2x text-warning"></i>
        </div>
      </div>
    </div>

    <!-- Total Declarations -->
    <div class="col-xl-4 col-md-6 mb-4">
      <div class="card h-100">
        <div class="card-body text-center">
          <div class="text-xs font-weight-bold text-uppercase mb-4">Total Declarations</div>
          <div class="h2 mb-0 font-weight-bold text-gray-800">{{ $NaissHopTotal }}</div>
          <i class="fas fa-list fa-2x text-danger"></i>
        </div>
      </div>
    </div>
  </div>

  <div class="row mb-3">
    <!-- Taux des Demandes -->
    <div class="col-xl-12 col-lg-5">
      <div class="card mb-2">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
          <h6 class="m-0 font-weight-bold text-primary">Taux des Demandes</h6>
          <form method="GET" action="{{ route('vendor.dashboard') }}" class="form-inline">
            <div class="form-group mr-2">
              <select name="month" class="form-control">
                @for ($m = 1; $m <= 12; $m++)
                  <option value="{{ $m }}" {{ $m == $selectedMonth ? 'selected' : '' }}>
                    {{ date('F', mktime(0, 0, 0, $m, 1)) }}
                  </option>
                @endfor
              </select>
            </div>
            <div class="form-group mr-2">
              <select name="year" class="form-control">
                @for ($y = date('Y'); $y >= date('Y') - 5; $y--)
                  <option value="{{ $y }}" {{ $y == $selectedYear ? 'selected' : '' }}>
                    {{ $y }}
                  </option>
                @endfor
              </select>
            </div>
            <button type="submit" class="btn btn-primary">Filtrer</button>
          </form>
        </div>
        <div class="card-body">
          <div class="mb-2">
            <div class="small text-gray-500">Naissances
              <div class="small float-right"><b>{{ $naissances->count() + $naissancesD->count() }} sur {{ $totalData }} éléments</b></div>
            </div>
            <div class="progress" style="height: 12px;">
              <div class="progress-bar bg-warning" role="progressbar" style="width: {{ $NaissP }}%;" aria-valuenow="{{ $Naiss }}" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
          </div>
          <div class="mb-3">
            <div class="small text-gray-500">Décès
              <div class="small float-right"><b>{{ $deces->count() + $decesdeja->count() }} sur {{ $totalData }} éléments</b></div>
            </div>
            <div class="progress" style="height: 12px;">
              <div class="progress-bar bg-success" role="progressbar" style="width: {{ $DecesP }}%;" aria-valuenow="{{ $Dece }}" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
          </div>
          <div class="mb-3">
            <div class="small text-gray-500">Mariages
              <div class="small float-right"><b>{{ $mariages->count() }} sur {{ $totalData }} éléments</b></div>
            </div>
            <div class="progress" style="height: 12px;">
              <div class="progress-bar bg-danger" role="progressbar" style="width: {{ $mariagePercentage }}%;" aria-valuenow="{{ $mariagePercentage }}" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="row mb-3">
    <!-- Taux des Déclarations -->
    <div class="col-xl-12 col-lg-5">
      <div class="card mb-2">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
          <h6 class="m-0 font-weight-bold text-primary">Taux des Déclarations</h6>
          <form method="GET" action="{{ route('vendor.dashboard') }}" class="form-inline">
            <div class="form-group mr-2">
              <select name="month_hops" class="form-control">
                @for ($m = 1; $m <= 12; $m++)
                  <option value="{{ $m }}" {{ $m == $selectedMonthHops ? 'selected' : '' }}>
                    {{ date('F', mktime(0, 0, 0, $m, 1)) }}
                  </option>
                @endfor
              </select>
            </div>
            <div class="form-group mr-2">
              <select name="year_hops" class="form-control">
                @for ($y = date('Y'); $y >= date('Y') - 5; $y--)
                  <option value="{{ $y }}" {{ $y == $selectedYearHops ? 'selected' : '' }}>
                    {{ $y }}
                  </option>
                @endfor
              </select>
            </div>
            <button type="submit" class="btn btn-primary">Filtrer</button>
          </form>
        </div>
        <div class="card-body">
          <div class="mb-2">
            <div class="small text-gray-500">Naissances
              <div class="small float-right"><b>{{ $naisshopsdash }} sur {{ $totalDataHops }} éléments</b></div>
            </div>
            <div class="progress" style="height: 12px;">
              <div class="progress-bar bg-info" role="progressbar" style="width: {{ $naisshopPercentage }}%;" aria-valuenow="{{ $naisshopPercentage }}" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
          </div>
          <div class="mb-3">
            <div class="small text-gray-500">Décès
              <div class="small float-right"><b>{{ $deceshopsdash }} sur {{ $totalDataHops }} éléments</b></div>
            </div>
            <div class="progress" style="height: 12px;">
              <div class="progress-bar bg-danger" role="progressbar" style="width: {{ $deceshopPercentage }}%;" aria-valuenow="{{ $deceshopPercentage }}" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Fonction pour mettre à jour l'état du message
    function updateStatusMessage(status) {
      const statusElement = document.getElementById('status-message');
      if (statusElement) {
        statusElement.textContent = status;
      }
    }

    // Appel AJAX pour vérifier et mettre à jour le statut
    function fetchDemandeStatus(demandeId) {
      fetch(`/demande-status/${demandeId}`)
        .then(response => response.json())
        .then(data => {
          if (data.status) {
            updateStatusMessage(data.status);
          }
        })
        .catch(error => console.error('Erreur:', error));
    }

    // Supposons que vous ayez un moyen de récupérer l'ID de la demande
    const demandeId = 123; // Id de la demande actuelle
    fetchDemandeStatus(demandeId);

    // Exécution lorsque l'admin ouvre la demande (simulé ici)
    document.getElementById('open-demande-btn').addEventListener('click', function() {
      fetch(`/update-demande-status/${demandeId}`)
        .then(response => response.json())
        .then(data => {
          if (data.status) {
            updateStatusMessage(data.status); // Mettre à jour le message
          }
        });
    });
  });
</script>

@endsection