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

    <link
        href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i"
        rel="stylesheet">



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
        body {

            background-color: #f8f9fa;

        }



        #main {

            background-color: #e4ebed;

            border-radius: 10px;

            border: 0px solid rgba(0, 0, 0, 0.5);

            /* Borde sutil oscuro */

            box-shadow: 5 10px rgba(0, 0, 0, 0.9);

            /* Sombra suave adicional (opcional) */

        }



        @media print {

            .d-print-none {

                display: none !important;

            }

        }



        /* Efectos al pasar el mouse (hover) */

        .nav-link:hover .icono-principal {

            color: #1545d1;

            /* Cambio de color */

            transform: translateX(50px);

            /* Desplazamiento horizontal */

        }



        /* Estilo base para los items del menú */

        .sidebar-nav .nav-item .nav-link {

            transition: all 0.4s cubic-bezier(0.25, 0.8, 0.25, 1);

            /* Transición suave */

            border-radius: 9px;

            /* Bordes más redondeados */

            margin: 8px 8px;

            /* Mayor espaciado entre items */

            padding: 10px 15px;

            /* Padding más generoso */

            border: 1px solid rgba(0, 0, 0, 0.1);

            /* Borde sutil */

            background-color: rgba(255, 255, 255, 0.9);

            /* Fondo blanco semi-transparente */

        }



        /* Efecto al pasar el mouse */

        .sidebar-nav .nav-item .nav-link:hover {

            background: linear-gradient(135deg, #e4eaf0 0%, #e9ecef 100%);

            /* Gradiente gris claro */

            transform: translateX(8px);

            /* Mayor desplazamiento */

            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);

            /* Sombra más pronunciada */

            border: 1px solid rgba(0, 0, 0, 0.15);

            /* Borde más visible */

            border-left: 3px solid #4e73df;

            /* Borde izquierdo azul como acento */



        }



        /* Efecto para íconos y texto */

        .sidebar-nav .nav-item:hover i,

        .sidebar-nav .nav-item:hover span {

            color: #2c3e50 !important;

            /* Texto más oscuro al hover */

        }



        /* Efecto para la flecha desplegable */

        .sidebar-nav .nav-item:hover .bi-chevron-down {

            transform: rotate(180deg) translateY(2px);

            /* Rotación con ajuste fino */

        }
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



            <i class="bi bi-list toggle-sidebar-btn"></i>



        </div><!-- End Logo -->



        <div class="search-bar d-print-none">

            <form class="search-form d-flex align-items-center" method="POST" action="#">

                <input type="text" name="query" placeholder="Busqueda general" title="Enter search keyword">

                <button type="submit" title="Search"><i class="bi bi-search"></i></button>

            </form>

        </div><!-- End Search Bar -->



        <nav class="header-nav ms-auto d-print-none">

            <ul class="d-flex align-items-center">



                <li class="nav-item d-block d-lg-none">

                    <a class="nav-link nav-icon search-bar-toggle " href="#">

                        <i class="bi bi-search"></i>

                    </a>

                </li><!-- End Search Icon-->





                </a><!-- End Messages Icon -->

                <!-- <pre>RUT de Auth: {{ Auth::user() ?? 'NO RUT' }}</pre> -->

                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow messages">

                    <li class="dropdown-header">

                        You have 3 new messages

                        <a href="#"><span class="badge rounded-pill bg-primary p-2 ms-2">View all</span></a>

                    </li>

                    <li>

                        <hr class="dropdown-divider">

                    </li>



                    <li class="message-item">

                        <a href="#">

                            <img src="{{asset('theme/nice/assets/img/messages-1.jpg')}}" alt="" class="rounded-circle">

                            <div>

                                <h4>Maria Hudson</h4>

                                <p>Velit asperiores et ducimus soluta repudiandae labore officia est ut...</p>

                                <p>4 hrs. ago</p>

                            </div>

                        </a>

                    </li>

                    <li>

                        <hr class="dropdown-divider">

                    </li>



                    <li class="message-item">

                        <a href="#">

                            <img src="{{asset('theme/nice/assets/img/messages-2.jpg')}}" alt="" class="rounded-circle">

                            <div>

                                <h4>Anna Nelson</h4>

                                <p>Velit asperiores et ducimus soluta repudiandae labore officia est ut...</p>

                                <p>6 hrs. ago</p>

                            </div>

                        </a>

                    </li>

                    <li>

                        <hr class="dropdown-divider">

                    </li>



                    <li class="message-item">

                        <a href="#">

                            <img src="{{asset('theme/nice/assets/img/messages-3.jpg')}}" alt="" class="rounded-circle">

                            <div>

                                <h4>David Muldon</h4>

                                <p>Velit asperiores et ducimus soluta repudiandae labore officia est ut...</p>

                                <p>8 hrs. ago</p>

                            </div>

                        </a>

                    </li>

                    <li>

                        <hr class="dropdown-divider">

                    </li>



                    <li class="dropdown-footer">

                        <a href="#">Show all messages</a>

                    </li>



                </ul><!-- End Messages Dropdown Items -->



                </li><!-- End Messages Nav -->



                <li class="nav-item dropdown pe-3">



                    <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">

                        <img src="{{asset('theme/nice/assets/img/profile-img.jpg')}}" alt="Profile"
                            class="rounded-circle">

                        <span class="d-none d-md-block dropdown-toggle ps-2">{{ Auth::user()->name }}</span>

                    </a><!-- End Profile Iamge Icon -->



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

                        <li>

                            <hr class="dropdown-divider">

                        </li>

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

                                <a class="dropdown-item d-flex align-items-center"
                                    href="{{route('orgs.accounts.crearUsuario', ['id' => auth()->user()->org_id])}}">

                                    <i class="bi bi-person"></i>

                                    <span>Crear Usuario</span>

                                </a>

                            </li>

                        @endif

                        <li>

                            <hr class="dropdown-divider">

                        </li>



                        <li>

                            <a class="dropdown-item d-flex align-items-center" href="{{ route('login.logout') }}">

                                <i class="bi bi-box-arrow-right"></i>

                                <span>Cerrar Sesión</span>

                            </a>

                        </li>



                    </ul><!-- End Profile Dropdown Items -->

                </li><!-- End Profile Nav -->



            </ul>

        </nav><!-- End Icons Navigation -->



    </header>





    <!-- End Header -->

    <!-- ======= Sidebar ======= -->

    <aside id="sidebar" class="sidebar d-print-none">

        @php

            $planId = Auth::user()->plan_id;

            $esencial = in_array($planId, [0]);

            $intermedio = in_array($planId, [1]);

            $avanzado = in_array($planId, [2]);



          @endphp

        @php

            $perfilId = Auth::user()->perfil_id;

        @endphp



        <ul class="sidebar-nav" id="sidebar-nav">

            {{-- SuperAdmin: todo --}}

            @if($user->isSuperAdmin() || $perfilId == 0)

                @if(Request::is('org/*'))

                    {{-- Repite aquí TODO el menú completo de org/* (Accesos Directos + Módulos) --}}

                    <li class="nav-heading">Accesos Directos</li>

                    <li class="nav-item">

                        <a href="{{ route('orgs.dashboard', ['id' => auth()->user()->org_id]) }}"
                            class="nav-link {{ (isset($active) && $active == 'orgs.dashboard') ? '' : 'collapsed' }}">

                            <i class="bi bi-speedometer2" style="color: orange;"></i><span>Dashboard</span>

                        </a>

                    </li>

                    <li class="nav-item">

                        <a class="nav-link {{ $active == 'orgs.readings.index' ? '' : 'collapsed' }}"
                            href="{{route('orgs.readings.index', ['id' => auth()->user()->org_id])}}">

                            <i class="bi bi-clipboard-plus" style="color: blue;"></i>

                            <span>Ingreso de Lecturas</span>

                        </a>

                    </li>

                    <li class="nav-item">

                        <a class="nav-link {{ $active == 'orgs.payments.index' ? '' : 'collapsed' }}"
                            href="{{route('orgs.payments.index', ['id' => auth()->user()->org_id])}}">

                            <i class="bi bi-credit-card" style="color: green;"></i>

                            <span>Ingresar un pago</span>

                        </a>

                    </li>

                    <li class="nav-item">

                        <a class="nav-link {{ $active == 'orgs.notifications.index' ? '' : 'collapsed' }}"
                            href="{{route('orgs.notifications.index', ['id' => auth()->user()->org_id])}}">

                            <i class="bi bi-bell" style="color: red;"></i>

                            <span>Enviar Notificaciones</span>

                        </a>

                    </li>

                    <li class="nav-item">

                        <a class="nav-link {{ $active == 'orgs.operator.index' ? '' : 'collapsed' }}"
                            href="{{route('orgs.operator.index', ['id' => auth()->user()->org_id])}}">

                            <i class="bi bi-journal-arrow-down" style="color: purple;"></i>

                            <span>App Operador</span>

                        </a>

                    </li>

                    <li class="nav-item">

                        <a class="nav-link {{ $active == 'orgs.folios.create' ? '' : 'collapsed' }}"
                            href="{{route('orgs.folios.create', ['id' => auth()->user()->org_id])}}">

                            <i class="bi bi-receipt" style="color: black;"></i>

                            <span>Generar Boletas</span>

                        </a>

                    </li>

                    <li class="nav-heading">Módulos</li>

                    <!-- Control de Clientes -->

                    @php

                        $clientesActive = in_array($active, [

                            'orgs.members.index',

                            'orgs.members.create',

                            'orgs.readings.index',

                            'orgs.readings.histories',

                            'orgs.services.index',

                            'orgs.locations.index',

                            'orgs.locations.create',

                        ]);

                    @endphp

                    <li class="nav-item">

                        <a class="nav-link {{ $clientesActive ? '' : 'collapsed' }}" data-bs-target="#module-nav"
                            data-bs-toggle="collapse" href="#">

                            <i class="bi bi-people-fill" style="color: #ffbb2c;"></i><span>CONTROL DE CLIENTES</span><i
                                class="bi bi-chevron-down ms-auto"></i>

                        </a>

                        <ul id="module-nav" class="nav-content collapse {{ $clientesActive ? 'show' : '' }}"
                            data-bs-parent="#sidebar-nav">

                            <li>

                                <a href="{{route('orgs.readings.index', ['id' => auth()->user()->org_id])}}"
                                    class="{{ $active == 'orgs.readings.index' ? 'active' : '' }}">

                                    <i class="bi bi-circle"></i><span>Ingreso de Lecturas</span>

                                </a>

                            </li>

                            <li>

                                <a href="{{route('orgs.readings.history', ['id' => auth()->user()->org_id])}}"
                                    class="{{ $active == 'orgs.readings.histories' ? 'active' : '' }}">

                                    <i class="bi bi-circle"></i><span>Historial de Lecturas y Consumo</span>

                                </a>

                            </li>

                            <li>

                                <a href="{{route('orgs.members.index', ['id' => auth()->user()->org_id])}}"
                                    class="{{ $active == 'orgs.members.index' ? 'active' : '' }}">

                                    <i class="bi bi-circle"></i><span>Lista de Clientes</span>

                                </a>

                            </li>

                            <li>

                                <a href="{{route('orgs.services.index', ['id' => auth()->user()->org_id])}}"
                                    class="{{ $active == 'orgs.services.index' ? 'active' : '' }}">

                                    <i class="bi bi-circle"></i><span>Lista de Servicios</span>

                                </a>

                            </li>

                            <li>

                                <a href="{{route('orgs.members.create', ['id' => auth()->user()->org_id])}}"
                                    class="{{ $active == 'orgs.members.create' ? 'active' : '' }}">

                                    <i class="bi bi-circle"></i><span>Agregar Cliente</span>

                                </a>

                            </li>



                            <li>

                                <a href="{{route('orgs.locations.create', ['id' => auth()->user()->org_id])}}"
                                    class="{{ $active == 'orgs.locations.create' ? 'active' : '' }}">

                                    <i class="bi bi-circle"></i><span>Agregar Sector</span>

                                </a>

                            </li>

                            <li>

                                <a href="{{route('orgs.locations.index', ['id' => auth()->user()->org_id])}}"
                                    class="{{ $active == 'orgs.locations.index' ? 'active' : '' }}">

                                    <i class="bi bi-circle"></i><span>Lista de Sectores</span>

                                </a>

                            </li>

                            <li>

                                <a href="{{route('orgs.locations.panelcontrolrutas', ['id' => auth()->user()->org_id])}}"
                                    class="{{ $active == 'orgs.locations.panelcontrolrutas' ? 'active' : '' }}">

                                    <i class="bi bi-circle"></i><span>Panel de Control Rutas</span>

                                </a>

                            </li>

                        </ul>

                    </li>



                    <!-- Facturación -->

                    @php

                        $facturacionActive = in_array($active, [

                            'orgs.sections.index',

                            'orgs.sections.create',

                            'orgs.folios.index',

                            'orgs.folios.create',

                            'orgs.payments.index',

                            'orgs.folios.histories',

                            'orgs.payments.histories',

                            'orgs.historyDTE'

                        ]);

                    @endphp

                    <li class="nav-item">

                        <a class="nav-link {{ $facturacionActive ? '' : 'collapsed' }}" data-bs-target="#facturacion-nav"
                            data-bs-toggle="collapse" href="#">

                            <i class="bi bi-cash-coin" style="color: green;"></i><span>FACTURACIÓN</span><i
                                class="bi bi-chevron-down ms-auto"></i>

                        </a>

                        <ul id="facturacion-nav" class="nav-content collapse {{ $facturacionActive ? 'show' : '' }}"
                            data-bs-parent="#sidebar-nav">

                            <li>

                                <a href="{{route('orgs.payments.index', ['id' => auth()->user()->org_id])}}"
                                    class="{{ $active == 'orgs.payments.index' ? 'active' : '' }}">

                                    <i class="bi bi-circle"></i><span>Ingresar un Pago</span>

                                </a>

                            </li>

                            <li>

                                <a href="{{route('orgs.folios.create', ['id' => auth()->user()->org_id])}}"
                                    class="{{ in_array($active, ['orgs.folios.index', 'orgs.folios.create']) ? 'active' : '' }}">

                                    <i class="bi bi-circle"></i><span>Generar Boletas</span>

                                </a>

                            </li>

                            <li>

                                <a href="{{route('orgs.sections.index', ['id' => auth()->user()->org_id])}}"
                                    class="{{ in_array($active, ['orgs.sections.index', 'orgs.sections.create']) ? 'active' : '' }}">

                                    <i class="bi bi-circle"></i><span>Cálculo de Tarifas</span>

                                </a>

                            </li>

                            <li>

                                <a href="{{route('orgs.payments.history', ['id' => auth()->user()->org_id])}}"
                                    class="{{ $active == 'orgs.payments.histories' ? 'active' : '' }}">

                                    <i class="bi bi-circle"></i><span>Historial de Pagos</span>

                                </a>

                            </li>





                            <li>

                                <a href="{{route('orgs.historyDTE', ['id' => auth()->user()->org_id])}}"
                                    class="{{ $active == 'orgs.historyDTE' ? 'active' : '' }}">

                                    <i class="bi bi-circle"></i><span>Historial de DTE</span>

                                </a>

                            </li>



                            <li>

                                <a href="{{route('orgs.folios.index', ['id' => auth()->user()->org_id])}}"
                                    class="{{ $active == 'orgs.folios.histories' ? 'active' : '' }}">

                                    <i class="bi bi-circle"></i><span>Historial de Folios</span>

                                </a>

                            </li>













                        </ul>

                    </li>

                    <!-- Contable -->

                    @php

                        $contableActive = in_array($active, [
                            'orgs.libro.caja',
                            'cuentas_iniciales.show',
                            'configuracion_cuentas_original.show',
                            'registro_ingresos_egresos.show'
                        ]);

                    @endphp

                    <li class="nav-item">

                        <a class="nav-link {{ $contableActive ? '' : 'collapsed' }}" data-bs-target="#contable-nav"
                            data-bs-toggle="collapse" href="#">

                            <i class="bi bi-card-checklist" style="color: blue;"></i><span>CONTABLE </span><i
                                class="bi bi-chevron-down ms-auto"></i>

                        </a>

                        <ul id="contable-nav" class="nav-content collapse {{ $contableActive ? 'show' : '' }}"
                            data-bs-parent="#sidebar-nav">

                            <li>
                                <a href="{{ route('cuentas_iniciales.show', ['id' => auth()->user()->org_id]) }}" 
                                   class="{{ $active == 'cuentas_iniciales.show' ? 'active' : '' }}">
                                    <i class="bi bi-journal-plus" style="color: #d32f2f;"></i>
                                    <span>Config. Cuentas Iniciales</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('registro_ingresos_egresos.show', ['id' => auth()->user()->org_id]) }}" 
                                   class="{{ $active == 'registro_ingresos_egresos.show' ? 'active' : '' }}">
                                    <i class="bi bi-arrow-left-right" style="color: #28a745;"></i>
                                    <span>Registro Ingresos/Egresos</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('giros_depositos.show', ['id' => auth()->user()->org_id]) }}" 
                                   class="{{ $active == 'giros_depositos.show' ? 'active' : '' }}">
                                    <i class="bi bi-bank" style="color: #007bff;"></i>
                                    <span>Giros y Depósitos</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('libro_caja_tabular.show', ['id' => auth()->user()->org_id]) }}" 
                                   class="{{ $active == 'libro_caja_tabular.show' ? 'active' : '' }}">
                                    <i class="bi bi-journal-bookmark" style="color: #6f42c1;"></i>
                                    <span>Libro de Caja Tabular</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('balance.show', ['id' => auth()->user()->org_id]) }}" 
                                   class="{{ $active == 'balance.show' ? 'active' : '' }}">
                                    <i class="bi bi-bar-chart" style="color: #fd7e14;"></i>
                                    <span>Balance</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('conciliacion_bancaria.show', ['id' => auth()->user()->org_id]) }}" 
                                   class="{{ $active == 'conciliacion_bancaria.show' ? 'active' : '' }}">
                                    <i class="bi bi-bank2" style="color: #20c997;"></i>
                                    <span>Conciliación Bancaria</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('informe_rubro.show', ['id' => auth()->user()->org_id]) }}" 
                                   class="{{ $active == 'informe_rubro.show' ? 'active' : '' }}">
                                    <i class="bi bi-pie-chart" style="color: #e83e8c;"></i>
                                    <span>Informe por Rubro</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('movimientos.show', ['id' => auth()->user()->org_id]) }}" 
                                   class="{{ $active == 'movimientos.show' ? 'active' : '' }}">
                                    <i class="bi bi-list-ul" style="color: #6f42c1;"></i>
                                    <span>Movimientos</span>
                                </a>
                            </li>

                        </ul>

                    </li>

                    <!-- Agua Potable -->

                    @php

                        $aguaActive = in_array($active, [

                            'orgs.aguapotable.macromedidor.index',

                            'orgs.aguapotable.cloracion.index',

                            'orgs.aguapotable.analisis.index'

                        ]);

                    @endphp

                    <li class="nav-item">

                        <a class="nav-link {{ $aguaActive ? '' : 'collapsed' }}" data-bs-target="#agua-nav"
                            data-bs-toggle="collapse" href="#">

                            <i class="bi bi-droplet-half" style="color: sky blue;"></i><span>AGUA POTABLE</span>

                            <i class="bi bi-chevron-down ms-auto"></i>

                        </a>

                        <ul id="agua-nav" class="nav-content collapse {{ $aguaActive ? 'show' : '' }}"
                            data-bs-parent="#sidebar-nav">

                            <li>

                                <a href="{{route('orgs.aguapotable.macromedidor.index', ['id' => auth()->user()->org_id])}}"
                                    class="{{ $active == 'orgs.aguapotable.macromedidor.index' ? 'active' : '' }}">

                                    <i class="bi bi-circle"></i><span>Macro Medidor</span>

                                </a>

                            </li>

                        </ul>

                    </li>



                    <!-- Inventario -->

                    @php

                        $inventarioActive = in_array($active, [

                            'orgs.inventories.index',

                            'orgs.inventories.create',

                            'orgs.categories.index',

                            'orgs.categories.create',

                            'orgs.categories.edit'

                        ]);

                    @endphp

                    <li class="nav-item">

                        <a class="nav-link {{ $inventarioActive ? '' : 'collapsed' }}" data-bs-target="#inventories-nav"
                            data-bs-toggle="collapse" href="#">

                            <i class="bi bi-box" style="color: brown;"></i><span>INVENTARIO</span><i
                                class="bi bi-chevron-down ms-auto"></i>

                        </a>

                        <ul id="inventories-nav" class="nav-content collapse {{ $inventarioActive ? 'show' : '' }}"
                            data-bs-parent="#sidebar-nav">

                            <li>

                                <a href="{{route('orgs.inventories.create', ['id' => auth()->user()->org_id])}}"
                                    class="{{ $active == 'orgs.inventories.create' ? 'active' : '' }}">

                                    <i class="bi bi-circle"></i><span>Registro de inventario</span>

                                </a>

                            </li>

                            <li>

                                <a href="{{route('orgs.categories.index', ['id' => auth()->user()->org_id])}}"
                                    class="{{ $active == 'orgs.categories.index' ? 'active' : '' }}">

                                    <i class="bi bi-circle"></i><span>Listado de Categorías</span>

                                </a>

                            </li>

                            <li>

                                <a href="{{ route('orgs.categories.create', ['id' => auth()->user()->org_id]) }}"
                                    class="{{ $active == 'orgs.categories.create' ? 'active' : '' }}">

                                    <i class="bi bi-circle"></i><span>Crear Categoría</span>

                                </a>

                            </li>



                            <li>

                                <a href="{{route('orgs.inventories.index', ['id' => auth()->user()->org_id])}}"
                                    class="{{ $active == 'orgs.inventories.index' ? 'active' : '' }}">

                                    <i class="bi bi-circle"></i><span>Historial de Inventario</span>

                                </a>

                            </li>

                        </ul>

                    </li>





                @else

                    <!-- Menú para usuarios no en organización -->

                    <li class="nav-item">

                        <a class="nav-link {{ $active == 'orgs' ? '' : 'collapsed' }}" href="{{route('orgs.index')}}">

                            <i class="bi bi-grid"></i>

                            <span>Mis Organizaciones</span>

                        </a>

                    </li>

                    <li class="nav-item">

                        <a class="nav-link  {{ $active == 'dashboardmisorganizaciones' ? '' : 'collapsed' }}"
                            href="{{ route('dashboardmisorganizaciones') }}">

                            <i class="bi bi-grid"></i>

                            <span>Dashboard</span>

                        </a>

                    </li>

                    <li class="nav-item">

                        <a class="nav-link collapsed" href="https://hydrosite.cl/public/orgs">

                            <i class="bi bi-grid"></i>

                            <span>Configuración de Planes</span>

                        </a>

                    </li>

                    <li class="nav-item">

                        <a class="nav-link collapsed" href="https://hydrosite.cl/public/orgs">

                            <i class="bi bi-grid"></i>

                            <span>Estado de Cuenta</span>

                        </a>

                    </li>

                    <li class="nav-item">

                        <a class="nav-link collapsed" href="https://hydrosite.cl/public/orgs">

                            <i class="bi bi-grid"></i>

                            <span>Comercial</span>

                        </a>

                    </li>

                    <li class="nav-item">

                        <a class="nav-link collapsed" href="https://hydrosite.cl/public/orgs">

                            <i class="bi bi-grid"></i>

                            <span>Otros</span>

                        </a>

                    </li>

                @endif

            @elseif($user->isAdmin() || $perfilId == 1)



                <li class="nav-heading">Accesos Directos</li>
                @if($esencial) {{-- Reemplaza X por ID mínimo para permitir ingreso lecturas --}}



                    <li class="nav-item">

                        <a class="nav-link {{ $active == 'orgs.readings.index' ? '' : 'collapsed' }}"
                            href="{{route('orgs.readings.index', ['id' => auth()->user()->org_id])}}">

                            <i class="bi bi-clipboard-plus" style="color: blue;"></i>

                            <span>Ingreso de Lecturas</span>

                        </a>

                    </li>

                    <li class="nav-item">

                        <a class="nav-link {{ $active == 'orgs.payments.index' ? '' : 'collapsed' }}"
                            href="{{route('orgs.payments.index', ['id' => auth()->user()->org_id])}}">

                            <i class="bi bi-credit-card" style="color: green;"></i>

                            <span>Ingresar un pago</span>

                        </a>

                    </li>



                    <li class="nav-item">

                        <a class="nav-link {{ $active == 'orgs.folios.create' ? '' : 'collapsed' }}"
                            href="{{route('orgs.folios.create', ['id' => auth()->user()->org_id])}}">

                            <i class="bi bi-receipt" style="color: black;"></i>

                            <span>Generar Boletas</span>

                        </a>

                    </li>



                    <li class="nav-heading">Módulos</li>

                    <!-- Control de Clientes -->

                    @php

                        $clientesActive = in_array($active, [

                            'orgs.members.index',

                            'orgs.members.create',

                            'orgs.readings.index',

                            'orgs.readings.histories',

                            'orgs.services.index',

                            'orgs.locations.index',

                            'orgs.locations.create'

                        ]);

                    @endphp

                    <li class="nav-item">

                        <a class="nav-link {{ $clientesActive ? '' : 'collapsed' }}" data-bs-target="#module-nav"
                            data-bs-toggle="collapse" href="#">

                            <i class="ri-folder-user-line" style="color: blue;"></i><span>CONTROL DE CLIENTES</span><i
                                class="bi bi-chevron-down ms-auto"></i>

                        </a>

                        <ul id="module-nav" class="nav-content collapse {{ $clientesActive ? 'show' : '' }}"
                            data-bs-parent="#sidebar-nav">

                            <li>

                                <a href="{{route('orgs.readings.index', ['id' => auth()->user()->org_id])}}"
                                    class="{{ $active == 'orgs.readings.index' ? 'active' : '' }}">

                                    <span>Ingreso de Lecturas</span>

                                </a>

                            </li>

                            <li>

                                <a href="{{route('orgs.members.index', ['id' => auth()->user()->org_id])}}"
                                    class="{{ $active == 'orgs.members.index' ? 'active' : '' }}">

                                    <i class="bi bi-circle"></i><span>Lista de clientes</span>

                                </a>

                            </li>

                            <li>

                                <a href="{{route('orgs.services.index', ['id' => auth()->user()->org_id])}}"
                                    class="{{ $active == 'orgs.services.index' ? 'active' : '' }}">

                                    <i class="bi bi-circle"></i><span>Lista de Servicios</span>

                                </a>

                            </li>

                            <li>

                                <a href="{{route('orgs.members.create', ['id' => auth()->user()->org_id])}}"
                                    class="{{ $active == 'orgs.members.create' ? 'active' : '' }}">

                                    <i class="bi bi-circle"></i><span>Agregar cliente</span>

                                </a>

                            </li>

                            <li>

                                <a href="{{route('orgs.folios.create', ['id' => auth()->user()->org_id])}}"
                                    class="{{ in_array($active, ['orgs.folios.index', 'orgs.folios.create']) ? 'active' : '' }}">

                                    <i class="bi bi-circle"></i><span>Generar boletas</span>

                                </a>

                            </li>

                            <li>

                                <a href="javascript:void(0);"
                                    class="{{ $active == 'orgs.locations.create' ? 'active' : '' }}  disabled text-muted"
                                    tabindex="-1" aria-disabled="true">

                                    <i class="bi bi-circle"></i><span>Agregar sector <i class="bi bi-lock fs-6"></i></span>

                                </a>

                            </li>

                            <li>

                                <a href="javascript:void(0);"
                                    class="{{ $active == 'orgs.locations.index' ? 'active' : '' }} disabled text-muted"
                                    tabindex="-1" aria-disabled="true">

                                    <i class="bi bi-circle"></i><span>Lista de sectores <i class="bi bi-lock fs-6"></i></span>

                                </a>

                            </li>

                            <li>

                                <a href="javascript:void(0);" class="disabled text-muted" tabindex="-1" aria-disabled="true">

                                    <i class="bi bi-circle"></i><span>Panel de control rutas <i
                                            class="bi bi-lock fs-6"></i></span>

                                </a>

                            </li>

                        </ul>

                    </li>



                    <!-- Facturación -->

                    @php

                        $facturacionActive = in_array($active, [

                            'orgs.sections.index',

                            'orgs.sections.create',

                            'orgs.folios.index',

                            'orgs.folios.create',

                            'orgs.payments.index',

                            'orgs.folios.histories',

                            'orgs.payments.histories',

                            'orgs.historyDTE'

                        ]);

                    @endphp

                    <li class="nav-item">

                        <a class="nav-link {{ $facturacionActive ? '' : 'collapsed' }}" data-bs-target="#facturacion-nav"
                            data-bs-toggle="collapse" href="#">

                            <i class="ri-bill-line" style="color: blue;"></i><span>FACTURACIÓN</span><i
                                class="bi bi-chevron-down ms-auto"></i>

                        </a>

                        <ul id="facturacion-nav" class="nav-content collapse {{ $facturacionActive ? 'show' : '' }}"
                            data-bs-parent="#sidebar-nav">

                            <li>

                                <a href="{{route('orgs.sections.index', ['id' => auth()->user()->org_id])}}"
                                    class="{{ in_array($active, ['orgs.sections.index', 'orgs.sections.create']) ? 'active' : '' }}">

                                    <i class="bi bi-circle"></i><span>Cálculo de tarifas</span>

                                </a>

                            </li>



                            <li>

                                <a href="{{route('orgs.historyDTE', ['id' => auth()->user()->org_id])}}"
                                    class="{{ $active == 'orgs.historyDTE' ? 'active' : '' }}">

                                    <i class="bi bi-circle"></i><span>Historial de DTE</span>

                                </a>

                            </li>



                            <li>

                                <a href="{{route('orgs.folios.index', ['id' => auth()->user()->org_id])}}"
                                    class="{{ $active == 'orgs.folios.histories' ? 'active' : '' }}">

                                    <i class="bi bi-circle"></i><span>Historial de folios</span>

                                </a>

                            </li>

                            <li>

                                <a href="{{route('orgs.payments.index', ['id' => auth()->user()->org_id])}}"
                                    class="{{ $active == 'orgs.payments.index' ? 'active' : '' }}">

                                    <i class="bi bi-circle"></i><span>Ingresar un pago</span>

                                </a>

                            </li>





                            <li>

                                <a href="{{route('orgs.payments.history', ['id' => auth()->user()->org_id])}}"
                                    class="{{ $active == 'orgs.payments.histories' ? 'active' : '' }}">

                                    <i class="bi bi-circle"></i><span>Historial de Pagos</span>

                                </a>

                            </li>



                        </ul>

                    </li>

                    <!-- Contable -->

                    @php

                        $contableActive = in_array($active, [



                        ]);

                    @endphp

                    <li class="nav-item">

                        <a class="nav-link {{ $contableActive ? '' : 'collapsed' }} disabled text-muted" tabindex="-1"
                            aria-disabled="true" data-bs-target="#contable-nav" data-bs-toggle="collapse" href="#">

                            <i class="ri-bill-line ri-bill-line-lock"></i><span>CONTABLE <i
                                    class="bi bi-lock fs-6"></i></span><i class="bi bi-chevron-down ms-auto"></i>

                        </a>

                        <ul id="contable-nav" class="nav-content collapse {{ $contableActive ? 'show' : '' }}"
                            data-bs-parent="#sidebar-nav">





                        </ul>

                    </li>

                    <!-- Agua Potable -->

                    @php

                        $aguaActive = in_array($active, [

                            'orgs.aguapotable.macromedidor.index',

                            'orgs.aguapotable.cloracion.index',

                            'orgs.aguapotable.analisis.index'

                        ]);

                    @endphp

                    <li class="nav-item">

                        <a class="nav-link {{ $aguaActive ? '' : 'collapsed' }}" data-bs-target="#agua-nav"
                            data-bs-toggle="collapse" href="#">

                            <i class="ri-building-4-line" style="color: blue;"></i><span>AGUA POTABLE</span>

                            <i class="bi bi-chevron-down ms-auto"></i>

                        </a>

                        <ul id="agua-nav" class="nav-content collapse {{ $aguaActive ? 'show' : '' }}"
                            data-bs-parent="#sidebar-nav">

                            <li>

                                <a href="{{route('orgs.aguapotable.macromedidor.index', ['id' => auth()->user()->org_id])}}"
                                    class="{{ $active == 'orgs.aguapotable.macromedidor.index' ? 'active' : '' }}">

                                    <i class="bi bi-circle"></i><span>Macro Medidor</span>

                                </a>

                            </li>

                        </ul>

                    </li>

                    <!-- Inventario -->

                    @php

                        $inventarioActive = in_array($active, [

                            'orgs.inventories.index',

                            'orgs.inventories.create',

                            'orgs.categories.index',

                            'orgs.categories.create',

                            'orgs.categories.edit'

                        ]);

                    @endphp

                    <li class="nav-item">

                        <a class="nav-link {{ $inventarioActive ? '' : 'collapsed' }} disabled text-muted" tabindex="-1"
                            aria-disabled="true" data-bs-target="#inventories-nav" data-bs-toggle="collapse" href="#">

                            <i class="ri-archive-line "></i><span>INVENTARIO <i class="bi bi-lock fs-6"></i></span><i
                                class="bi bi-chevron-down ms-auto"></i>

                        </a>

                        <ul id="inventories-nav" class="nav-content collapse {{ $inventarioActive ? 'show' : '' }}"
                            data-bs-parent="#sidebar-nav">

                            <li>

                                <a href="{{route('orgs.inventories.create', ['id' => auth()->user()->org_id])}}"
                                    class="{{ $active == 'orgs.inventories.create' ? 'active' : '' }}">

                                    <i class="bi bi-circle"></i><span>Registro de inventario</span>

                                </a>

                            </li>

                            <li>

                                <a href="{{ route('orgs.categories.create', ['id' => auth()->user()->org_id]) }}"
                                    class="{{ $active == 'orgs.categories.create' ? 'active' : '' }}">

                                    <i class="bi bi-circle"></i><span>Crear Categoría</span>

                                </a>

                            </li>

                            <li>

                                <a href="{{route('orgs.categories.index', ['id' => auth()->user()->org_id])}}"
                                    class="{{ $active == 'orgs.categories.index' ? 'active' : '' }}">

                                    <i class="bi bi-circle"></i><span>Listado de Categorías</span>

                                </a>

                            </li>

                            <li>

                                <a href="{{route('orgs.inventories.index', ['id' => auth()->user()->org_id])}}"
                                    class="{{ $active == 'orgs.inventories.index' ? 'active' : '' }}">

                                    <i class="bi bi-circle"></i><span>Historial de Inventario</span>

                                </a>

                            </li>

                        </ul>

                    </li>







                @endif



                @if($intermedio)

                    <li class="nav-item">

                        <a class="nav-link {{ $active == 'orgs.readings.index' ? '' : 'collapsed' }}"
                            href="{{route('orgs.readings.index', ['id' => auth()->user()->org_id])}}">

                            <i class="bi bi-clipboard-plus" style="color: blue;"></i>

                            <span>Ingreso de Lecturas</span>

                        </a>

                    </li>

                    <li class="nav-item">

                        <a class="nav-link {{ $active == 'orgs.payments.index' ? '' : 'collapsed' }}"
                            href="{{route('orgs.payments.index', ['id' => auth()->user()->org_id])}}">

                            <i class="bi bi-credit-card" style="color: green;"></i>

                            <span>Ingresar un pago</span>

                        </a>

                    </li>



                    <li class="nav-item">

                        <a class="nav-link {{ $active == 'orgs.folios.create' ? '' : 'collapsed' }}"
                            href="{{route('orgs.folios.create', ['id' => auth()->user()->org_id])}}">

                            <i class="bi bi-receipt" style="color: black;"></i>

                            <span>Generar Boletas</span>

                        </a>

                    </li>



                    <li class="nav-heading">Módulos</li>

                    <!-- Control de Clientes -->

                    @php

                        $clientesActive = in_array($active, [

                            'orgs.members.index',

                            'orgs.members.create',

                            'orgs.readings.index',

                            'orgs.readings.histories',

                            'orgs.services.index',

                            'orgs.locations.index',

                            'orgs.locations.create'

                        ]);

                    @endphp

                    <li class="nav-item">

                        <a class="nav-link {{ $clientesActive ? '' : 'collapsed' }}" data-bs-target="#module-nav"
                            data-bs-toggle="collapse" href="#">

                            <i class="ri-folder-user-line" style="color: blue;"></i><span>CONTROL DE CLIENTES</span><i
                                class="bi bi-chevron-down ms-auto"></i>

                        </a>

                        <ul id="module-nav" class="nav-content collapse {{ $clientesActive ? 'show' : '' }}"
                            data-bs-parent="#sidebar-nav">

                            <li>

                                <a href="{{route('orgs.readings.index', ['id' => auth()->user()->org_id])}}"
                                    class="{{ $active == 'orgs.readings.index' ? 'active' : '' }}">

                                    Ingreso de Lecturas</span>

                                </a>

                            </li>

                            <li>

                                <a href="{{route('orgs.members.index', ['id' => auth()->user()->org_id])}}"
                                    class="{{ $active == 'orgs.members.index' ? 'active' : '' }}">

                                    <i class="bi bi-circle"></i><span>Lista de clientes</span>

                                </a>

                            </li>

                            <li>

                                <a href="{{route('orgs.services.index', ['id' => auth()->user()->org_id])}}"
                                    class="{{ $active == 'orgs.services.index' ? 'active' : '' }}">

                                    <i class="bi bi-circle"></i><span>Lista de Servicios</span>

                                </a>

                            </li>

                            <li>

                                <a href="{{route('orgs.members.create', ['id' => auth()->user()->org_id])}}"
                                    class="{{ $active == 'orgs.members.create' ? 'active' : '' }}">

                                    <i class="bi bi-circle"></i><span>Agregar cliente</span>

                                </a>

                            </li>

                            <li>

                                <a href="{{route('orgs.folios.create', ['id' => auth()->user()->org_id])}}"
                                    class="{{ in_array($active, ['orgs.folios.index', 'orgs.folios.create']) ? 'active' : '' }}">

                                    <i class="bi bi-circle"></i><span>Generar boletas</span>

                                </a>

                            </li>

                            <li>

                                <a href="{{route('orgs.locations.create', ['id' => auth()->user()->org_id])}}"
                                    class="{{ $active == 'orgs.locations.create' ? 'active' : '' }}">

                                    <i class="bi bi-circle"></i><span>Agregar sector</span>

                                </a>

                            </li>

                            <li>

                                <a href="{{route('orgs.locations.index', ['id' => auth()->user()->org_id])}}"
                                    class="{{ $active == 'orgs.locations.index' ? 'active' : '' }}">

                                    <i class="bi bi-circle"></i><span>Lista de sectores</span>

                                </a>

                            </li>

                            <li>

                                <a href="javascript:void(0);" class="disabled text-muted" tabindex="-1" aria-disabled="true">

                                    <i class="bi bi-circle"></i><span>Panel de control rutas <i
                                            class="bi bi-lock fs-6"></i></span>

                                </a>

                            </li>

                        </ul>

                    </li>



                    <!-- Facturación -->

                    @php

                        $facturacionActive = in_array($active, [

                            'orgs.sections.index',

                            'orgs.sections.create',

                            'orgs.folios.index',

                            'orgs.folios.create',

                            'orgs.payments.index',

                            'orgs.folios.histories',

                            'orgs.payments.histories',

                            'orgs.historyDTE'

                        ]);

                    @endphp

                    <li class="nav-item">

                        <a class="nav-link {{ $facturacionActive ? '' : 'collapsed' }}" data-bs-target="#facturacion-nav"
                            data-bs-toggle="collapse" href="#">

                            <i class="ri-bill-line" style="color: blue;"></i><span>FACTURACIÓN</span><i
                                class="bi bi-chevron-down ms-auto"></i>

                        </a>

                        <ul id="facturacion-nav" class="nav-content collapse {{ $facturacionActive ? 'show' : '' }}"
                            data-bs-parent="#sidebar-nav">

                            <li>

                                <a href="{{route('orgs.sections.index', ['id' => auth()->user()->org_id])}}"
                                    class="{{ in_array($active, ['orgs.sections.index', 'orgs.sections.create']) ? 'active' : '' }}">

                                    <i class="bi bi-circle"></i><span>Cálculo de tarifas</span>

                                </a>

                            </li>



                            <li>

                                <a href="{{route('orgs.historyDTE', ['id' => auth()->user()->org_id])}}"
                                    class="{{ $active == 'orgs.historyDTE' ? 'active' : '' }}">

                                    <i class="bi bi-circle"></i><span>Historial de DTE</span>

                                </a>

                            </li>



                            <li>

                                <a href="{{route('orgs.folios.index', ['id' => auth()->user()->org_id])}}"
                                    class="{{ $active == 'orgs.folios.histories' ? 'active' : '' }}">

                                    <i class="bi bi-circle"></i><span>Historial de folios</span>

                                </a>

                            </li>

                            <li>

                                <a href="{{route('orgs.payments.index', ['id' => auth()->user()->org_id])}}"
                                    class="{{ $active == 'orgs.payments.index' ? 'active' : '' }}">

                                    <i class="bi bi-circle"></i><span>Ingresar un pago</span>

                                </a>

                            </li>





                            <li>

                                <a href="{{route('orgs.payments.history', ['id' => auth()->user()->org_id])}}"
                                    class="{{ $active == 'orgs.payments.histories' ? 'active' : '' }}">

                                    <i class="bi bi-circle"></i><span>Historial de Pagos</span>

                                </a>

                            </li>



                        </ul>

                    </li>

                    <!-- Contable -->

                    @php

                        $contableActive = in_array($active, [



                        ]);

                    @endphp

                    <li class="nav-item">

                        <a class="nav-link {{ $contableActive ? '' : 'collapsed' }}" data-bs-target="#contable-nav"
                            data-bs-toggle="collapse" href="#">

                            <i class="ri-bill-line" style="color: blue;"></i><span>CONTABLE </span><i
                                class="bi bi-chevron-down ms-auto"></i>

                        </a>

                        <ul id="contable-nav" class="nav-content collapse {{ $contableActive ? 'show' : '' }}"
                            data-bs-parent="#sidebar-nav">

                            <li>

                                <a href="{{ route('orgs.libro.caja', ['id' => auth()->user()->org_id]) }}"
                                    class="{{ $active == 'orgs.libro.caja' ? 'active' : '' }}">

                                    <i class="bi bi-circle"></i><span>Gestión Contable Pro</span>

                                </a>

                            </li>

                            <li>
                                <a href="{{ route('cuentas_iniciales.show', ['id' => auth()->user()->org_id]) }}" 
                                   class="{{ $active == 'cuentas_iniciales.show' ? 'active' : '' }}">
                                    <i class="bi bi-journal-plus" style="color: #d32f2f;"></i>
                                    <span>Config. Cuentas Iniciales</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('registro_ingresos_egresos.show', ['id' => auth()->user()->org_id]) }}" 
                                   class="{{ $active == 'registro_ingresos_egresos.show' ? 'active' : '' }}">
                                    <i class="bi bi-arrow-left-right" style="color: #28a745;"></i>
                                    <span>Registro Ingresos/Egresos</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('giros_depositos.show', ['id' => auth()->user()->org_id]) }}" 
                                   class="{{ $active == 'giros_depositos.show' ? 'active' : '' }}">
                                    <i class="bi bi-bank" style="color: #007bff;"></i>
                                    <span>Giros y Depósitos</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('libro_caja_tabular.show', ['id' => auth()->user()->org_id]) }}" 
                                   class="{{ $active == 'libro_caja_tabular.show' ? 'active' : '' }}">
                                    <i class="bi bi-journal-bookmark" style="color: #6f42c1;"></i>
                                    <span>Libro de Caja Tabular</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('balance.show', ['id' => auth()->user()->org_id]) }}" 
                                   class="{{ $active == 'balance.show' ? 'active' : '' }}">
                                    <i class="bi bi-bar-chart" style="color: #fd7e14;"></i>
                                    <span>Balance</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('conciliacion_bancaria.show', ['id' => auth()->user()->org_id]) }}" 
                                   class="{{ $active == 'conciliacion_bancaria.show' ? 'active' : '' }}">
                                    <i class="bi bi-bank2" style="color: #20c997;"></i>
                                    <span>Conciliación Bancaria</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('informe_rubro.show', ['id' => auth()->user()->org_id]) }}" 
                                   class="{{ $active == 'informe_rubro.show' ? 'active' : '' }}">
                                    <i class="bi bi-pie-chart" style="color: #e83e8c;"></i>
                                    <span>Informe por Rubro</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('movimientos.show', ['id' => auth()->user()->org_id]) }}" 
                                   class="{{ $active == 'movimientos.show' ? 'active' : '' }}">
                                    <i class="bi bi-list-ul" style="color: #6f42c1;"></i>
                                    <span>Movimientos</span>
                                </a>
                            </li>

                        </ul>

                    </li>

                    <!-- Agua Potable -->

                    @php

                        $aguaActive = in_array($active, [

                            'orgs.aguapotable.macromedidor.index',

                            'orgs.aguapotable.cloracion.index',

                            'orgs.aguapotable.analisis.index'

                        ]);

                    @endphp

                    <li class="nav-item">

                        <a class="nav-link {{ $aguaActive ? '' : 'collapsed' }}" data-bs-target="#agua-nav"
                            data-bs-toggle="collapse" href="#">

                            <i class="ri-building-4-line" style="color: blue;"></i><span>AGUA POTABLE</span>

                            <i class="bi bi-chevron-down ms-auto"></i>

                        </a>

                        <ul id="agua-nav" class="nav-content collapse {{ $aguaActive ? 'show' : '' }}"
                            data-bs-parent="#sidebar-nav">

                            <li>

                                <a href="{{route('orgs.aguapotable.macromedidor.index', ['id' => auth()->user()->org_id])}}"
                                    class="{{ $active == 'orgs.aguapotable.macromedidor.index' ? 'active' : '' }}">

                                    <i class="bi bi-circle"></i><span>Macro Medidor</span>

                                </a>

                            </li>

                        </ul>

                    </li>

                    <!-- Inventario -->

                    @php

                        $inventarioActive = in_array($active, [

                            'orgs.inventories.index',

                            'orgs.inventories.create',

                            'orgs.categories.index',

                            'orgs.categories.create',

                            'orgs.categories.edit'

                        ]);

                    @endphp

                    <li class="nav-item">

                        <a class="nav-link {{ $inventarioActive ? '' : 'collapsed' }}" data-bs-target="#inventories-nav"
                            data-bs-toggle="collapse" href="#">

                            <i class="ri-archive-line" style="color: blue;"></i><span>INVENTARIO</span><i
                                class="bi bi-chevron-down ms-auto"></i>

                        </a>

                        <ul id="inventories-nav" class="nav-content collapse {{ $inventarioActive ? 'show' : '' }}"
                            data-bs-parent="#sidebar-nav">

                            <li>

                                <a href="{{route('orgs.inventories.create', ['id' => auth()->user()->org_id])}}"
                                    class="{{ $active == 'orgs.inventories.create' ? 'active' : '' }}">

                                    <i class="bi bi-circle"></i><span>Registro de inventario</span>

                                </a>

                            </li>

                            <li>

                                <a href="{{ route('orgs.categories.create', ['id' => auth()->user()->org_id]) }}"
                                    class="{{ $active == 'orgs.categories.create' ? 'active' : '' }}">

                                    <i class="bi bi-circle"></i><span>Crear Categoría</span>

                                </a>

                            </li>

                            <li>

                                <a href="{{route('orgs.categories.index', ['id' => auth()->user()->org_id])}}"
                                    class="{{ $active == 'orgs.categories.index' ? 'active' : '' }}">

                                    <i class="bi bi-circle"></i><span>Listado de Categorías</span>

                                </a>

                            </li>

                            <li>

                                <a href="{{route('orgs.inventories.index', ['id' => auth()->user()->org_id])}}"
                                    class="{{ $active == 'orgs.inventories.index' ? 'active' : '' }}">

                                    <i class="bi bi-circle"></i><span>Historial de Inventario</span>

                                </a>

                            </li>

                        </ul>

                    </li>







                @endif



                @if($avanzado)



                    <li class="nav-item">

                        <a class="nav-link {{ $active == 'orgs.readings.index' ? '' : 'collapsed' }}"
                            href="{{route('orgs.readings.index', parameters: ['id' => auth()->user()->org_id])}}">

                            <i class="bi bi-clipboard-plus" style="color: blue;"></i>

                            <span>Ingreso de Lecturas</span>

                        </a>



                    </li>


                    <li class="nav-item">

                        <a class="nav-link {{ $active == 'orgs.payments.index' ? '' : 'collapsed' }}"
                            href="{{route('orgs.payments.index', ['id' => auth()->user()->org_id])}}">

                            <i class="bi bi-credit-card" style="color: green;"></i>

                            <span>Ingresar un pago</span>

                        </a>

                    </li>



                    <li class="nav-item">

                        <a class="nav-link {{ $active == 'orgs.folios.create' ? '' : 'collapsed' }}"
                            href="{{route('orgs.folios.create', ['id' => auth()->user()->org_id])}}">

                            <i class="bi bi-receipt" style="color: black;"></i>

                            <span>Generar Boletas</span>

                        </a>

                    </li>



                    <li class="nav-heading">Módulos</li>

                    <!-- Control de Clientes -->

                    @php

                        $clientesActive = in_array($active, [

                            'orgs.members.index',

                            'orgs.members.create',

                            'orgs.readings.index',

                            'orgs.readings.histories',

                            'orgs.services.index',

                            'orgs.locations.index',

                            'orgs.locations.create'

                        ]);

                    @endphp

                    <li class="nav-item">

                        <a class="nav-link {{ $clientesActive ? '' : 'collapsed' }}" data-bs-target="#module-nav"
                            data-bs-toggle="collapse" href="#">

                            <i class="ri-folder-user-line" style="color: blue;"></i><span>CONTROL DE CLIENTES</span><i
                                class="bi bi-chevron-down ms-auto"></i>

                        </a>

                        <ul id="module-nav" class="nav-content collapse {{ $clientesActive ? 'show' : '' }}"
                            data-bs-parent="#sidebar-nav">

                            <li>

                                <a href="{{route('orgs.readings.index', ['id' => auth()->user()->org_id])}}"
                                    class="{{ $active == 'orgs.readings.index' ? 'active' : '' }}">

                                    <span>Ingreso de Lecturas</span>

                                </a>

                            </li>

                            <li>

                                <a href="{{route('orgs.members.index', ['id' => auth()->user()->org_id])}}"
                                    class="{{ $active == 'orgs.members.index' ? 'active' : '' }}">

                                    <i class="bi bi-circle"></i><span>Lista de clientes</span>

                                </a>

                            </li>

                            <li>

                                <a href="{{route('orgs.services.index', ['id' => auth()->user()->org_id])}}"
                                    class="{{ $active == 'orgs.services.index' ? 'active' : '' }}">

                                    <i class="bi bi-circle"></i><span>Lista de Servicios</span>

                                </a>

                            </li>

                            <li>

                                <a href="{{route('orgs.members.create', ['id' => auth()->user()->org_id])}}"
                                    class="{{ $active == 'orgs.members.create' ? 'active' : '' }}">

                                    <i class="bi bi-circle"></i><span>Agregar cliente</span>

                                </a>

                            </li>

                            <li>

                                <a href="{{route('orgs.folios.create', ['id' => auth()->user()->org_id])}}"
                                    class="{{ in_array($active, ['orgs.folios.index', 'orgs.folios.create']) ? 'active' : '' }}">

                                    <i class="bi bi-circle"></i><span>Generar boletas</span>

                                </a>

                            </li>

                            <li>

                                <a href="{{route('orgs.locations.create', ['id' => auth()->user()->org_id])}}"
                                    class="{{ $active == 'orgs.locations.create' ? 'active' : '' }}">

                                    <i class="bi bi-circle"></i><span>Agregar sector</span>

                                </a>

                            </li>

                            <li>

                                <a href="{{route('orgs.locations.index', ['id' => auth()->user()->org_id])}}"
                                    class="{{ $active == 'orgs.locations.index' ? 'active' : '' }}">

                                    <i class="bi bi-circle"></i><span>Lista de sectores</span>

                                </a>

                            </li>

                            <li>

                                <a href="javascript:void(0);" class="disabled text-muted" tabindex="-1" aria-disabled="true">

                                    <i class="bi bi-circle"></i><span>Panel de control rutas <i
                                            class="bi bi-lock fs-6"></i></span>

                                </a>

                            </li>

                        </ul>

                    </li>



                    <!-- Facturación -->

                    @php

                        $facturacionActive = in_array($active, [

                            'orgs.sections.index',

                            'orgs.sections.create',

                            'orgs.folios.index',

                            'orgs.folios.create',

                            'orgs.payments.index',

                            'orgs.folios.histories',

                            'orgs.payments.histories',

                            'orgs.historyDTE'

                        ]);

                    @endphp

                    <li class="nav-item">

                        <a class="nav-link {{ $facturacionActive ? '' : 'collapsed' }}" data-bs-target="#facturacion-nav"
                            data-bs-toggle="collapse" href="#">

                            <i class="ri-bill-line" style="color: blue;"></i><span>FACTURACIÓN</span><i
                                class="bi bi-chevron-down ms-auto"></i>

                        </a>

                        <ul id="facturacion-nav" class="nav-content collapse {{ $facturacionActive ? 'show' : '' }}"
                            data-bs-parent="#sidebar-nav">

                            <li>

                                <a href="{{route('orgs.sections.index', ['id' => auth()->user()->org_id])}}"
                                    class="{{ in_array($active, ['orgs.sections.index', 'orgs.sections.create']) ? 'active' : '' }}">

                                    <i class="bi bi-circle"></i><span>Cálculo de tarifas</span>

                                </a>

                            </li>



                            <li>

                                <a href="{{route('orgs.historyDTE', ['id' => auth()->user()->org_id])}}"
                                    class="{{ $active == 'orgs.historyDTE' ? 'active' : '' }}">

                                    <i class="bi bi-circle"></i><span>Historial de DTE</span>

                                </a>

                            </li>



                            <li>

                                <a href="{{route('orgs.folios.index', ['id' => auth()->user()->org_id])}}"
                                    class="{{ $active == 'orgs.folios.histories' ? 'active' : '' }}">

                                    <i class="bi bi-circle"></i><span>Historial de folios</span>

                                </a>

                            </li>

                            <li>

                                <a href="{{route('orgs.payments.index', ['id' => auth()->user()->org_id])}}"
                                    class="{{ $active == 'orgs.payments.index' ? 'active' : '' }}">

                                    <i class="bi bi-circle"></i><span>Ingresar un pago</span>

                                </a>

                            </li>





                            <li>

                                <a href="{{route('orgs.payments.history', ['id' => auth()->user()->org_id])}}"
                                    class="{{ $active == 'orgs.payments.histories' ? 'active' : '' }}">

                                    <i class="bi bi-circle"></i><span>Historial de Pagos</span>

                                </a>

                            </li>



                        </ul>

                    </li>

                    <!-- Contable -->

                    @php

                        $contableActive = in_array($active, [



                        ]);

                    @endphp

                    <li class="nav-item">

                        <a class="nav-link {{ $contableActive ? '' : 'collapsed' }}" data-bs-target="#contable-nav"
                            data-bs-toggle="collapse" href="#">

                            <i class="ri-bill-line" style="color: blue;"></i><span>CONTABLE </span><i
                                class="bi bi-chevron-down ms-auto"></i>

                        </a>

                        <ul id="contable-nav" class="nav-content collapse {{ $contableActive ? 'show' : '' }}"
                            data-bs-parent="#sidebar-nav">

                            <li>

                                <a href="{{ route('orgs.libro.caja', ['id' => auth()->user()->org_id]) }}"
                                    class="{{ $active == 'orgs.libro.caja' ? 'active' : '' }}">

                                    <i class="bi bi-circle"></i><span>Gestión Contable Pro</span>

                                </a>

                            </li>

                            <li>
                                <a href="{{ route('cuentas_iniciales.show', ['id' => auth()->user()->org_id]) }}" 
                                   class="{{ $active == 'cuentas_iniciales.show' ? 'active' : '' }}">
                                    <i class="bi bi-journal-plus" style="color: #d32f2f;"></i>
                                    <span>Config. Cuentas Iniciales</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('registro_ingresos_egresos.show', ['id' => auth()->user()->org_id]) }}" 
                                   class="{{ $active == 'registro_ingresos_egresos.show' ? 'active' : '' }}">
                                    <i class="bi bi-arrow-left-right" style="color: #28a745;"></i>
                                    <span>Registro Ingresos/Egresos</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('giros_depositos.show', ['id' => auth()->user()->org_id]) }}" 
                                   class="{{ $active == 'giros_depositos.show' ? 'active' : '' }}">
                                    <i class="bi bi-bank" style="color: #007bff;"></i>
                                    <span>Giros y Depósitos</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('libro_caja_tabular.show', ['id' => auth()->user()->org_id]) }}" 
                                   class="{{ $active == 'libro_caja_tabular.show' ? 'active' : '' }}">
                                    <i class="bi bi-journal-bookmark" style="color: #6f42c1;"></i>
                                    <span>Libro de Caja Tabular</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('balance.show', ['id' => auth()->user()->org_id]) }}" 
                                   class="{{ $active == 'balance.show' ? 'active' : '' }}">
                                    <i class="bi bi-bar-chart" style="color: #fd7e14;"></i>
                                    <span>Balance</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('conciliacion_bancaria.show', ['id' => auth()->user()->org_id]) }}" 
                                   class="{{ $active == 'conciliacion_bancaria.show' ? 'active' : '' }}">
                                    <i class="bi bi-bank2" style="color: #20c997;"></i>
                                    <span>Conciliación Bancaria</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('informe_rubro.show', ['id' => auth()->user()->org_id]) }}" 
                                   class="{{ $active == 'informe_rubro.show' ? 'active' : '' }}">
                                    <i class="bi bi-pie-chart" style="color: #e83e8c;"></i>
                                    <span>Informe por Rubro</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('movimientos.show', ['id' => auth()->user()->org_id]) }}" 
                                   class="{{ $active == 'movimientos.show' ? 'active' : '' }}">
                                    <i class="bi bi-list-ul" style="color: #6f42c1;"></i>
                                    <span>Movimientos</span>
                                </a>
                            </li>

                        </ul>

                    </li>

                    <!-- Agua Potable -->

                    @php

                        $aguaActive = in_array($active, [

                            'orgs.aguapotable.macromedidor.index',

                            'orgs.aguapotable.cloracion.index',

                            'orgs.aguapotable.analisis.index'

                        ]);

                    @endphp

                    <li class="nav-item">

                        <a class="nav-link {{ $aguaActive ? '' : 'collapsed' }}" data-bs-target="#agua-nav"
                            data-bs-toggle="collapse" href="#">

                            <i class="ri-building-4-line" style="color: blue;"></i><span>AGUA POTABLE</span>

                            <i class="bi bi-chevron-down ms-auto"></i>

                        </a>

                        <ul id="agua-nav" class="nav-content collapse {{ $aguaActive ? 'show' : '' }}"
                            data-bs-parent="#sidebar-nav">

                            <li>

                                <a href="{{route('orgs.aguapotable.macromedidor.index', ['id' => auth()->user()->org_id])}}"
                                    class="{{ $active == 'orgs.aguapotable.macromedidor.index' ? 'active' : '' }}">

                                    <i class="bi bi-circle"></i><span>Macro Medidor</span>

                                </a>

                            </li>

                        </ul>

                    </li>





                    <!-- Inventario -->

                    @php

                        $inventarioActive = in_array($active, [

                            'orgs.inventories.index',

                            'orgs.inventories.create',

                            'orgs.categories.index',

                            'orgs.categories.create',

                            'orgs.categories.edit'

                        ]);

                    @endphp

                    <li class="nav-item">

                        <a class="nav-link {{ $inventarioActive ? '' : 'collapsed' }}" data-bs-target="#inventories-nav"
                            data-bs-toggle="collapse" href="#">

                            <i class="ri-archive-line" style="color: blue;"></i><span>INVENTARIO</span><i
                                class="bi bi-chevron-down ms-auto"></i>

                        </a>

                        <ul id="inventories-nav" class="nav-content collapse {{ $inventarioActive ? 'show' : '' }}"
                            data-bs-parent="#sidebar-nav">

                            <li>

                                <a href="{{route('orgs.inventories.create', ['id' => auth()->user()->org_id])}}"
                                    class="{{ $active == 'orgs.inventories.create' ? 'active' : '' }}">

                                    <i class="bi bi-circle"></i><span>Registro de inventario</span>

                                </a>

                            </li>

                            <li>

                                <a href="{{ route('orgs.categories.create', ['id' => auth()->user()->org_id]) }}"
                                    class="{{ $active == 'orgs.categories.create' ? 'active' : '' }}">

                                    <i class="bi bi-circle"></i><span>Crear Categoría</span>

                                </a>

                            </li>

                            <li>

                                <a href="{{route('orgs.categories.index', ['id' => auth()->user()->org_id])}}"
                                    class="{{ $active == 'orgs.categories.index' ? 'active' : '' }}">

                                    <i class="bi bi-circle"></i><span>Listado de Categorías</span>

                                </a>

                            </li>

                            <li>

                                <a href="{{route('orgs.inventories.index', ['id' => auth()->user()->org_id])}}"
                                    class="{{ $active == 'orgs.inventories.index' ? 'active' : '' }}">

                                    <i class="bi bi-circle"></i><span>Historial de Inventario</span>

                                </a>

                            </li>

                        </ul>

                    </li>







                @endif



                {{-- Agrega más bloques según sean relevantes para el plan --}}

            @elseif($user->isCrc() || $perfilId == 3)
  @php

                    $libroCajaActive = $active === 'orgs.libro.caja';

                @endphp

                 <li  class="nav-item">
                    <a href="{{ route('orgs.libro.caja', ['id' => auth()->user()->org_id]) }}"
                            class="nav-link {{ $libroCajaActive ? 'active' : '' }}">
                        <i class="bi bi-circle"></i><span>Gestión Contable Pro</span>
                    </a>
                 </li>


            @elseif($user->isOperator() || $perfilId == 5)

                @php

                    $operatorActive = $active === 'orgs.operator.index';
                    $macroMedidorActive = $active === 'orgs.aguapotable.macromedidor.index';

                @endphp

                    {{-- App Operador --}}
                <li class="nav-item">
                    <a class="nav-link {{ $operatorActive ? 'active' : 'collapsed' }}"
                        href="{{ route('orgs.operator.index', ['id' => auth()->user()->org_id]) }}">
                        <i class="bi bi-journal-arrow-down" style="color: purple;"></i>
                        <span>App Operador</span>
                    </a>
                </li>

                {{-- Macro Medidor --}}
                <li class="nav-item">
                    <a class="nav-link {{ $macroMedidorActive ? 'active' : 'collapsed' }}"
                        href="{{ route('orgs.aguapotable.macromedidor.index', ['id' => auth()->user()->org_id]) }}">
                        <i class="bi bi-circle"></i>
                        <span>Macro Medidor</span>
                    </a>
                </li>

            @else

                {{-- Usuario común --}}

                <li class="nav-item">

                </li>

            @endif



        </ul>

    </aside>



    <!-- End Sidebar-->



    <main id="main" class="main">

        @if (session()->has('success'))

            <div class="alert alert-success alert-dismissible fade show"><i class="bi bi-check-circle me-1"></i>

                {!! session()->get('success') !!}</div>

        @endif

        @if (Session::has('message'))

            <div class="alert alert-info alert-dismissible fade show"><i class="bi bi-info-circle me-1"></i>

                {{ Session::get('message') }}</div>

        @endif

        @if (Session::has('warning'))

            <div class="alert alert-warning alert-dismissible fade show"><i class="bi bi-exclamation-octagon me-1"></i>

                {{ Session::get('warning') }}</div>

        @endif

        @if (Session::has('danger'))

            <div class="alert alert-danger alert-dismissible fade show"><i class="bi bi-exclamation-octagon me-1"></i>

                {{ Session::get('danger') }}</div>

        @endif

        @yield('content')

    </main><!-- End #main -->



    <!-- ======= Footer ======= -->

    <footer id="footer" class="footer d-print-none">

        <div class="copyright">

            &copy; Copyright <strong><span>HydroSite®</span></strong>. Todos los derechos reservados

        </div>

        <div class="credits">

            <!-- All the links in the footer should remain intact. -->

            <!-- You can delete the links only if you purchased the pro version. -->

            <!-- Licensing information: https://bootstrapmade.com/license/ -->

            <!-- Purchase the pro version with working PHP/AJAX contact form: https://bootstrapmade.com/nice-admin-bootstrap-admin-html-template/ -->

            Desarrollado por <strong>Sigma Technology SpA</strong>

        </div>

    </footer>

    <!-- End Footer -->



    <a href="#" class="back-to-top d-flex align-items-center justify-content-center d-print-none"><i
            class="bi bi-arrow-up-short"></i></a>



    <!-- Vendor JS Files -->

    <script src="{{asset('theme/common/js/jquery-3.7.1.min.js')}}"></script>

    <script src="{{asset('theme/common/js/sweetalert2.all.min.js')}}"></script>

    <script src="{{asset('theme/nice/assets/vendor/apexcharts/apexcharts.min.js')}}"></script>

    <script src="{{asset('theme/nice/assets/vendor/bootstrap/js/bootstrap.bundle.min.js')}}"></script>

    <script src="{{asset('theme/nice/assets/vendor/chart.js/chart.umd.js')}}"></script>

    <script src="{{asset('theme/nice/assets/vendor/echarts/echarts.min.js')}}"></script>

    <script src="{{asset('theme/nice/assets/vendor/quill/quill.js')}}"></script>

    <script src="{{asset('theme/nice/assets/vendor/simple-datatables/simple-datatables.js')}}"></script>

    <script src="{{asset('theme/nice/assets/vendor/tinymce/tinymce.min.js')}}"></script>

    <script src="{{asset('theme/nice/assets/vendor/php-email-form/validate.js')}}"></script>



    <!-- Template Main JS File -->

    <script src="{{asset('theme/nice/assets/js/main.js')}}"></script>

    <script src="{{asset('theme/common/js/jquery.Rut.js')}}"></script>


    @stack('scripts')
    @yield('js')

</body>



</html>









