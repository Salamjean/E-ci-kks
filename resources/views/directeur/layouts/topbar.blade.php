<nav class="navbar ms-navbar">
  <div class="ms-aside-toggler ms-toggler pl-0" data-target="#ms-side-nav" data-toggle="slideLeft">
      <span class="ms-toggler-bar bg-white"></span>
      <span class="ms-toggler-bar bg-white"></span>
      <span class="ms-toggler-bar bg-white"></span>
  </div>
  <div class="logo-sn logo-sm ms-d-block-sm">
      <a class="pl-0 ml-0 text-center navbar-brand mr-0" href="index.html">
          <img src="https://via.placeholder.com/84x41" alt="logo"> 
      </a>
  </div>

  {{-- Ajoute un logo ici centré --}}
  <div class="text-center my-2">
      <img src="{{ asset('assets4/img/logoo.png') }}" alt="Logo" style="width: 100px; height: auto;">
  </div>
  {{-- Fin de l'ajout du logo --}}

      <li class="ms-nav-item ms-nav-user dropdown">
        <a href="#" id="userDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <div class="profile-container" style="display: flex; align-items: center;">
              <img class="ms-user-img ms-img-round" style="width: 50px; height: 50px; border-radius: 50%; object-fit: cover; margin-right: 10px;" src="{{ asset('storage/' . (Auth::guard('directeur')->user()->profile_picture ?? 'default-profile.png')) }}" alt="Profile Picture">
              <span style="color: white; font-weight: bold;">
                Dr. {{ Auth::guard('directeur')->user()->name }} 
              </span>
            </div>
          </a>
          <ul class="dropdown-menu dropdown-menu-right user-dropdown" aria-labelledby="userDropdown">
              <li class="dropdown-menu-header">
                  <h6 class="dropdown-header ms-inline m-0"><span class="text-disabled">Bienvenue, {{ Auth::guard('directeur')->user()->name ?? 'Alain' }} {{ Auth::guard('directeur')->user()->prenom ?? 'Alain' }}</span></h6>
              </li>
              <li class="dropdown-divider"></li>
              <li class="ms-dropdown-list">
                  <a class="media fs-14 p-2" href="pages/prebuilt-pages/user-profile.html"> <span><i class="flaticon-user mr-2"></i> Profile</span> </a>
                  <a class="media fs-14 p-2" href="pages/apps/email.html"> <span><i class="flaticon-mail mr-2"></i> Inbox</span> <span class="badge badge-pill badge-info">3</span> </a>
                  <a class="media fs-14 p-2" href="pages/prebuilt-pages/user-profile.html"> <span><i class="flaticon-gear mr-2"></i> Account Settings</span> </a>
              </li>
              <li class="dropdown-divider"></li>
              <li class="dropdown-menu-footer">
                  <a class="media fs-14 p-2" href="pages/prebuilt-pages/lock-screen.html"> <span><i class="flaticon-security mr-2"></i> Lock</span> </a>
              </li>
              <li class="dropdown-menu-footer">
                  <a class="media fs-14 p-2" href="{{ route('directeur.logout') }}"> <span><i class="flaticon-shut-down mr-2"></i> Logout</span> </a>
              </li>
          </ul>
      </li>
  </ul>
  <div class="ms-toggler ms-d-block-sm pr-0 ms-nav-toggler" data-toggle="slideDown" data-target="#ms-nav-options">
      <span class="ms-toggler-bar bg-white"></span>
      <span class="ms-toggler-bar bg-white"></span>
      <span class="ms-toggler-bar bg-white"></span>
  </div>
</nav>