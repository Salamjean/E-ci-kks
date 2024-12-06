<nav class="navbar ms-navbar">
    <div class="ms-aside-toggler ms-toggler pl-0" data-target="#ms-side-nav" data-toggle="slideLeft">
      <span class="ms-toggler-bar bg-white"></span>
      <span class="ms-toggler-bar bg-white"></span>
      <span class="ms-toggler-bar bg-white"></span>
    </div>
    <div class="logo-sn logo-sm ms-d-block-sm">
      <a class="pl-0 ml-0 text-center navbar-brand mr-0" href="index.html"><img src="https://via.placeholder.com/84x41" alt="logo"> </a>
    </div>
    <ul class="ms-nav-list ms-inline mb-0" id="ms-nav-options">
      <li class="ms-nav-item  ms-d-none">
        <a href="#mymodal" class="text-white" data-toggle="modal"><i class="flaticon-spreadsheet mr-2"></i> Make an appointment</a>
      </li>

      <li class="ms-nav-item ms-d-none">
        <a href="#prescription" class="text-white" data-toggle="modal"><i class="flaticon-pencil mr-2"></i> Write a prescription</a>
      </li>

      <li class="ms-nav-item ms-d-none">
        <a href="#report1" class="text-white" data-toggle="modal"><i class="flaticon-list mr-2"></i> Generate Report</a>
      </li>



      <li class="ms-nav-item dropdown">
        <a href="#" class="text-disabled ms-has-notification" id="notificationDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="flaticon-bell"></i></a>
        <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="notificationDropdown">
          <li class="dropdown-menu-header">
            <h6 class="dropdown-header ms-inline m-0"><span class="text-disabled">Notifications</span></h6>
            <span class="badge badge-pill badge-info">4 New</span>
          </li>
          <li class="dropdown-divider"></li>
          <li class="ms-scrollable ms-dropdown-list">
            <a class="media p-2" href="#">
              <div class="media-body">
                <span>12 ways to improve your crypto dashboard</span>
                <p class="fs-10 my-1 text-disabled"><i class="material-icons">access_time</i> 30 seconds ago</p>
              </div>
            </a>
            <a class="media p-2" href="#">
              <div class="media-body">
                <span>You have newly registered users</span>
                <p class="fs-10 my-1 text-disabled"><i class="material-icons">access_time</i> 45 minutes ago</p>
              </div>
            </a>
            <a class="media p-2" href="#">
              <div class="media-body">
                <span>Your account was logged in from an unauthorized IP</span>
                <p class="fs-10 my-1 text-disabled"><i class="material-icons">access_time</i> 2 hours ago</p>
              </div>
            </a>
            <a class="media p-2" href="#">
              <div class="media-body">
                <span>An application form has been submitted</span>
                <p class="fs-10 my-1 text-disabled"><i class="material-icons">access_time</i> 1 day ago</p>
              </div>
            </a>
          </li>
          <li class="dropdown-divider"></li>
          <li class="dropdown-menu-footer text-center">
            <a href="#">View all Notifications</a>
          </li>
        </ul>
      </li>
      <li class="ms-nav-item ms-nav-user dropdown">
        <a href="#" id="userDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <img class="ms-user-img ms-img-round float-right" style=" width: 50px; /* Taille du cercle */
          height: 40px; /* Taille du cercle */
          border-radius: 50%; /* Cela rend l'image ronde */
          object-fit: cover;" src="{{ asset('storage/' . (Auth::guard('sous_admin')->user()->profile_picture ?? 'default-profile.png')) }}" alt="Profile Picture"> </a>
        <ul class="dropdown-menu dropdown-menu-right user-dropdown" aria-labelledby="userDropdown">
          <li class="dropdown-menu-header">
            <h6 class="dropdown-header ms-inline m-0"><span class="text-disabled">Bienvenue, {{ Auth::guard('sous_admin')->user()->name }} {{ Auth::guard('sous_admin')->user()->prenom }}</span></h6>
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
            <a class="media fs-14 p-2" href="{{ route('sous_admin.logout') }}"> <span><i class="flaticon-shut-down mr-2"></i> Logout</span> </a>
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