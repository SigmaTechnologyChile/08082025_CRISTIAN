<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Bienvenidos - HydroSite</title>
    <meta name="description" content="Software APR-SSR">
    <meta name="keywords" content="Software APR-SSR">

    <!-- Favicons -->
    <link href="{{asset('theme/common/img/favicon.png')}}" rel="icon">
    <link href="{{asset('theme/common/img/apple-touch-icon.png')}}" rel="apple-touch-icon">
    <link rel="apple-touch-icon" sizes="180x180" href="{{asset('theme/common/img/apple-touch-icon.png')}}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{asset('theme/common/img/favicon-32x32.png')}}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{asset('theme/common/img/favicon-16x16.png')}}">
    <link rel="manifest" href="{{asset('theme/common/img/site.webmanifest')}}">
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com" rel="preconnect">
    <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Raleway:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="{{asset('theme/resi/assets/vendor/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{asset('theme/resi/assets/vendor/bootstrap-icons/bootstrap-icons.css')}}" rel="stylesheet">
    <link href="{{asset('theme/resi/assets/vendor/aos/aos.css')}}" rel="stylesheet">
    <link href="{{asset('theme/resi/assets/vendor/glightbox/css/glightbox.min.css')}}" rel="stylesheet">
    <link href="{{asset('theme/resi/assets/vendor/swiper/swiper-bundle.min.css')}}" rel="stylesheet">

    <!-- Font Awesome para ícono de WhatsApp -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Main CSS File -->
    <link href="{{asset('theme/resi/assets/css/main.css')}}" rel="stylesheet">
    <link href="{{asset('/public/theme/resi/assets/css/home.css')}}" rel="stylesheet">

    <!-- =======================================================
  * Template Name: Resi
  * Template URL: https://bootstrapmade.com/resi-free-bootstrap-html-template/
  * Updated: Aug 07 2024 with Bootstrap v5.3.3
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
</head>

