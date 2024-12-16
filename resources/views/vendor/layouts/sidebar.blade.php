<ul class="navbar-nav sidebar sidebar-light accordion" id="accordionSidebar">
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.html">
      <div class="">
        <img src="{{asset("assets/images/profiles/portbouet.png")}}" style="height:100px" class="mr-7">
      </div>
      <div class="sidebar-brand-text mx-3" style="font-size: 30px">E-CI</div>
    </a>
    <hr class="sidebar-divider my-0">
    <li class="nav-item active">
      <a class="nav-link" href="{{ route('vendor.dashboard') }}" >
        <i class="fas fa-fw fa-tachometer-alt"></i>
        <span>Dashboard</span></a>
    </li>
    <hr class="sidebar-divider">
    <div class="sidebar-heading"  style="font-size: 15px; text-align:center">
     Les Demanades
    </div>
    <li class="nav-item" style="font-size: 15px; text-align:center">
      <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseBootstrap"
        aria-expanded="true" aria-controls="collapseBootstrap">
        <i class="far fa-fw fa-window-maximize"></i>
        <span>Naissances</span>
      </a>
      <div id="collapseBootstrap" class="collapse" aria-labelledby="headingBootstrap" data-parent="#accordionSidebar">
        <div class="bg-white py-2 collapse-inner rounded">
          <h6 class="collapse-header">Naissance</h6>
          <a class="collapse-item" href="{{ route('naissHop.mairieindex') }}">Déclaration-Naissance</a>
          <a class="collapse-item" href="{{ route('naissance.index') }}">Extrait-Naissance</a>
        </div>
      </div>
    </li>
    <li class="nav-item">
      <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseForm" aria-expanded="true"
        aria-controls="collapseForm">
        <i class="fa fa-school"></i>
        <span>Décès</span>
      </a>
      <div id="collapseForm" class="collapse" aria-labelledby="headingForm" data-parent="#accordionSidebar">
        <div class="bg-white py-2 collapse-inner rounded">
          <h6 class="collapse-header">Décès</h6>
          <a class="collapse-item" href="{{ route('deces.mairieDecesindex') }}">Déclaration-Décès</a>
          <a class="collapse-item" href="{{ route('deces.index') }}">Extrait-Décès</a>
        </div>
      </div>
    </li>

    <li class="nav-item">
      <a class="nav-link" href="{{ route('mariage.index') }}">
        <i class="fab fa-fw fa-wpforms"></i>
        <span>Acte de mariage</span>
      </a>
    
  </ul>