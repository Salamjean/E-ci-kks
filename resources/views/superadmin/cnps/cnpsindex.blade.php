@extends('superadmin.cnps.layouts.template')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

<div class="container-fluid" id="container-wrapper">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
      <h1 class="h3 mb-0 text-gray-800">Listes de toutes les déclarations de décès</h1>
    </div>

    <!-- Row -->
    <div class="row">
      <!-- Datatables -->
      <div class="col-lg-12">
        <div class="card mb-4">
          <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold " style="color: orange">Les déclarations de décès</h6>
          </div>
          <div class="table-responsive p-3">
            <!-- Champ de recherche -->
            <input type="text" id="searchInput" class="form-control mb-3" placeholder="Rechercher...">
        
            <table class="table align-items-center table-flush" id="dataTable">
                <thead class="bg-navbar text-white" style="background-color: orange">
                    <tr style="font-size: 12px">
                        <th class="text-center">N° CMD</th>
                        <th class="text-center">Hôpital</th>
                        <th class="text-center">Commune</th>
                        <th class="text-center">Nom du défunt</th>
                        <th class="text-center">Date de naissance</th>
                        <th class="text-center">Date de décès</th>
                        <th class="text-center">Date et Heure de déclaration</th>
                        <th class="text-center">Nom du docteur</th>
                    </tr>
                </thead>
                
                <tbody>
                    @forelse ($deceshops as $deceshop)
                    <tr style="font-size: 12px">
                        <td>{{ $deceshop->codeCMD }}</td>
                        <td>{{ $deceshop->nomHop }}</td>
                        <td>{{ strtoupper($deceshop->commune) }}</td>
                        <td>{{ $deceshop->NomM .' '.$deceshop->PrM }}</td>
                        <td>{{ $deceshop->DateNaissance }}</td>
                        <td>{{ $deceshop->DateDeces }}</td>
                        <td>{{ $deceshop->created_at }}</td>
                        <td>Dr. {{ $deceshop->sous_admin ? $deceshop->sous_admin->name . ' ' . $deceshop->sous_admin->prenom : 'Demandeur inconnu' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center">Aucune déclaration effectuée</td>
                    </tr>
                    @endforelse
                    
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
        </script>
        </div>
      </div>
@endsection

<script>
  function showImage(imageElement) {
    const modalImage = document.getElementById('modalImage');

    // Vérifier si l'image utilise déjà la valeur de remplacement (image par défaut)
    if (imageElement.src.includes('assets/images/profiles/bébé.jpg')) {
        modalImage.src = imageElement.src; // Utiliser l'image par défaut
    } else {
        modalImage.src = imageElement.src; // Utiliser l'image actuelle (valide)
    }
}

</script>