<body class="index-page">

    @include('layouts.common.header', ['active' => 'welcome'])
    
    <!-- Botones visibles en móvil -->
    <div class="d-block d-lg-none fixed-top mobile-top-buttons" style="background: white; z-index: 1030; padding: 10px; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
        <div class="container">
            <div class="row g-0"> <!-- Agregado g-0 para eliminar espacios entre columnas -->
                <div class="col-6 text-center pe-1"> <!-- Añadido padding derecho -->
                    <a href="https://hydrosite.cl/public/pago-cuenta" class="btn btn-primary w-100">
                        Pagar Cuenta
                    </a>
                </div>
                <div class="col-6 text-center ps-1"> <!-- Añadido padding izquierdo -->
                    <!-- ENLACE ACTUALIZADO PARA MI CUENTA -->
                    <a href="https://hydrosite.cl/public/login" class="btn btn-outline-primary w-100">
                        Mi Cuenta
                    </a>
                </div>
            </div>
        </div>
    </div>

    <main class="main"> <!-- Padding superior eliminado -->
        <!-- Hero Section -->
        <section id="hero" class="hero section">
            <div class="container">
                <div class="row gy-4">
                    <div class="col-lg-6 order-lg-last order-first">
                        <div class="image-container">
                            
                            <img src="{{asset('/theme/resi/assets/img/logo_home.png')}}" class="img-fluid" alt="">
                        </div>
                    </div>

                    <div class="col-lg-6 order-lg-first order-last d-flex flex-column justify-content-center">
                        <div class="hero-content-wrapper text-center">
                            <h1>“Moderniza tu APR o SSR con el software más seguro y eficiente del país". </h1>

                            <div class="main-description">
                                <p>
                                    HydroSite es el Software de gestión administrativo y operacional más completo,
                                    estable, y seguro del país.
                                </p>
                                <p>
                                    Nuestro sistema ha sido desarrollado para modernizar la gestión administrativa y
                                    operacional de los SSR y Cooperativas simplificando procesos, mejorando la
                                    eficiencia y garantizando una operación alineada a los más altos estándares.
                                </p>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="row">
    <div class="col-lg-12 order-lg-first order-last d-flex flex-column justify-content-center">
        <div class="hero-content-wrapper">
            <div class="features-container">
                <div class="features-title mb-4 special-theme-color">
                    <i class="bi bi-stars"></i> Características principales
                </div>

                <ul class="container d-flex flex-wrap gap-3 p-0">
                    <li class="card shadow p-0 list-card-space shadow-custom-list row col-sm-12">
                        <p class="fs-6"><i class="bi bi-check2"></i> Control Total de Clientes</p>
                    </li>
                    <li class="card shadow p-0 list-card-space shadow-custom-list row col-sm-12">
                        <p class="fs-6"><i class="bi bi-check2"></i> Facturación Electrónica (DTE)</p>
                    </li>
                    <li class="card shadow p-0 list-card-space shadow-custom-list row col-sm-12">
                        <p class="fs-6"><i class="bi bi-check2"></i> App Operador / Lecturas en Terreno</p>
                    </li>
                    <li class="card shadow p-0 list-card-space shadow-custom-list row col-sm-12">
                        <p class="fs-6"><i class="bi bi-check2"></i> Pagos Online WebPay</p>
                    </li>
                    <li class="card shadow p-0 list-card-space shadow-custom-list row col-sm-12">
                        <p class="fs-6"><i class="bi bi-check2"></i> Monitoreo de Calidad de Agua</p>
                    </li>
                    <li class="card shadow p-0 list-card-space shadow-custom-list row col-sm-12">
                        <p class="fs-6"><i class="bi bi-check2"></i> Gestión Contable Avanzada</p>
                    </li>
                    <li class="card shadow p-0 list-card-space shadow-custom-list row col-sm-12">
                        <p class="fs-6"><i class="bi bi-check2"></i> Control de Inventario</p>
                    </li>
                    <li class="card shadow p-0 list-card-space shadow-custom-list row col-sm-12">
                        <p class="fs-6"><i class="bi bi-check2"></i> Dashboard de Control Total</p>
                    </li>
                    <li class="card shadow p-0 list-card-space shadow-custom-list row col-sm-12">
                        <p class="fs-6"><i class="bi bi-check2"></i> Notificaciones de Corte de Suministro</p>
                    </li>
                    <li class="card shadow p-0 list-card-space shadow-custom-list row col-sm-12">
                        <p class="fs-6"><i class="bi bi-check2"></i> Gestión de Inventario</p>
                    </li>
                    <li class="card shadow p-0 list-card-space shadow-custom-list row col-sm-12">
                        <p class="fs-6"><i class="bi bi-check2"></i> Gestión de Rutas Optimizada</p>
                    </li>
                    <li class="card shadow p-0 list-card-space shadow-custom-list row col-sm-12">
                        <p class="fs-6"><i class="bi bi-check2"></i> Pagos con Tarjeta / Transbank</p>
                    </li>
                </ul>
            </div>
 <div class="call-btn-container">
            <a href="tel:+56952468967" class="call-btn">
                <span class="phone-icon">
                    <i class="fas fa-phone-alt"></i>
                </span>
                ¡Llamanos ahora!
            </a>
        </div>
       
        </section>
 <!-- Features Section -->
        <section id="features" class="features section">

            <!-- Section Title -->
            <div class="container section-title" data-aos="fade-up">
                <h2>Módulos Disponibles</h2>
                <p>"Imagina lo fácil que sería tu trabajo con HydroSite… ¡Ahora hazlo realidad!"</p>
            </div><!-- End Section Title -->

            <div class="container">
                <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-5 g-4">
                    <div class="col" data-aos="fade-up" data-aos-delay="100">
                        <div class="features-item">
                            <i class="bi bi-people-fill" style="color: #ffbb2c;"></i>
                            <h3><a href="" class="stretched-link">Módulo Control de Clientes</a></h3>
                        </div>
                    </div><!-- End Feature Item -->

                    <div class="col" data-aos="fade-up" data-aos-delay="200">
                        <div class="features-item">
                            <i class="bi bi-calculator-fill" style="color: #5578ff;"></i>
                            <h3><a href="" class="stretched-link">Módulo Contable</a></h3>
                        </div>
                    </div><!-- End Feature Item -->

                    <div class="col" data-aos="fade-up" data-aos-delay="300">
                        <div class="features-item">
                            <i class="bi bi-receipt" style="color: #e80368;"></i>
                            <h3><a href="" class="stretched-link">Módulo de Facturación</a></h3>
                        </div>
                    </div><!-- End Feature Item -->

                    <div class="col" data-aos="fade-up" data-aos-delay="400">
                        <div class="features-item">
                            <i class="bi bi-box-seam" style="color: #e361ff;"></i>
                            <h3><a href="" class="stretched-link">Módulo de Inventario</a></h3>
                        </div>
                    </div><!-- End Feature Item -->

                    <div class="col" data-aos="fade-up" data-aos-delay="500">
                        <div class="features-item">
                            <i class="bi bi-droplet-half" style="color:  #5578ff;"></i>
                            <h3><a href="" class="stretched-link">Módulo de Agua Potable</a></h3>
                        </div>
                    </div><!-- End Feature Item -->
                </div>
            </div>

        </section><!-- /Features Section -->

 <!-- Features Section -->
        <section id="features" class="features section">

            <!-- Section Title -->
            <div class="container d-flex flex-column align-items-center">
    <!-- Section Title -->
    <div class="section-title text-center" data-aos="fade-up">
        <h2>POTENCIADORES ADICIONALES</h2>
        <p>"Registro eficiente de lecturas en terreno y control inteligente de rutas. Notificaciones instantáneas para cortes programados por sector."</p>
    </div><!-- End Section Title -->
    
    <div class="w-100">
        <div class="row justify-content-center row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-5 g-4">
                    <div class="col" data-aos="fade-up" data-aos-delay="100">
                        <div class="features-item">
                            <i class="bi bi-journal-arrow-down" style="color: purple;"></i>
                            <h3><a href="" class="stretched-link">App Operador</a></h3>
                        </div>
                    </div><!-- End Feature Item -->

                    <div class="col" data-aos="fade-up" data-aos-delay="200">
                        <div class="features-item">
                            <i class="bi bi-bell" style="color: red;"></i>
                            <h3><a href="" class="stretched-link">Notificaciones</a></h3>
                        </div>
                    </div><!-- End Feature Item -->

                </div>
            </div>

        </section><!-- /Features Section -->

        <!-- About Section -->
