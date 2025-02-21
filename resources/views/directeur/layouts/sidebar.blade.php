<aside id="ms-side-nav" class="side-nav fixed ms-aside-scrollable ms-aside-left">
    <!-- Logo -->
    <div class="logo-sn ms-d-block-lg">
      <div style="background-color:azure; margin-top:10px"><a class="pl-0 ml-0 text-center" href="{{ route('directeur.dashboard') }}"> {{ Auth::guard('directeur')->user()->nomHop }} </a></div><br>
      <a href="{{ route('doctor.dashboard') }}" class="text-center ms-logo-img-link"> <img class="ms-user-img ms-img-round" style=" width: 70px; /* Taille du cercle */
        height: 70px; /* Taille du cercle */
        border-radius: 55%; /* Cela rend l'image ronde */
        object-fit: cover;" src="{{ asset('storage/' . (Auth::guard('directeur')->user()->profile_picture ?? 'default-profile.png')) }}" alt="Profile Picture"></a>
      <h5 class="text-center text-white mt-2">{{ Auth::guard('directeur')->user()->name  }} {{ Auth::guard('directeur')->user()->prenom  }}</h5>
      
    </div>
    <!-- Navigation -->
    <ul class="accordion ms-main-aside fs-14" id="side-nav-accordion">
      <!-- Dashboard -->
      <li class="menu-item">
        <a href="#" class="has-chevron" data-toggle="collapse" data-target="#dashboard" aria-expanded="false" aria-controls="dashboard">
          <span><i class="material-icons fs-16">dashboard</i>Tableau de board </span>
        </a>
        <ul id="dashboard" class="collapse" aria-labelledby="dashboard" data-parent="#side-nav-accordion">
          <li> <a href="{{ route('directeur.dashboard') }}">E-Côte d'Ivoire</a> </li>
        </ul>
      </li>
      <!-- /Dashboard -->
      <!-- Doctor -->
      <li class="menu-item">
        <a href="#" class="has-chevron" data-toggle="collapse" data-target="#doctor" aria-expanded="false" aria-controls="doctor">
          <span><i class="fas fa-stethoscope"></i>Docteur</span>
        </a>
        <ul id="doctor" class="collapse" aria-labelledby="doctor" data-parent="#side-nav-accordion">
          <li> <a href="{{ route('directeur.index') }}">Liste Docteur</a> </li>
        </ul>
      </li>
      <!-- Doctor -->

        <!-- Patient -->
      <li class="menu-item">
        <a href="#" class="has-chevron" data-toggle="collapse" data-target="#patient" aria-expanded="false" aria-controls="patient">
          <span><i class="fas fa-user"></i>Déclaration Naissance</span>
        </a>
        <ul id="patient" class="collapse" aria-labelledby="patient" data-parent="#side-nav-accordion">
          <li> <a href="{{ route('directeur.naissanceindex') }}">Liste Déclaration</a> </li>
        </ul>
      </li>
      <!-- /Patient -->
      <!-- Department -->
      <li class="menu-item">
        <a href="#" class="has-chevron" data-toggle="collapse" data-target="#department" aria-expanded="false" aria-controls="department">
          <span><i class="fas fa-school"></i>Déclaration Décès</span>
        </a>
        <ul id="department" class="collapse" aria-labelledby="department" data-parent="#side-nav-accordion">
          <li> <a href="{{ route('directeur.decesindex') }}">Liste Déclaration</a> </li>
        </ul>
      </li>
      <!-- /Department -->
      <li class="menu-item">
        <a href="#" class="has-chevron" data-toggle="collapse" data-target="#schedule" aria-expanded="false" aria-controls="schedule">
          <span><i class="fas fa-list-alt"></i>Statistique</span>
        </a>
        <ul id="schedule" class="collapse" aria-labelledby="schedule" data-parent="#side-nav-accordion">
          <li> <a href="{{ route('stats.directeurindex') }}">Statistique</a> </li>
        </ul>
      </li><br><br>
      <!-- /Schedule -->
  </aside>
  <!-- Main Content -->
  <main class="body-content">