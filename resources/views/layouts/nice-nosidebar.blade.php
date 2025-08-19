<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'HydroSite') - {{ config('app.name', 'HydroSite') }}</title>
    <!-- Favicons -->
    <link href="{{asset('theme/common/img/favicon.png')}}" rel="icon">
    <link href="{{asset('theme/common/img/apple-touch-icon.png')}}" rel="apple-touch-icon">
    <link rel="apple-touch-icon" sizes="180x180" href="{{asset('theme/common/img/apple-touch-icon.png')}}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{asset('theme/common/img/favicon-32x32.png')}}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{asset('theme/common/img/favicon-16x16.png')}}">
    <link rel="manifest" href="{{asset('theme/common/img/site.webmanifest')}}">
    <!-- Google Fonts -->
    <link href="https://fonts.gstatic.com" rel="preconnect">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">
    <!-- Vendor CSS Files -->
    <link href="{{asset('theme/nice/assets/vendor/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{asset('theme/nice/assets/vendor/bootstrap-icons/bootstrap-icons.css')}}" rel="stylesheet">
    <link href="{{asset('theme/nice/assets/vendor/boxicons/css/boxicons.min.css')}}" rel="stylesheet">
    <link href="{{asset('theme/nice/assets/vendor/quill/quill.snow.css')}}" rel="stylesheet">
    <link href="{{asset('theme/nice/assets/vendor/quill/quill.bubble.css')}}" rel="stylesheet">
    <link href="{{asset('theme/nice/assets/vendor/remixicon/remixicon.css')}}" rel="stylesheet">
    <link href="{{asset('theme/nice/assets/vendor/simple-datatables/style.css')}}" rel="stylesheet">
    <!-- Template Main CSS File -->
    <link href="{{asset('theme/nice/assets/css/style.css')}}" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        #main { background-color: #e4ebed; border-radius: 10px; border: 0px solid rgba(0, 0, 0, 0.5); box-shadow: 5 10px rgba(0, 0, 0, 0.9); }
        @media print { .d-print-none { display: none !important; } }
    </style>
</head>
<body>
    <!-- ======= Header ======= -->
    <header id="header" class="header fixed-top d-flex align-items-center d-print-none">
        <div class="d-flex align-items-center justify-content-between d-print-none">
            <a href="{{route('account.dashboard')}}" class="logo d-flex align-items-center">
                <img src="{{asset('theme/common/img/hydrosite_favicon.png')}}" alt="">
                <span class="d-none d-lg-block">HydroSite</span>
            </a>
        </div>
        <div class="search-bar d-print-none">
            <form class="search-form d-flex align-items-center" method="POST" action="#">
                <input type="text" name="query" placeholder="Busqueda general" title="Enter search keyword">
                <button type="submit" title="Search"><i class="bi bi-search"></i></button>
            </form>
        </div>
        <nav class="header-nav ms-auto d-print-none">
            <ul class="d-flex align-items-center">
                <li class="nav-item d-block d-lg-none">
                    <a class="nav-link nav-icon search-bar-toggle " href="#">
                        <i class="bi bi-search"></i>
                    </a>
                </li>
                <li class="nav-item dropdown pe-3">
                    <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
                        <img src="{{asset('theme/nice/assets/img/profile-img.jpg')}}" alt="Profile" class="rounded-circle">
                        <span class="d-none d-md-block dropdown-toggle ps-2">{{ Auth::user()->name }}</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
                        <li class="dropdown-header">
                            <h6>{{ Auth::user()->name }}</h6>
                            <span>
                                @if($user->isSuperAdmin())
                                    <span class="badge bg-primary">Super admin</span>
                                @elseif($user->isAdmin())
                                    <span class="badge bg-warning">Administrador</span>
                                @else
                                    <span class="badge bg-warning">Usuario</span>
                                @endif
                            </span>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        @if($user->isSuperAdmin())
                            <li>
                                <a class="dropdown-item d-flex align-items-center" href="{{route('orgs.index')}}">
                                    <i class="bi bi-person"></i>
                                    <span>Mis Organizaciones</span>
                                </a>
                            </li>
                        @endif
                        <li>
                            <a class="dropdown-item d-flex align-items-center" href="{{route('account.profile')}}">
                                <i class="bi bi-person"></i>
                                <span>Mi Perfil</span>
                            </a>
                        </li>
                        @if($user->isSuperAdmin() || $user->isAdmin())
                            <li>
                                <a class="dropdown-item d-flex align-items-center" href="{{route('orgs.accounts.crearUsuario', ['id' => auth()->user()->org_id])}}">
                                    <i class="bi bi-person"></i>
                                    <span>Crear Usuario</span>
                                </a>
                            </li>
                        @endif
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item d-flex align-items-center" href="{{ route('login.logout') }}">
                                <i class="bi bi-box-arrow-right"></i>
                                <span>Cerrar Sesión</span>
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </nav>
    </header>
    <!-- End Header -->
    <!-- NO SIDEBAR -->
    <main id="main" class="main" style="padding-top: 90px;">
        @yield('content')
    </main>
    <!-- Vendor JS Files -->
    <script src="{{asset('theme/nice/assets/vendor/apexcharts/apexcharts.min.js')}}"></script>
    <script src="{{asset('theme/nice/assets/vendor/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
    <script src="{{asset('theme/nice/assets/vendor/chart.js/chart.umd.js')}}"></script>
    <script src="{{asset('theme/nice/assets/vendor/echarts/echarts.min.js')}}"></script>
    <script src="{{asset('theme/nice/assets/vendor/quill/quill.min.js')}}"></script>
    <script src="{{asset('theme/nice/assets/vendor/simple-datatables/simple-datatables.js')}}"></script>
    <script src="{{asset('theme/nice/assets/vendor/tinymce/tinymce.min.js')}}"></script>
    <script src="{{asset('theme/nice/assets/vendor/php-email-form/validate.js')}}"></script>
    <!-- Template Main JS File -->
    <script src="{{asset('theme/nice/assets/js/main.js')}}"></script>
</body>
</html>