<section id="about" class="about section">

    <div class="container" data-aos="fade-up" data-aos-delay="100">
        <div class="row align-items-xl-center gy-5">

            <div class="col-xl-5 content">
                <h3>Acerca de Nosotros</h3>
                <h2>Desarrollo Tecnológico, Vanguardia y Seguridad</h2>
                <p>HydroSite es la plataforma de gestión administrativa más completa del mercado, desarrollada
                    por Sigma Technology Spa. Nuestra empresa cuenta con Ingenieros Informáticos y un equipo de
                    profesionales con amplios conocimientos en el área Comercial, Contable y Legal, lo que nos
                    permite brindar una solución 360 más actualizada, segura y confiable para gestionar todos
                    los requerimientos de cada organización, cumpliendo con los estándares de calidad y
                    requerimientos de los organismos gubernamentales.</p>
                <a href="#" class="read-more"><span>Leer más</span><i class="bi bi-arrow-right"></i></a>
            </div>

            <div class="col-xl-7">
                <div class="row gy-4">
                    <div class="col-md-6" data-aos="fade-up" data-aos-delay="400">
                        <div class="icon-box">
                            <i class="bi bi-shield-lock"></i>
                            <h3>Seguridad y Confianza</h3>
                            <p>
                                Todo nuestro sistema está completamente seguro y además
                                cuenta con una infraestructura adecuada para manejar grandes
                                magnitudes de datos.
                            </p>
                        </div>
                    </div> <!-- End Icon Box -->

                    <div class="col-md-6" data-aos="fade-up" data-aos-delay="500">
                        <div class="icon-box four">
                                    <i class="bi bi-graph-up-arrow"></i>
                            <h3>Aumento de la productividad</h3>
                            <p>Cada uno de nuestros Módulos están pensados para optimizar los tiempos de
                                trabajo, tanto en la oficina como en terreno. Ahora con un solo click puede obtener
                                toda la información necesaria disponible inmediatamente.</p>
                        </div>
                    </div> <!-- End Icon Box -->
                </div>
            </div>
        </div>
    </div>

