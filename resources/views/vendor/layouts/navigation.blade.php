<nav class="navbar navbar-expand navbar-light bg-navbar topbar mb-4 static-top">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <!-- Bouton Sidebar -->
  <button id="sidebarToggleTop" class="btn btn-primary  rounded-circle mr-3">
    <i class="fa fa-bars"></i>
  </button>



  <!-- Icônes du Topbar -->
  <ul class="navbar-nav ml-auto align-items-center">
    <!-- Notifications -->
    <li class="nav-item dropdown no-arrow mx-1">
      <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button" data-toggle="dropdown"
         aria-haspopup="true" aria-expanded="false">
        <i class="fas fa-bell fa-fw"></i>
        @if($alerts->count() > 0)
            <span class="badge badge-danger badge-counter">{{ $alerts->count() }}</span>
        @endif
      </a>
      <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="alertsDropdown">
        <h6 class="dropdown-header">Alertes</h6>
        @forelse ($alerts as $alert)
            <a class="dropdown-item d-flex align-items-center alert-item" href="#"
               data-id="{{ $alert->id }}"
               data-message="{{ $alert->message }}"
               data-date="{{ $alert->created_at->format('d/m/Y H:i') }}">
                <div class="icon-circle bg-primary">
                    <i class="fas fa-file-alt text-white"></i>
                </div>
                <div>
                    <span class="font-weight-bold">{{ $alert->message }}</span>
                    <div class="text-gray-500">{{ $alert->created_at->diffForHumans() }}</div>
                </div>
            </a>
        @empty
            <a class="dropdown-item text-center small text-gray-500" href="#">Aucune alerte disponible</a>
        @endforelse
      </div>
    </li>
    
    
  
  <!-- Modal pour afficher les détails de l'alerte -->
  <div class="modal fade" id="alertDetailsModal" tabindex="-1" role="dialog" aria-labelledby="alertDetailsModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
          <div class="modal-content">
              <div class="modal-header">
                  <h5 class="modal-title" id="alertDetailsModalLabel">Détails de l'alerte</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                  </button>
              </div>
              <div class="modal-body">
                  <p><strong>Message:</strong> <span id="alertMessage"></span></p>
                  <p><strong>Créée le:</strong> <span id="alertCreatedAt"></span></p>
              </div>
          </div>
      </div>
  </div>
    <!-- Profil Utilisateur -->
    <li class="nav-item dropdown no-arrow">
      <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" 
         aria-haspopup="true" aria-expanded="false">
        <!-- Image selon la ville -->
        @php
          $user = Auth::guard('vendor')->user();
          $cities = [
              'yopougon' => 'yopougon.png',
              'plateau' => 'plateau.jpeg',
              'marcory' => 'marcory.png',
              'cocody' => 'cocody.png',
              'abobo' => 'abobo.png',
              'koumassi' => 'koumassi.png',
              'port-bouet' => 'portbouet.png',
              'treichville' => 'treichville.png',
              'attecoube' => 'attecoube.png',
              'adjame' => 'adjame.jpg',
              'songon' => 'songon.png',
          ];
          $image = isset($cities[$user->name]) ? $cities[$user->name] : 'default.png';
        @endphp
        <img class="img-profile rounded-circle" src="{{ asset('assets/images/profiles/' . $image) }}" 
             alt="Logo {{ $user->name }}">
        <span class="ml-2 d-none d-lg-inline text-white small">Mairie de {{ $user->name }}</span>
      </a>
      <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
        <a class="dropdown-item" href="#">
          <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i> Profil
        </a>
        <a class="dropdown-item" href="#">
          <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i> Paramètres
        </a>
        <div class="dropdown-divider"></div>
        <a class="dropdown-item" href="{{ route('vendor.logout') }}">
          <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i> Déconnexion
        </a>
      </div>
    </li>
  </ul>
</nav>
<script>
document.querySelectorAll('.alert-item').forEach(item => {
  item.addEventListener('click', function(event) {
    const message = item.getAttribute('data-message');
    const date = item.getAttribute('data-date');
    const id = item.getAttribute('data-id');

    // Affichage du pop-up avec SweetAlert2
    Swal.fire({
      title: 'Détail de l\'alerte',
      html: `<p><strong>Message :</strong> ${message}</p><p><strong>Créée le :</strong> ${date}</p>`,
      icon: 'info',
      confirmButtonText: 'Fermer',
      didClose: () => markAsRead(id, item)  // Marquer l'alerte comme lue après la fermeture
    });
  });
});

function markAsRead(alertId, alertItem) {
  // Envoyer une requête POST pour marquer l'alerte comme lue
  fetch(`/alerts/${alertId}/mark-as-read`, {
    method: 'POST',
    headers: {
      'X-CSRF-TOKEN': '{{ csrf_token() }}',
      'Content-Type': 'application/json',
    },
  }).then(response => {
    if (response.ok) {
      console.log('Alerte marquée comme lue');
      
      // Supprimer l'alerte du DOM
      alertItem.remove();
      
      // Mettre à jour le compteur d'alertes
      updateAlertBadge();
    } else {
      console.error('Erreur lors de la mise à jour de l\'alerte.');
    }
  }).catch(error => console.error('Erreur : ', error));
}

function updateAlertBadge() {
  // Récupérer le nombre actuel d'alertes
  const alertCount = document.querySelectorAll('.alert-item').length;
  
  // Mettre à jour le badge d'alertes avec le nouveau nombre
  const badge = document.querySelector('.badge-counter');
  
  if (badge) {
    // Si le nombre d'alertes est 0, masquer le badge
    if (alertCount === 0) {
      badge.style.display = 'none';
    } else {
      badge.style.display = 'inline-block';
      badge.textContent = alertCount;
    }
  }
}

</script>

