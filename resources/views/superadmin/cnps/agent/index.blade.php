@extends('superadmin.agences.cnps.layouts.template')

@section('content')

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<style>
  .etat-en-attente {
    background-color: orange;
    color: black;
}

.etat-validee {
    background-color: green;
    color: white;
}

.etat-refusee {
    background-color: red;
    color: white;
}
.btn{
  background-color: rgb(199, 195, 195);
}
button {
            border: none;
            background: none;
            cursor: pointer;
            font-size: 16px;
        }
        a {
            text-decoration: none;
            color: black;
        }

        a .edit {
            color: #28a745;
            transition: color 0.3s ease;
            
        }
        a .eye {
            color: #3047b8;
            transition: color 0.3s ease;
            text-decoration: none;
        }

        a .delete {
            color: #dc3545;
            transition: color 0.3s ease;
        }

        .edit:hover {
            color: #1e7e34;
        }
        .eye:hover {
            color: #1e617e;
        }
        .delete:hover {
            color: #c82333;
        }

</style>
 <!-- Insertion de SweetAlert2 -->
 <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
 <div class="row" style="width:100%; justify-content:center">
  <div class="row" style="width:100%; justify-content:center">
      @if (Session::get('success1')) <!-- Pour la suppression -->
          <script>
              Swal.fire({
                  icon: 'success',
                  title: 'Suppression réussie',
                  text: '{{ Session::get('success1') }}',
                  showConfirmButton: true,  // Afficher le bouton OK
                  confirmButtonText: 'OK',  // Texte du bouton
                  background: '#ffcccc',   // Couleur de fond personnalisée
                  color: '#b30000'          // Texte rouge foncé
              });
          </script>
      @endif
  
      @if (Session::get('success')) <!-- Pour la modification -->
          <script>
              Swal.fire({
                  icon: 'success',
                  title: 'Action réussie',
                  text: '{{ Session::get('success') }}',
                  showConfirmButton: true,  // Afficher le bouton OK
                  confirmButtonText: 'OK',  // Texte du bouton
                  background: '#ccffcc',   // Couleur de fond personnalisée
                  color: '#006600'          // Texte vert foncé
              });
          </script>
      @endif
  
      @if (Session::get('error')) <!-- Pour une erreur générale -->
          <script>
              Swal.fire({
                  icon: 'error',
                  title: 'Erreur',
                  text: '{{ Session::get('error') }}',
                  showConfirmButton: true,  // Afficher le bouton OK
                  confirmButtonText: 'OK',  // Texte du bouton
                  background: '#f86750',    // Couleur de fond rouge vif
                  color: '#ffffff'          // Texte blanc
              });
          </script>
      @endif
  </div>

<div class="container-fluid" id="container-wrapper">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
      <h1 class="h3 mb-0 text-gray-800">Listes des agents</h1>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('cnpsagences.dashboard') }}">Accueil</a></li>
        <li class="breadcrumb-item">Listes</li>
      </ol>
    </div>

    <!-- Row -->
    <div class="row">
      <!-- Datatables -->
      <div class="col-lg-12">
        <div class="card mb-4">
          <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold " style="color:orange">Informations des agents</h6>
          </div>
          <div class="table-responsive p-3">
            <!-- Champ de recherche -->
            <input type="text" id="searchInput" class="form-control mb-3" placeholder="Rechercher...">
        
            <table class="table align-items-center table-flush" id="dataTable">
                <thead class="bg-navbar text-white" style="background-color: orange">
                    <tr style="font-size: 12px">
                        <th class="text-center">Nom</th>
                        <th class="text-center">Prénoms</th>
                        <th class="text-center">Email</th>
                        <th class="text-center">Contact</th>
                        <th class="text-center">Commune</th>
                        <th colspan="2" class="text-center">Action</th>
                    </tr>
                </thead>
                
                <tbody>
                    @forelse ($agents as $agent)
                    <tr style="font-size: 12px">
                        <td  class="text-center">{{ $agent->name }}</td>
                        <td  class="text-center">{{ $agent->prenom }}</td>
                        <td  class="text-center">{{ $agent->email }}</td>
                        <td  class="text-center">{{ $agent->contact }}</td>
                        <td  class="text-center">{{ $agent->commune }}</td>
                        <td >
                            <form action="{{ route('cnpsagent.edit', $agent->id) }}" method="GET">
                                @csrf
                                <button style="margin-left:50%" type="submit" ><a href="{{ route('cnpsagent.edit', $agent->id) }}" class="edit"><i class="fas fa-edit"></i></a></button>
                            </form>
                        </td>
                        <td>
                        <form id="deleteForm{{ $agent->id }}" action="{{ route('cnpsagent.archive', $agent->id) }}" method="POST" style="display: flex; justify-content:center; align-items:center">
                            @csrf
                            @method('DELETE')
                            <button type="button" class="delete" onclick="confirmDelete({{ $agent->id }})"><i class="fas fa-trash"></i></button>
                        </form>
                    </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center">Aucune demande effectuée</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <script>
            document.getElementById('searchInput').addEventListener('keyup', function() {
                const filter = this.value.toLowerCase();
                const rows = document.querySelectorAll('#dataTable tbody tr');
        
                rows.forEach(row => {
                    const cells = row.querySelectorAll('td');
                    const match = Array.from(cells).some(cell => 
                        cell.textContent.toLowerCase().includes(filter)
                    );
                    row.style.display = match ? '' : 'none';
                });
            });

            function confirmDelete(agentId) {
                Swal.fire({
                    title: 'Êtes-vous sûr?',
                    text: "Vous ne pourrez pas revenir en arrière!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Oui, supprimer!',
                    cancelButtonText: 'Annuler'
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('deleteForm' + agentId).submit();
                    }
                });
            }
        </script>
               
                
                <!-- Modal -->
                <div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="imageModalLabel">Aperçu de l'image</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                            </div>
                            <div class="modal-body text-center">
                                <img id="modalImage" src="{{ asset('assets/images/profiles/bébé.jpg') }}" alt="Image prévisualisée" class="img-fluid">
                            </div>
                        </div>
                    </div>
                </div>
                
                
              </tbody>
          </table>
          
          </div>
        </div>
      </div>

@endsection