</section><!-- /About Section -->

           


        <!-- Services Section -->
        <section id="services" class="services section">

            <!-- Section Title -->
            <div class="container section-title" data-aos="fade-up">
                <h2>Servicios Complementarios</h2>
                <p>Soluciones profesionales para una gestión completamente eficiente</p>
            </div><!-- End Section Title -->

            <div class="container py-5">
                <div class="row gy-5">

                    <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="100">
                        <div class="service-item item-cyan position-relative p-4">
                            <div class="icon mb-3">
                                <svg width="100" height="100" viewBox="0 0 600 600" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke="none" stroke-width="0" fill="#f5f5f5"
                                        d="M300,521.0016835830174C376.1290562159157,517.8887921683347,466.0731472004068,529.7835943286574,510.70327084640275,468.03025145048787C554.3714126377745,407.6079735673963,508.03601936045806,328.9844924480964,491.2728898941984,256.3432110539036C474.5976632858925,184.082847569629,479.9380746630129,96.60480741107993,416.23090153303,58.64404602377083C348.86323505073057,18.502131276798302,261.93793281208167,40.57373210992963,193.5410806939664,78.93577620505333C130.42746243093433,114.334589627462,98.30271207620316,179.96522072025542,76.75703585869454,249.04625023123273C51.97151888228291,328.5150500222984,13.704378332031375,421.85034740162234,66.52175969318436,486.19268352777647C119.04800174914682,550.1803526380478,217.28368757567262,524.383925680826,300,521.0016835830174">
                                    </path>
                                </svg>
                                <i class="bi bi-calculator"></i>
                            </div>
                            <a href="service-details.html" class="stretched-link">
                                <h3 class="mb-3">Contabilidad Hydrosite</h3>
                            </a>
                            <p class="mb-4">Potencia tu gestión con nuestro servicio de contabilidad especializada para
                                APR y SSR. Un servicio profesional que se integra de forma directa con HydroSite,
                                facilitando la revisión, análisis y presentación ordenada de tus registros contables,
                                tributarios y financieros.</p>
                            <h6 class="stretched-link" style="font-weight: bold; margin-top:20px;">Si, somos más que un
                                Software, somos una solución.</h6>
                        </div>
                    </div><!-- End Service Item -->

                    <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="200">
                        <div class="service-item item-orange position-relative p-4">
                            <div class="icon mb-3">
                                <svg width="100" height="100" viewBox="0 0 600 600" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke="none" stroke-width="0" fill="#f5f5f5"
                                        d="M300,582.0697525312426C382.5290701553225,586.8405444964366,449.9789794690241,525.3245884688669,502.5850820975895,461.55621195738473C556.606425686781,396.0723002908107,615.8543463187945,314.28637112970534,586.6730223649479,234.56875336149918C558.9533121215079,158.8439757836574,454.9685369536778,164.00468322053177,381.49747125262974,130.76875717737553C312.15926192815925,99.40240125094834,248.97055460311594,18.661163978235184,179.8680185752513,50.54337015887873C110.5421016452524,82.52863877960104,119.82277516462835,180.83849132639028,109.12597500060166,256.43424936330496C100.08760227029461,320.3096726198365,92.17705696193138,384.0621239912766,124.79988738764834,439.7174275375508C164.83382741302287,508.01625554203684,220.96474134820875,577.5009287672846,300,582.0697525312426">
                                    </path>
                                </svg>
                                <i class="bi bi-globe"></i>
                            </div>
                            <a href="service-details.html" class="stretched-link">
                                <h3 class="mb-3">Diseño de Pagina Web</h3>
                            </a>
                            <p class="mb-4">Haz visible tu organización con una página web moderna, clara y fácil de
                                administrar. Creamos sitios personalizados que permiten informar a tu comunidad,
                                publicar cortes, facilitar pagos y fortalecer la confianza con los usuarios.</p>
                            <h6 class="stretched-link" style="font-weight: bold; margin-top:20px;">Conecta tu gestión
                                con tu comunidad, también en línea.</h6>
                        </div>
                    </div><!-- End Service Item -->

                    <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="300">
                        <div class="service-item item-teal position-relative p-4">
                            <div class="icon mb-3">
                                <svg width="100" height="100" viewBox="0 0 600 600" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke="none" stroke-width="0" fill="#f5f5f5"
                                        d="M300,541.5067337569781C382.14930387511276,545.0595476570109,479.8736841581634,548.3450877840088,526.4010558755058,480.5488172755941C571.5218469581645,414.80211281144784,517.5187510058486,332.0715597781072,496.52539010469104,255.14436215662573C477.37192572678356,184.95920475031193,473.57363656557914,105.61284051026155,413.0603344069578,65.22779650032875C343.27470386102294,18.654635553484475,251.2091493199835,5.337323636656869,175.0934190732945,40.62881213300186C97.87086631185822,76.43348514350839,51.98124368387456,156.15599469081315,36.44837278890362,239.84606092416172C21.716077023791087,319.22268207091537,43.775223500013084,401.1760424656574,96.891909868211,461.97329694683043C147.22146801428983,519.5804099606455,223.5754009179313,538.201503339737,300,541.5067337569781">
                                    </path>
                                </svg>
                                <i class="bi bi-file-earmark-text"></i>
                            </div>
                            <a href="service-details.html" class="stretched-link">
                                <h3 class="mb-3">Inscripción en el Registro de Derechos de Agua</h3>
                            </a>
                            <p class="mb-4">Te acompañamos en el proceso legal y administrativo para regularizar tus
                                derechos de aprovechamiento de agua ante la DGA. Nos encargamos de la documentación,
                                trámites y seguimiento, asegurando que tu APR o cooperativa cumpla con la normativa
                                vigente.</p>
                            <h6 class="stretched-link" style="font-weight: bold; margin-top:20px;">Formaliza tu
                                organización, asegura tu gestión.</h6>
                        </div>
                    </div><!-- End Service Item -->

                    <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="400">
                        <div class="service-item item-red position-relative p-4">
                            <div class="icon mb-3">
                                <svg width="100" height="100" viewBox="0 0 600 600" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke="none" stroke-width="0" fill="#f5f5f5"
                                        d="M300,503.46388370962813C374.79870501325706,506.71871716319447,464.8034551963731,527.1746412648533,510.4981551193396,467.86667711651364C555.9287308511215,408.9015244558933,512.6030010748507,327.5744911775523,490.211057578863,256.5855673507754C471.097692560561,195.9906835881958,447.69079081568157,138.11976852964426,395.19560036434837,102.3242989838813C329.3053358748298,57.3949838291264,248.02791733380457,8.279543830951368,175.87071277845988,42.242879143198664C103.41431057327972,76.34704239035025,93.79494320519305,170.9812938413882,81.28167332365135,250.07896920659033C70.17666984294237,320.27484674793965,64.84698225790005,396.69656628748305,111.28512138212992,450.4950937839243C156.20124167950087,502.5303643271138,231.32542653798444,500.4755392045468,300,503.46388370962813">
                                    </path>
                                </svg>
                                <i class="bi bi-graph-up-arrow"></i>
                            </div>
                            <a href="service-details.html" class="stretched-link">
                                <h3 class="mb-3">Asesoria Financiera</h3>
                            </a>
                            <p class="mb-4">Expande tu organización como todo un profesional. Obten nuestra Asesoría
                                Financiera y podrás mejorar todos los indicadores en muy poco tiempo. Te acompañamos en
                                cada proceso de escalamiento hacia una rentabilidad acorde a tus objetivos.</p>
                            <h6 class="stretched-link" style="font-weight: bold; margin-top:20px;">Ahora tus
                                conocimientos se expanden y potencian.</h6>
                        </div>
                    </div><!-- End Service Item -->
 <div class="call-btn-container">
            <a href="tel:+56952468967" class="call-btn">
                <span class="phone-icon">
                    <i class="fas fa-phone-alt"></i>
                </span>
                ¡ Solicita una cotización Aquí !
            </a>
        </div>
       
        </section>
                </div>
            </div>

        </section><!-- /Services Section -->

        <!-- Features Section -->
        <section id="features" class="features section">

           
                </div>
            </div>

        </section><!-- /Features Section -->

    </main>

    @include('layouts.common.footer')

    <!-- Scroll Top -->
    <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i
            class="bi bi-arrow-up-short"></i></a>

    <!-- Preloader -->
    <div id="preloader"></div>

    <!-- Botón de WhatsApp flotante -->
    <a href="https://wa.me/+56933790083" class="whatsapp-button" target="_blank">
        <i class="fab fa-whatsapp"></i>
    </a>
    
    <!-- Mensaje flotante para WhatsApp -->
    <div class="whatsapp-message">
        ¡Contáctanos por WhatsApp!
    </div>

    <!-- Vendor JS Files -->
    <script src="{{asset('theme/resi/assets/vendor/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
    <script src="{{asset('theme/resi/assets/vendor/php-email-form/validate.js')}}"></script>
    <script src="{{asset('theme/resi/assets/vendor/aos/aos.js')}}"></script>
    <script src="{{asset('theme/resi/assets/vendor/glightbox/js/glightbox.min.js')}}"></script>
    <script src="{{asset('theme/resi/assets/vendor/purecounter/purecounter_vanilla.js')}}"></script>
    <script src="{{asset('theme/resi/assets/vendor/imagesloaded/imagesloaded.pkgd.min.js')}}"></script>
    <script src="{{asset('theme/resi/assets/vendor/isotope-layout/isotope.pkgd.min.js')}}"></script>
    <script src="{{asset('theme/resi/assets/vendor/swiper/swiper-bundle.min.js')}}"></script>

    <!-- Main JS File -->
    <script src="{{asset('theme/resi/assets/js/main.js')}}"></script>

    <script>
        // Función para simular un clic en el botón de WhatsApp
        document.querySelector('.whatsapp-button').addEventListener('click', function(e) {
            e.preventDefault();
            window.open(this.href, '_blank');
        });
    </script>

