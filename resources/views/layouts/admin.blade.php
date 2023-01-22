<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com" />
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet" />

    <!-- Scripts -->

    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <link
      rel="stylesheet"
      href="https://unpkg.com/leaflet@1.9.2/dist/leaflet.css"
      integrity="sha256-sA+zWATbFveLLNqWO2gtiw3HL/lh1giY/Inf1BJ0z14="
      crossorigin="" />
    <script
      src="https://unpkg.com/leaflet@1.9.2/dist/leaflet.js"
      integrity="sha256-o9N1jGDZrf5tS+Ft4gbIK7mYMipq9lqpVJ91xHSyKhg="
      crossorigin=""></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,400,0,0" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
    <link href="https://unpkg.com/bootstrap-table@1.21.0/dist/bootstrap-table.min.css" rel="stylesheet">
    <script src="https://unpkg.com/bootstrap-table@1.21.0/dist/bootstrap-table.min.js"></script>
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css" />
    <script src="https://unpkg.com/leaflet.markercluster@1.4.1/dist/leaflet.markercluster.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.Default.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.css" />
    <script src="https://unpkg.com/leaflet.featuregroup.subgroup@1.0.2/dist/leaflet.featuregroup.subgroup.js"></script>
  </head>

  <body>
    <div id="app">
      <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
        <div class="container">
          <a class="navbar-brand" href="{{ url('/') }}">
            {{ config('app.name', 'Laravel') }}
          </a>
          <button
            class="navbar-toggler"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent"
            aria-expanded="false"
            aria-label="{{ __('Toggle navigation') }}">
            <span class="navbar-toggler-icon"></span>
          </button>

          <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <!-- Left Side Of Navbar -->
            <ul class="navbar-nav me-auto"></ul>

            <!-- Right Side Of Navbar -->
            <ul class="navbar-nav ms-auto">
              <!-- Authentication Links -->
              @guest @if (Route::has('login'))
              <li class="nav-item">
                <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
              </li>
              @endif @if (Route::has('register'))
              <li class="nav-item">
                <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
              </li>
              @endif @else
              <div class="dropdown">
                <input class="form-control" id="searchInput" placeholder="Search" />
                <template id="searchItemTemp">
                  <a id="searchItem" onclick="panToPlace(this.id);" style="cursor: pointer">
                    <h6 id="searchItemTitle">Link 1</h6>
                    <h7 id="searchItemLatlng"><span id="searchItemLat">12.0000</span>,<span id="searchItemLng">12.0000</span></h7>
                    <h7 id="searchItemLikes" class="float-end"
                      ><i class="bi-heart-fill" style="font-size: 8px"></i>
                      <span id="searchItemLikesCounter">0</span>
                    </h7>
                  </a>
                </template>
                <div class="dropdown-content" id="searchListParent">
                  <center>
                    <div class="m-1" style="font-size: 12px; background-color: rgb(229, 229, 229); display: none" id="searchSortedBy">
                      Sorted by likes
                    </div>
                  </center>
                  <div id="searchList"></div>
                  <center>
                    <button class="btn btn-sm btn-dark m-2" id="searchBarLoadMore" style="display: none">Load More</button>
                  </center>
                </div>
              </div>
              <a id="addMarker" style="cursor: pointer" onclick="toggleAddMode(1);"
                ><span class="material-symbols-outlined mt-2"> add </span></a
              >
              <li class="nav-item dropdown">
                <a
                  id="navbarDropdown"
                  class="nav-link dropdown-toggle"
                  href="#"
                  role="button"
                  data-bs-toggle="dropdown"
                  aria-haspopup="true"
                  aria-expanded="false"
                  v-pre>
                  {{ Auth::user()->name }}
                </a>

                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                  <a
                    class="dropdown-item"
                    href="{{ route('admin') }}"
                    onclick="event.preventDefault();document.getElementById('admin-form').submit();">
                    {{ __('Admin Panel') }}
                  </a>
                  <a
                    class="dropdown-item"
                    href="{{ route('logout') }}"
                    onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                    {{ __('Logout') }}
                  </a>

                  <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
                  <form id="admin-form" action="{{ route('admin') }}" method="GET" class="d-none">@csrf</form>
                </div>
              </li>
              @endguest
            </ul>
          </div>
        </div>
      </nav>

      <main class="">
        <div class="d-flex" id="wrapper">
          @include('admin.sidebar')

          <!-- Page content wrapper-->
          <div id="page-content-wrapper">
            <nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom">
              <div class="container-fluid">
                <button class="btn btn-dark" id="sidebarToggle">
                  <span class="material-symbols-outlined text-white align-middle"> menu </span>
                </button>
              </div>
            </nav>

            @yield('content')
          </div>
        </div>
      </main>
    </div>
  </body>
</html>
