@extends('doctor.layouts.template')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="ms-content-wrapper">
    <div class="row w-full">
        <!-- Notifications Widgets -->
        <div class="col-xl-3 col-md-6 col-sm-6">
            <a href="#">
                <div class="ms-card card-gradient-custom ms-widget ms-infographics-widget ms-p-relative">
                    <div class="ms-card-body media">
                        <div class="media-body">
                            <h6>Total Docteur</h6>
                            <p class="ms-card-change">{{ $docteur }}</p>
                        </div>
                    </div>
                    <i class="fas fa-stethoscope ms-icon-mr"></i>
                </div>
            </a>
        </div>

        <div class="col-xl-3 col-md-6 col-sm-6">
            <a href="#">
                <div class="ms-card card-gradient-custom ms-widget ms-infographics-widget ms-p-relative">
                    <div class="ms-card-body media">
                        <div class="media-body">
                            <h6>Total Naissance</h6>
                            <p class="ms-card-change">{{ $naisshop }}</p>
                        </div>
                    </div>
                    <i class="fas fa-child ms-icon-mr"></i>
                </div>
            </a>
        </div>

        <div class="col-xl-3 col-md-6 col-sm-6">
            <a href="#">
                <div class="ms-card card-gradient-custom ms-widget ms-infographics-widget ms-p-relative">
                    <div class="ms-card-body media">
                        <div class="media-body">
                            <h6 class="bold">Total Décès</h6>
                            <p class="ms-card-change">{{ $deceshop }}</p>
                        </div>
                    </div>
                    <i class="fa fa-church ms-icon-mr"></i>
                </div>
            </a>
        </div>

        <div class="col-xl-3 col-md-6 col-sm-6">
            <a href="#">
                <div class="ms-card card-gradient-custom ms-widget ms-infographics-widget ms-p-relative">
                    <div class="ms-card-body media">
                        <div class="media-body">
                            <h6 class="bold">Total Déclaration</h6>
                            <p class="ms-card-change">{{ $total }}</p>
                        </div>
                    </div>
                    <i class="fas fa-briefcase-medical ms-icon-mr"></i>
                </div>
            </a>
        </div>
    </div>

    <!-- Progress bars -->
    <div class="progress">
        <div class="progress-bar bg-primary" role="progressbar" style="width: 45.07%" aria-valuenow="45.07" aria-valuemin="0" aria-valuemax="100"></div>
        <div class="progress-bar bg-danger" role="progressbar" style="width: 29.05%" aria-valuenow="29.05" aria-valuemin="0" aria-valuemax="100"></div>
        <div class="progress-bar bg-warning" role="progressbar" style="width: 25.48%" aria-valuenow="25.48" aria-valuemin="0" aria-valuemax="100"></div>
    </div>

      <!-- Graphiques -->
  <div class="row mt-4">
    <div class="col-lg-6">
        <div class="card shadow">
            <canvas id="naissChart"></canvas>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card shadow">
            <canvas id="decesChart"></canvas>
        </div>
    </div>
</div>
<script>
    const naissData = @json(array_values($naissData)); 
    const decesData = @json(array_values($decesData)); 
    const months = ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil', 'Août', 'Sep', 'Oct', 'Nov', 'Déc'];
    const naissCtx = document.getElementById('naissChart').getContext('2d');
    const decesCtx = document.getElementById('decesChart').getContext('2d');

    // Créer le graphique des naissances
    const naissChart = new Chart(naissCtx, {
        type: 'bar',
        data: {
            labels: months,
            datasets: [{
                label: 'Naissances',
                data: naissData,
                backgroundColor: 'rgba(75, 192, 192, 1)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    max: Math.max(...naissData), // Max égal au maximum de naissData
                    ticks: {
                        stepSize: 1 // Ajustez le stepSize pour que chaque carré représente une unité
                    },
                    title: {
                        display: true,
                        text: 'Nombre de Naissances'
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'Mois'
                    }
                }
            },
            plugins: {
                datalabels: {
                    anchor: 'end',
                    align: 'end',
                    formatter: function(value) {
                        return Math.floor(value); // Affiche les valeurs en entiers
                    },
                    color: '#fff',
                }
            }
        }
    });

    // Créer le graphique des décès
    const decesChart = new Chart(decesCtx, {
        type: 'bar',
        data: {
            labels: months,
            datasets: [{
                label: 'Décès',
                data: decesData,
                backgroundColor: 'rgba(255, 99, 132, 1)',
                borderColor: 'rgba(255, 99, 132, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    max: Math.max(...decesData), // Max égal au maximum de decesData
                    ticks: {
                        stepSize: 1 // Ajustez le stepSize pour que chaque carré représente une unité
                    },
                    title: {
                        display: true,
                        text: 'Nombre de Décès'
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'Mois'
                    }
                }
            },
            plugins: {
                datalabels: {
                    anchor: 'end',
                    align: 'end',
                    formatter: function(value) {
                        return Math.floor(value); // Affiche les valeurs en entiers
                    },
                    color: '#fff',
                }
            }
        }
    });
</script>
@endsection