</body>

</html>

<style>

/* Animación 1: Flotación suave */
        .float-animation .features-item:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(231, 76, 60, 0.2);
        }
/* Optimizar textos para móvil */
@media (max-width: 768px) {
  .testimonio p {
    font-size: 0.9rem;
    line-height: 1.4;
    text-align: left; /* Mejor legibilidad */
  }
  
  /* Añadir efecto "leer más" en textos largos */
  .card-text {
    overflow: hidden;
    display: -webkit-box;
    -webkit-line-clamp: 4; /* Límite de líneas */
    -webkit-box-orient: vertical;
  }
}
.slogan-sitio {
  text-align: center; /* Móvil: mejor legibilidad */
  font-size: 2rem; /* Tamaño adaptable (ajústalo según necesidad) */
  max-width: 95%; /* Evita que el texto llegue a los bordes */
  margin: 3 auto; /* Centra el bloque (opcional) */
}

@media (min-width: 768px) {
  .slogan-sitio {
    text-align: justify; /* PC: texto justificado */
    max-width: 200%; /* Controla el ancho en desktop */
  }
}

.slogan-sitio {
  text-align: left; /* Móvil: alineación izquierda (óptima para lectura) */
  padding: 0 15px; /* Opcional: evita que el texto toque los bordes */
}

@media (min-width: 768px) { /* Tablet/PC */
  .slogan-sitio {
    text-align: justify; /* Solo se justifica en pantallas grandes */
    padding: 0; /* Si no necesitas padding en desktop */
  }
}

 .call-btn-container {
        text-align: center;  /* Opción 1: para centrar contenido inline o inline-block */
        /* O usando flex: */
        /* display: flex;
           justify-content: center; */
    }

document.addEventListener('DOMContentLoaded', function() {
            const callBtn = document.querySelector('.call-btn');
            
            // Crear efecto ripple al hacer clic
            callBtn.addEventListener('click', function(e) {
                // Crear elemento ripple
                const ripple = document.createElement('span');
                ripple.classList.add('ripple');
                
                // Posicionar ripple
                const rect = callBtn.getBoundingClientRect();
                const size = Math.max(rect.width, rect.height);
                ripple.style.width = size + 'px';
                ripple.style.height = size + 'px';
                ripple.style.left = e.clientX - rect.left - size/2 + 'px';
                ripple.style.top = e.clientY - rect.top - size/2 + 'px';
                
                // Agregar ripple al botón
                callBtn.appendChild(ripple);
                
                // Eliminar ripple después de la animación
                setTimeout(() => {
                    ripple.remove();
                }, 1500);
            });
            
            // Animación de botón al cargar
            setTimeout(() => {
                callBtn.style.opacity = '0';
                callBtn.style.transform = 'translateY(20px)';
                
                setTimeout(() => {
                    callBtn.style.transition = 'all 0.8s ease';
                    callBtn.style.opacity = '1';
                    callBtn.style.transform = 'translateY(0)';
                }, 100);
            }, 500);
        });

/* Botón mejorado */
        .call-btn-container {
            position: relative;
            display: inline-block;
            margin: 30px 0;
        }
        
        .call-btn {
            position: relative;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(145deg, #0d6efd, #0b5ed7);
            color: white;
            padding: 16px 40px;
            border-radius: 90px;
            text-decoration: none;
            font-weight: 600;
            font-size: 1.3rem;
            transition: all 0.3s ease;
            box-shadow: 0 8px 20px rgba(13, 110, 253, 0.4);
            overflow: hidden;
            z-index: 2;
            border: none;
            cursor: pointer;
            animation: pulse 2s infinite;
        }
        
        .call-btn:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 25px rgba(13, 110, 253, 0.6);
            background: linear-gradient(145deg, #0b5ed7, #0a58ca);
        }
        
        .call-btn:active {
            transform: translateY(2px);
            box-shadow: 0 20px 10px rgba(13, 110, 253, 0.4);
        }
        
        .call-btn i {
            margin-left: 12px;
            font-size: 1.4rem;
            transition: transform 0.3s ease;
        }
        
        .call-btn:hover i {
            transform: scale(1.0);
        }
        
        .call-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(45deg, #20c997, #0dcaf0, #0d6efd, #6610f2);
            background-size: 400% 400%;
            z-index: -1;
            border-radius: 5px;
            opacity: 0;
            transition: opacity 0.9s ease;
        }
        
        .call-btn:hover::before {
            opacity: 1;
            animation: gradient-shift 3s ease infinite;
        }
        
        .call-btn::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            transform: translate(-50%, -50%);
            transition: width 0.6s ease, height 0.6s ease;
            z-index: 1;
        }
        
        .call-btn:hover::after {
            width: 300px;
            height: 300px;
        }
        
        .ripple {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.3);
            transform: scale(0);
            animation: ripple-animation 1.5s linear infinite;
            z-index: 1;
        }
        
        .phone-icon {
            position: relative;
            display: inline-block;
            margin-right: 15px;
        }
        
        /* Animaciones */
        @keyframes pulse {
            0% {
                box-shadow: 0 0 0 0 rgba(13, 110, 253, 0.7);
            }
            70% {
                box-shadow: 0 0 0 15px rgba(13, 110, 253, 0);
            }
            100% {
                box-shadow: 0 0 0 0 rgba(13, 110, 253, 0);
            }
        }
        
        @keyframes gradient-shift {
            0% {
                background-position: 0% 50%;
            }
            50% {
                background-position: 100% 50%;
            }
            100% {
                background-position: 0% 50%;
            }
        }
        
        @keyframes ripple-animation {
            to {
                transform: scale(4);
                opacity: 0;
            }
        }
        
        .call-btn:hover .phone-icon {
            animation: ring 0.5s ease infinite;
        }
        
        @keyframes ring {
            0% { transform: rotate(-15deg); }
            50% { transform: rotate(15deg); }
            100% { transform: rotate(-15deg); }
        }
        
        .features-container {
            margin-top: 50px;
            background: #f8f9fa;
            border-radius: 15px;
            padding: 25px;
            text-align: left;
        }
        
        .features-title {
            font-weight: 700;
            font-size: 1.4rem;
            margin-bottom: 20px;
            color: #0d6efd;
        }
        
        .feature-item {
            display: flex;
            align-items: center;
            padding: 10px 15px;
            margin-bottom: 15px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }
        
        .feature-item:hover {
            transform: translateX(10px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        
        .feature-icon {
            background: #e9f7fe;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            color: #0d6efd;
            font-size: 1.2rem;
        }
        
        .feature-text {
            font-size: 1.1rem;
            color: #495057;
        }
        
        .phone-number {
            font-size: 1.5rem;
            font-weight: 700;
            margin-top: 20px;
            color: #0d6efd;
            background: rgba(13, 110, 253, 0.1);
            padding: 10px 20px;
            border-radius: 10px;
            display: inline-block;
        }
        
        @media (max-width: 768px) {
            .call-btn {
                padding: 14px 30px;
                font-size: 1.2rem;
            }
            
            .demo-title {
                font-size: 1.8rem;
            }
            
            .phone-number {
                font-size: 1.3rem;
            }
        }

    .about .icon-boxes {
        display: flex;
        flex-wrap: wrap;
        gap: 0;
        /* evita saltos entre columnas */
    }

    .about .icon-boxes>.col-md-6 {
        display: flex;
        flex-direction: column;
    }

    .about .icon-box {
        background-color: var(--surface-color);
        padding: 50px 40px;
        box-shadow: 0px 10px 50px rgba(0, 0, 0, 0.1);
        border-radius: 10px;
        transition: all 0.3s ease-out 0s;
        display: flex;
        flex-direction: column;
        height: 100%;
    }

    .about .icon-boxes>.col-md-6 {
        padding: 10px;
    }

    .about .icon-boxes {
        margin: -10px;
    }

body.index-page {
    margin: 0;
    padding: 0;
}

   .hero-content-wrapper h1 {
    /* ... otras propiedades ... */
    background: linear-gradient(90deg,
            #7d80c7,  /* azul claro */
            #1d2059,  /* azul oscuro */
            #6268f0,  /* celeste */
            #060cc7); /* azul */
        background-size: 1000% auto;
        -webkit-background-clip: text;
        background-clip: text;
        -webkit-text-fill-color: transparent;

        animation: waterGradient 8s ease infinite alternate;
    }

    .hero-content-wrapper h1::after {
        content: '';
        position: absolute;
        bottom: -8px;
        left: 0;
        width: 100%;
        height: 3px;
        background: linear-gradient(90deg,
                rgba(166, 225, 250, 0.4),
                rgba(0, 180, 216, 0.8),
                rgba(166, 225, 250, 0.4));
        border-radius: 3px;
        transform-origin: center;
        animation: waterWave 5s ease-in-out infinite;
    }

    .hero-content-wrapper h1::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 50%;
        height: 100%;
        background: linear-gradient(90deg,
                transparent,
                rgba(255, 255, 255, 0.3),
                transparent);
        animation: waterShine 6s ease-in-out infinite;
        pointer-events: none;
    }

    @keyframes waterGradient {
        0% {
            background-position: 0% 50%;
        }

        50% {
            background-position: 100% 50%;
        }

        100% {
            background-position: 0% 50%;
        }
    }

    @keyframes waterWave {
        0% {
            transform: scaleX(0.95) translateX(-5px);
            opacity: 0.6;
        }

        50% {
            transform: scaleX(1) translateX(5px);
            opacity: 0.9;
        }

        100% {
            transform: scaleX(0.95) translateX(-5px);
            opacity: 0.6;
        }
    }

    @keyframes waterShine {
        0% {
            left: -100%;
        }

        30% {
            left: 100%;
        }

        100% {
            left: 100%;
        }
    }

    @media (max-width: 768px) {
        .hero-content-wrapper h1 {
            font-size: 1.6rem;
        }

    }


    .icon-box i {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 2.5rem;
        color: #0d6efd;
        margin-bottom: 1rem;
        position: relative;
        transition: all 0.3s ease;
        animation: iconPulse 1.8s infinite ease-in-out;
    }

    .icon-box:hover i {
        color: #0a58ca;
        transform: translateY(-5px);
    }

    @keyframes iconPulse {
        0% {
            transform: scale(1);
            color: white;
        }

        50% {
            transform: scale(1.1);
            color: white;
        }

        100% {
            transform: scale(1);
            color: white;
        }
    }


    .icon-box.one i {
        color: #4e73df;
        background-color: #f8f9fc;
    }

    .icon-box.two i {
        color: #1cc88a;
        background-color: #f0f7f4;
    }

    .icon-box.three i {
        color: #f6c23e;
        background-color: #fdf7e8;
    }

    .icon-box.four i {
        color: #e74a3b;
        background-color: #fdf3f2;
    }

    .icon-box.one:hover i {
        color: #f8f9fc;
        background-color: #4e73df;
    }

    .icon-box.two:hover i {
        color: #f0f7f4;
        background-color: #1cc88a;
    }

    .icon-box.three:hover i {
        color: #fdf7e8;
        background-color: #f6c23e;
    }

    .icon-box.four:hover i {
        color: #fdf3f2;
        background-color: #e74a3b;
    }

    .icon-box i {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 2.5rem;
        margin-bottom: 1rem;
        position: relative;
        transition: all 0.3s ease;
        padding: 15px;
        border-radius: 50%;
        animation: iconPulse 1.8s infinite ease-in-out;
    }

    .icon-box:hover i {
        transform: translateY(-5px) scale(1.1);
    }

    @keyframes iconPulse {
        0% {
            transform: scale(1);
        }

        50% {
            transform: scale(1.1);
        }

        100% {
            transform: scale(1);
        }
    }

    .icon-box {
        position: relative;
        overflow: hidden;
        padding: 1.5rem;
        border-radius: 10px;
        transition: all 0.3s ease;
        background-color: rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.15);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }

    .icon-box::after {
        content: '';
        position: absolute;
        top: -50%;
        left: -100%;
        width: 90%;
        height: 200%;
        background: linear-gradient(to right,
                rgba(255, 255, 255, 0) 0%,
                rgba(255, 255, 255, 0.5) 50%,
                rgba(255, 255, 255, 0) 100%);
        transform: rotate(25deg);
        animation: shimmer 1.2s infinite linear;
        z-index: 1;
        pointer-events: none;
    }

    @keyframes shimmer {
        0% {
            left: -100%;
            opacity: 0.8;
            /* Visible desde el inicio */
        }

        50% {
            opacity: 1;
            /* Pico de opacidad */
        }

        100% {
            left: 200%;
            opacity: 0.8;
        }
    }

    /* Efecto al hacer hover */
    .icon-box:hover::after {
        background: linear-gradient(to right,
                rgba(255, 255, 255, 0) 0%,
                rgba(255, 255, 255, 0.7) 50%,
                rgba(255, 255, 255, 0) 100%);
        animation-duration: 1s;
    }

    .icon-box:hover {
        transform: translateY(-10px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        background-color: rgba(0, 0, 0, 0.15);
    }

    .icon-box i::after {
        content: '';
        position: absolute;
        width: 100%;
        height: 100%;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(255, 255, 255, 0.2) 0%, rgba(255, 255, 255, 0) 70%);
        opacity: 0;
        z-index: -1;
        transition: opacity 0.3s ease;
        animation: glowPulse 3s infinite ease-in-out;
    }

    @keyframes glowPulse {
        0% {
            opacity: 0;
            transform: scale(0.8);
        }

        50% {
            opacity: 0.7;
            transform: scale(1.2);
        }

        100% {
            opacity: 0;
            transform: scale(0.8);
        }
    }

    .icon-boxes .icon-box:nth-child(1) i {
        animation-delay: 0s;
    }

    .icon-boxes .icon-box:nth-child(1)::after {
        animation-delay: 0.5s;
    }

    .icon-boxes .icon-box:nth-child(2) i {
        animation-delay: 0.5s;
    }

    .icon-boxes .icon-box:nth-child(2)::after {
        animation-delay: 1s;
    }

    .icon-boxes .icon-box:nth-child(3) i {
        animation-delay: 1s;
    }

    .icon-boxes .icon-box:nth-child(3)::after {
        animation-delay: 1.5s;
    }

    .icon-boxes .icon-box:nth-child(4) i {
        animation-delay: 1.5s;
    }

    .icon-boxes .icon-box:nth-child(4)::after {
        animation-delay: 2s;
    }

    @keyframes floatingBox {
        0% {
            transform: translateY(0);
        }

        50% {
            transform: translateY(-15px);
        }

        100% {
            transform: translateY(0);
        }
    }

    .icon-box {
        animation: floatingBox 4s infinite ease-in-out;
    }

    .icon-boxes .icon-box:nth-child(1) {
        animation-delay: 0s;
    }

    .icon-boxes .icon-box:nth-child(2) {
        animation-delay: 1s;
    }

    .icon-boxes .icon-box:nth-child(3) {
        animation-delay: 2s;
    }

    .icon-boxes .icon-box:nth-child(4) {
        animation-delay: 3s;
    }


    .icon-box h3 {
        margin-bottom: 15px;
        font-size: 1.5rem;
        position: relative;
        z-index: 2;
    }


    .icon-box p,
    .icon-box ul {
        transition: color 0.3s ease;
        position: relative;
        z-index: 2;
    }


    .icon-box ul {
        padding-left: 20px;
    }

    .icon-box li {
        margin-bottom: 8px;
        position: relative;
    }

    .list-card-space {
        margin-right: 0.2rem;
        width: 48%;
    }

    .shadow-custom-list {
        z-index: 1;
        transition: border-left 0.3s ease;
        border: 0px;
    }

    .shadow-custom-list:hover {
        border-left: 4px solid var(--swiper-theme-color) !important;
        transform: scale(1.015) !important;
        transition: all 0.17s ease-in-out !important;
    }

    .slogan-sitio {
        font-weight: bold;
        margin: 3.7rem 0rem;
        width: 100dvw;
    }

    .special-theme-color {
        color: var(--swiper-theme-color);
    }

    .link-hover-fix {
        position: relative;
        z-index: 1;
    }

    .link-hover-fix a:hover {
        color: color-mix(in srgb, var(--swiper-theme-color), transparent 20%);
        transition: all 0.17s ease-in-out !important;
    }

    @media (min-width: 1200px) {
        .slogan-sitio {
            font-weight: bold;
            margin: 9.2rem 0rem 3.7rem 0rem;
            width: 100dvw;
        }
    }

    @media (max-width: 640px) {
        .list-card-space {
            margin-left: 0.2rem;
            width: 100%;
        }
    }
    /* Agrega esto al final de tu bloque de estilos */
#hero.hero.section {
    padding-top: 30px !important; /* Ajusta este valor según necesites */
}

@media (max-width: 991px) {
    #hero.hero.section {
        padding-top: 60px !important; /* Más espacio para móviles por los botones flotantes */
    }
}

/* Estilos para el botón de WhatsApp */
.whatsapp-button {
    position: fixed;
    bottom: 30px;
    left: 30px;
    background-color: #25D366;
    color: white;
    border-radius: 50%;
    width: 70px;
    height: 70px;
    display: flex;
    justify-content: center;
    align-items: center;
    font-size: 40px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
    z-index: 1000;
    animation: pulse-whatsapp 2s infinite;
    text-decoration: none;
    transition: all 0.3s ease;
}

.whatsapp-button:hover {
    transform: scale(1.1);
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.4);
}

.whatsapp-message {
    position: fixed;
    bottom: 115px;
    left: 30px;
    background-color: white;
    color: #333;
    padding: 15px;
    border-radius: 10px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
    width: 250px;
    text-align: center;
    z-index: 999;
    opacity: 0;
    transform: translateY(20px);
    transition: all 0.3s ease;
}

.whatsapp-button:hover + .whatsapp-message {
    opacity: 1;
    transform: translateY(0);
}

@media (max-width: 768px) {
    .whatsapp-button {
        width: 60px;
        height: 60px;
        font-size: 35px;
        bottom: 20px;
        right: 20px;
    }
    
    .whatsapp-message {
        bottom: 95px;
        left: 20px;
        width: 200px;
    }
}

@media (max-width: 480px) {
    .whatsapp-button {
        width: 55px;
        height: 55px;
        font-size: 30px;
        bottom: 15px;
        right: 15px;
    }
    
    .whatsapp-message {
        bottom: 85px;
        left: 15px;
        width: 180px;
        font-size: 0.9rem;
    }
}

/* Animación de pulso para WhatsApp */
@keyframes pulse-whatsapp {
    0% {
        box-shadow: 0 0 0 0 rgba(37, 211, 102, 0.7);
    }
    70% {
        box-shadow: 0 0 0 15px rgba(37, 211, 102, 0);
    }
    100% {
        box-shadow: 0 0 0 0 rgba(37, 211, 102, 0);
    }
}

</style>
