@extends('layouts.nice', ['active'=>'orgs.notifications.index', 'title'=>'Notificaciones'])

@section('content')
    <div class="pagetitle">
        <h1>{{$org->name}}</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item"><a href="{{route('orgs.index')}}">Organizaciones</a></li>
                <li class="breadcrumb-item"><a href="{{route('orgs.dashboard',$org->id)}}">{{$org->name}}</a></li>
                <li class="breadcrumb-item active">Notificaciones</li>
            </ol>
        </nav>
    </div>

    <section class="section dashboard">
        <!-- Estadísticas rápidas -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card bg-primary text-white">
                    <div class="card-body py-2">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title mb-0 text-white">Total</h6>
                                <h3 class="mb-0">124</h3>
                            </div>
                            <i class="bi bi-bell fs-1 opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-success text-white">
                    <div class="card-body py-2">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title mb-0 text-white">Enviadas</h6>
                                <h3 class="mb-0">98</h3>
                            </div>
                            <i class="bi bi-check-circle fs-1 opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-warning text-white">
                    <div class="card-body py-2">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title mb-0 text-white">Pendientes</h6>
                                <h3 class="mb-0">18</h3>
                            </div>
                            <i class="bi bi-hourglass-split fs-1 opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-danger text-white">
                    <div class="card-body py-2">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title mb-0 text-white">Fallidas</h6>
                                <h3 class="mb-0">8</h3>
                            </div>
                            <i class="bi bi-exclamation-triangle fs-1 opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Formulario de selección y envío -->
        <div class="card mb-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="card-title mb-0">Enviar Notificación</h5>
                    <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-toggle="collapse" data-bs-target="#notificationForm">
                        <i class="bi bi-chevron-up"></i>
                    </button>
                </div>
                
                <div class="collapse show" id="notificationForm">
                    <form action="{{ route('orgs.notifications.store', ['id' => $org->id]) }}" method="POST">
                        @csrf
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label for="notification_title" class="form-label">Título de la notificación:</label>
                                <input type="text" id="notification_title" name="title" class="form-control" required placeholder="Ingrese un título descriptivo">
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label for="notification_message" class="form-label">Mensaje:</label>
                                <textarea id="notification_message" name="message" class="form-control" rows="4" required placeholder="Escriba el contenido detallado de la notificación..."></textarea>
                                <div class="form-text">Describe el contenido de la notificación detalladamente.</div>
                            </div>
                        </div>
                        
                        <!-- Métodos de envío (solo correo) -->
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label class="form-label d-block">Método de envío:</label>
                                <div class="alert alert-info py-2">
                                    <i class="bi bi-info-circle me-2"></i>
                                    Las notificaciones se enviarán exclusivamente por correo electrónico
                                </div>
                                <!-- Campo oculto para forzar el envío por email -->
                                <input type="hidden" name="send_methods[]" value="email">
                            </div>
                        </div>
                        
                        <hr class="my-4">
                        
                        <!-- Selector de destinatarios - SOLUCIÓN CONFIABLE -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Enviar a:</label>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" id="send_to_all" name="send_to_all">
                                    <label class="form-check-label" for="send_to_all">Todos</label>
                                </div>
                                <input type="hidden" name="target_type" value="sector">
                            </div>
                        </div>

                        <!-- Selector de sectores simplificado -->
                        <div class="row mb-3" id="sector-container">
                            <div class="col-md-12">
                                <label for="sectors" class="form-label">Seleccionar Sectores:</label>
                                <select id="sectors" name="sectors[]" class="form-select" multiple required>
                                    @foreach ($activeLocations as $location)
                                        <option value="{{ $location->id }}">{{ $location->name }}</option>
                                    @endforeach
                                </select>
                                <div class="form-text">Mantén presionado Ctrl (o Cmd en Mac) para seleccionar múltiples sectores.</div>
                            </div>
                        </div>
                        
                        <div class="text-end">
                            <button type="reset" class="btn btn-outline-secondary me-2">
                                <i class="bi bi-x-circle"></i> Cancelar
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-send"></i> Enviar Notificación
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Listado de notificaciones -->
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="card-title mb-0">Listado de Notificaciones</h5>
                    <div>
                        <button class="btn btn-outline-secondary me-2" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFilters">
                            <i class="bi bi-filter"></i> Filtrar
                        </button>
                        <a href="#" class="btn btn-primary">
                            <i class="bi bi-plus-circle"></i> Nueva Notificación
                        </a>
                    </div>
                </div>

                <!-- Buscador -->
                <div class="row mb-3">
                    <div class="col-md-6 offset-md-6">
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Buscar notificaciones...">
                            <button class="btn btn-outline-secondary" type="button">
                                <i class="bi bi-search"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Filtros colapsables -->
                <div class="collapse mb-3" id="collapseFilters">
                    <div class="card card-body bg-light">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label class="form-label">Fecha desde</label>
                                <input type="date" class="form-control form-control-sm">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Fecha hasta</label>
                                <input type="date" class="form-control form-control-sm">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Estado</label>
                                <select class="form-select form-select-sm">
                                    <option value="">Todos</option>
                                    <option value="sent">Enviados</option>
                                    <option value="pending">Pendientes</option>
                                    <option value="failed">Fallidos</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Método de envío</label>
                                <select class="form-select form-select-sm">
                                    <option value="">Todos</option>
                                    <option value="app">Aplicación</option>
                                    <option value="email">Email</option>
                                    <option value="sms">SMS</option>
                                </select>
                            </div>
                            <div class="col-12 text-end">
                                <button class="btn btn-sm btn-outline-secondary me-2">Limpiar</button>
                                <button class="btn btn-sm btn-secondary">Aplicar filtros</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Título</th>
                                <th>Mensaje</th>
                                <th>Destinatarios</th>
                                <th>Métodos</th>
                                <th>Estado</th>
                                <th>Fecha</th>
                                <th class="text-end">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>Actualización del sistema</td>
                                <td>El sistema se actualizará esta noche a las 22:00.</td>
                                <td>
                                    <span class="badge bg-primary">Sector: Administración</span>
                                </td>
                                <td>
                                    <span class="badge rounded-pill bg-info text-white">
                                        <i class="bi bi-envelope me-1"></i>Email
                                    </span>
                                </td>
                                <td><span class="badge bg-success">Enviado</span></td>
                                <td>08/04/2025 12:34</td>
                                <td class="text-end">
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                            Acciones
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li><a class="dropdown-item" href="#"><i class="bi bi-eye"></i> Ver detalles</a></li>
                                            <li><a class="dropdown-item" href="#"><i class="bi bi-pencil"></i> Editar</a></li>
                                            <li><a class="dropdown-item" href="#"><i class="bi bi-arrow-repeat"></i> Reenviar</a></li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li><a class="dropdown-item text-danger" href="#"><i class="bi bi-trash"></i> Eliminar</a></li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>Mantenimiento programado</td>
                                <td>Habrá una pausa por mantenimiento el viernes.</td>
                                <td>
                                    <span class="badge bg-info">Todos los sectores</span>
                                </td>
                                <td>
                                    <span class="badge rounded-pill bg-light text-dark border"><i class="bi bi-app me-1"></i>App</span>
                                </td>
                                <td><span class="badge bg-success">Enviado</span></td>
                                <td>06/04/2025 09:15</td>
                                <td class="text-end">
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                            Acciones
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li><a class="dropdown-item" href="#"><i class="bi bi-eye"></i> Ver detalles</a></li>
                                            <li><a class="dropdown-item" href="#"><i class="bi bi-pencil"></i> Editar</a></li>
                                            <li><a class="dropdown-item" href="#"><i class="bi bi-arrow-repeat"></i> Reenviar</a></li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li><a class="dropdown-item text-danger" href="#"><i class="bi bi-trash"></i> Eliminar</a></li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Paginación -->
                <nav aria-label="Page navigation" class="mt-3">
                    <ul class="pagination justify-content-end">
                        <li class="page-item disabled">
                            <a class="page-link" href="#" tabindex="-1" aria-disabled="true">Anterior</a>
                        </li>
                        <li class="page-item active"><a class="page-link" href="#">1</a></li>
                        <li class="page-item"><a class="page-link" href="#">2</a></li>
                        <li class="page-item"><a class="page-link" href="#">3</a></li>
                        <li class="page-item">
                            <a class="page-link" href="#">Siguiente</a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </section>

    @push('styles')
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
    <style>
        /* ESTILOS PARA SELECT DESHABILITADO */
        select:disabled {
            background-color: #e9ecef;
            opacity: 0.7;
            cursor: not-allowed;
        }
        
        /* Estilos generales */
        .badge {
            font-weight: 500;
        }
        .table > :not(caption) > * > * {
            padding: 0.75rem 0.75rem;
        }
        .card {
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        }
        .card-title {
            font-weight: 600;
        }
    </style>
    @endpush

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Elementos del formulario
            const sendToAllCheckbox = document.getElementById('send_to_all');
            const sectorsSelect = document.getElementById('sectors');

            // Función para bloquear/desbloquear los sectores
            function toggleSectors() {
                if (sendToAllCheckbox.checked) {
                    // 1. Deshabilitar el elemento select
                    sectorsSelect.disabled = true;
                    
                    // 2. Limpiar selecciones existentes
                    Array.from(sectorsSelect.options).forEach(option => {
                        option.selected = false;
                    });
                    
                    // 3. Hacer que el select no sea requerido
                    sectorsSelect.required = false;
                } else {
                    // 1. Habilitar el elemento select
                    sectorsSelect.disabled = false;
                    
                    // 2. Hacer que el select sea requerido
                    sectorsSelect.required = true;
                }
            }

            // Configurar el event listener
            sendToAllCheckbox.addEventListener('change', toggleSectors);
            
            // Inicializar el estado
            toggleSectors();

            // Configuración de Summernote
            if (typeof $.fn.summernote !== 'undefined') {
                $('#notification_message').summernote({
                    placeholder: 'Escribe el contenido de la notificación...',
                    tabsize: 2,
                    height: 120,
                    toolbar: [
                        ['style', ['style']],
                        ['font', ['bold', 'underline', 'clear']],@extends('layouts.nice', ['active'=>'orgs.notifications.index', 'title'=>'Notificaciones'])

@section('content')
    <div class="pagetitle">
        <h1>{{$org->name}}</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item"><a href="{{route('orgs.index')}}">Organizaciones</a></li>
                <li class="breadcrumb-item"><a href="{{route('orgs.dashboard',$org->id)}}">{{$org->name}}</a></li>
                <li class="breadcrumb-item active">Notificaciones</li>
            </ol>
        </nav>
    </div>

    <section class="section dashboard">
        <!-- Estadísticas rápidas -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card bg-primary text-white">
                    <div class="card-body py-2">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title mb-0 text-white">Total</h6>
                                <h3 class="mb-0">124</h3>
                            </div>
                            <i class="bi bi-bell fs-1 opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-success text-white">
                    <div class="card-body py-2">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title mb-0 text-white">Enviadas</h6>
                                <h3 class="mb-0">98</h3>
                            </div>
                            <i class="bi bi-check-circle fs-1 opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-warning text-white">
                    <div class="card-body py-2">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title mb-0 text-white">Pendientes</h6>
                                <h3 class="mb-0">18</h3>
                            </div>
                            <i class="bi bi-hourglass-split fs-1 opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-danger text-white">
                    <div class="card-body py-2">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title mb-0 text-white">Fallidas</h6>
                                <h3 class="mb-0">8</h3>
                            </div>
                            <i class="bi bi-exclamation-triangle fs-1 opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Formulario de selección y envío -->
        <div class="card mb-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="card-title mb-0">Enviar Notificación</h5>
                    <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-toggle="collapse" data-bs-target="#notificationForm">
                        <i class="bi bi-chevron-up"></i>
                    </button>
                </div>
                
                <div class="collapse show" id="notificationForm">
                    <form action="{{ route('orgs.notifications.store', ['id' => $org->id]) }}" method="POST">
                        @csrf
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label for="notification_title" class="form-label">Título de la notificación:</label>
                                <input type="text" id="notification_title" name="title" class="form-control" required placeholder="Ingrese un título descriptivo">
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label for="notification_message" class="form-label">Mensaje:</label>
                                <textarea id="notification_message" name="message" class="form-control" rows="4" required placeholder="Escriba el contenido detallado de la notificación..."></textarea>
                                <div class="form-text">Describe el contenido de la notificación detalladamente.</div>
                            </div>
                        </div>
                        
                        <!-- Métodos de envío (solo correo) -->
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label class="form-label d-block">Método de envío:</label>
                                <div class="alert alert-info py-2">
                                    <i class="bi bi-info-circle me-2"></i>
                                    Las notificaciones se enviarán exclusivamente por correo electrónico
                                </div>
                                <!-- Campo oculto para forzar el envío por email -->
                                <input type="hidden" name="send_methods[]" value="email">
                            </div>
                        </div>
                        
                        <hr class="my-4">
                        
                        <!-- Selector de destinatarios - SOLUCIÓN CONFIABLE -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Enviar a:</label>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" id="send_to_all" name="send_to_all">
                                    <label class="form-check-label" for="send_to_all">Todos</label>
                                </div>
                                <input type="hidden" name="target_type" value="sector">
                            </div>
                        </div>

                        <!-- Selector de sectores simplificado -->
                        <div class="row mb-3" id="sector-container">
                            <div class="col-md-12">
                                <label for="sectors" class="form-label">Seleccionar Sectores:</label>
                                <select id="sectors" name="sectors[]" class="form-select" multiple required>
                                    @foreach ($activeLocations as $location)
                                        <option value="{{ $location->id }}">{{ $location->name }}</option>
                                    @endforeach
                                </select>
                                <div class="form-text">Mantén presionado Ctrl (o Cmd en Mac) para seleccionar múltiples sectores.</div>
                            </div>
                        </div>
                        
                        <div class="text-end">
                            <button type="reset" class="btn btn-outline-secondary me-2">
                                <i class="bi bi-x-circle"></i> Cancelar
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-send"></i> Enviar Notificación
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Listado de notificaciones -->
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="card-title mb-0">Listado de Notificaciones</h5>
                    <div>
                        <button class="btn btn-outline-secondary me-2" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFilters">
                            <i class="bi bi-filter"></i> Filtrar
                        </button>
                        <a href="#" class="btn btn-primary">
                            <i class="bi bi-plus-circle"></i> Nueva Notificación
                        </a>
                    </div>
                </div>

                <!-- Buscador -->
                <div class="row mb-3">
                    <div class="col-md-6 offset-md-6">
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Buscar notificaciones...">
                            <button class="btn btn-outline-secondary" type="button">
                                <i class="bi bi-search"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Filtros colapsables -->
                <div class="collapse mb-3" id="collapseFilters">
                    <div class="card card-body bg-light">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label class="form-label">Fecha desde</label>
                                <input type="date" class="form-control form-control-sm">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Fecha hasta</label>
                                <input type="date" class="form-control form-control-sm">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Estado</label>
                                <select class="form-select form-select-sm">
                                    <option value="">Todos</option>
                                    <option value="sent">Enviados</option>
                                    <option value="pending">Pendientes</option>
                                    <option value="failed">Fallidos</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Método de envío</label>
                                <select class="form-select form-select-sm">
                                    <option value="">Todos</option>
                                    <option value="app">Aplicación</option>
                                    <option value="email">Email</option>
                                    <option value="sms">SMS</option>
                                </select>
                            </div>
                            <div class="col-12 text-end">
                                <button class="btn btn-sm btn-outline-secondary me-2">Limpiar</button>
                                <button class="btn btn-sm btn-secondary">Aplicar filtros</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Título</th>
                                <th>Mensaje</th>
                                <th>Destinatarios</th>
                                <th>Métodos</th>
                                <th>Estado</th>
                                <th>Fecha</th>
                                <th class="text-end">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>Actualización del sistema</td>
                                <td>El sistema se actualizará esta noche a las 22:00.</td>
                                <td>
                                    <span class="badge bg-primary">Sector: Administración</span>
                                </td>
                                <td>
                                    <span class="badge rounded-pill bg-info text-white">
                                        <i class="bi bi-envelope me-1"></i>Email
                                    </span>
                                </td>
                                <td><span class="badge bg-success">Enviado</span></td>
                                <td>08/04/2025 12:34</td>
                                <td class="text-end">
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                            Acciones
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li><a class="dropdown-item" href="#"><i class="bi bi-eye"></i> Ver detalles</a></li>
                                            <li><a class="dropdown-item" href="#"><i class="bi bi-pencil"></i> Editar</a></li>
                                            <li><a class="dropdown-item" href="#"><i class="bi bi-arrow-repeat"></i> Reenviar</a></li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li><a class="dropdown-item text-danger" href="#"><i class="bi bi-trash"></i> Eliminar</a></li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>Mantenimiento programado</td>
                                <td>Habrá una pausa por mantenimiento el viernes.</td>
                                <td>
                                    <span class="badge bg-info">Todos los sectores</span>
                                </td>
                                <td>
                                    <span class="badge rounded-pill bg-light text-dark border"><i class="bi bi-app me-1"></i>App</span>
                                </td>
                                <td><span class="badge bg-success">Enviado</span></td>
                                <td>06/04/2025 09:15</td>
                                <td class="text-end">
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                            Acciones
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li><a class="dropdown-item" href="#"><i class="bi bi-eye"></i> Ver detalles</a></li>
                                            <li><a class="dropdown-item" href="#"><i class="bi bi-pencil"></i> Editar</a></li>
                                            <li><a class="dropdown-item" href="#"><i class="bi bi-arrow-repeat"></i> Reenviar</a></li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li><a class="dropdown-item text-danger" href="#"><i class="bi bi-trash"></i> Eliminar</a></li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Paginación -->
                <nav aria-label="Page navigation" class="mt-3">
                    <ul class="pagination justify-content-end">
                        <li class="page-item disabled">
                            <a class="page-link" href="#" tabindex="-1" aria-disabled="true">Anterior</a>
                        </li>
                        <li class="page-item active"><a class="page-link" href="#">1</a></li>
                        <li class="page-item"><a class="page-link" href="#">2</a></li>
                        <li class="page-item"><a class="page-link" href="#">3</a></li>
                        <li class="page-item">
                            <a class="page-link" href="#">Siguiente</a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </section>

    
    
@endsection@extends('layouts.nice', ['active'=>'orgs.notifications.index', 'title'=>'Notificaciones'])

@section('content')
    <div class="pagetitle">
        <h1>{{$org->name}}</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item"><a href="{{route('orgs.index')}}">Organizaciones</a></li>
                <li class="breadcrumb-item"><a href="{{route('orgs.dashboard',$org->id)}}">{{$org->name}}</a></li>
                <li class="breadcrumb-item active">Notificaciones</li>
            </ol>
        </nav>
    </div>

    <section class="section dashboard">
        <!-- Estadísticas rápidas -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card bg-primary text-white">
                    <div class="card-body py-2">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title mb-0 text-white">Total</h6>
                                <h3 class="mb-0">124</h3>
                            </div>
                            <i class="bi bi-bell fs-1 opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-success text-white">
                    <div class="card-body py-2">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title mb-0 text-white">Enviadas</h6>
                                <h3 class="mb-0">98</h3>
                            </div>
                            <i class="bi bi-check-circle fs-1 opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-warning text-white">
                    <div class="card-body py-2">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title mb-0 text-white">Pendientes</h6>
                                <h3 class="mb-0">18</h3>
                            </div>
                            <i class="bi bi-hourglass-split fs-1 opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-danger text-white">
                    <div class="card-body py-2">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title mb-0 text-white">Fallidas</h6>
                                <h3 class="mb-0">8</h3>
                            </div>
                            <i class="bi bi-exclamation-triangle fs-1 opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Formulario de selección y envío -->
        <div class="card mb-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="card-title mb-0">Enviar Notificación</h5>
                    <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-toggle="collapse" data-bs-target="#notificationForm">
                        <i class="bi bi-chevron-up"></i>
                    </button>
                </div>
                
                <div class="collapse show" id="notificationForm">
                    <form action="{{ route('orgs.notifications.store', ['id' => $org->id]) }}" method="POST">
                        @csrf
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label for="notification_title" class="form-label">Título de la notificación:</label>
                                <input type="text" id="notification_title" name="title" class="form-control" required placeholder="Ingrese un título descriptivo">
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label for="notification_message" class="form-label">Mensaje:</label>
                                <textarea 
    id="notification_message" 
    name="message" 
    class="form-control" 
    rows="5" 
    required 
    placeholder="Escribe el contenido de la notificación..."
></textarea>
                                <div class="form-text">Describe el contenido de la notificación detalladamente.</div>
                            </div>
                        </div>
                        
                        <!-- Métodos de envío (solo correo) -->
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label class="form-label d-block">Método de envío:</label>
                                <div class="alert alert-info py-2">
                                    <i class="bi bi-info-circle me-2"></i>
                                    Las notificaciones se enviarán exclusivamente por correo electrónico
                                </div>
                                <!-- Campo oculto para forzar el envío por email -->
                                <input type="hidden" name="send_methods[]" value="email">
                            </div>
                        </div>
                        
                        <hr class="my-4">
                        
                        <!-- Selector de destinatarios - SOLUCIÓN CONFIABLE -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Enviar a:</label>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" id="send_to_all" name="send_to_all">
                                    <label class="form-check-label" for="send_to_all">Todos</label>
                                </div>
                                <input type="hidden" name="target_type" value="sector">
                            </div>
                        </div>

                        <!-- Selector de sectores simplificado -->
                        <div class="row mb-3" id="sector-container">
                            <div class="col-md-12">
                                <label for="sectors" class="form-label">Seleccionar Sectores:</label>
                                <select id="sectors" name="sectors[]" class="form-select" multiple required>
                                    @foreach ($activeLocations as $location)
                                        <option value="{{ $location->id }}">{{ $location->name }}</option>
                                    @endforeach
                                </select>
                                <div class="form-text">Mantén presionado Ctrl (o Cmd en Mac) para seleccionar múltiples sectores.</div>
                            </div>
                        </div>
                        
                        <div class="text-end">
                            <button type="reset" class="btn btn-outline-secondary me-2">
                                <i class="bi bi-x-circle"></i> Cancelar
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-send"></i> Enviar Notificación
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Listado de notificaciones -->
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="card-title mb-0">Listado de Notificaciones</h5>
                    <div>
                        <button class="btn btn-outline-secondary me-2" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFilters">
                            <i class="bi bi-filter"></i> Filtrar
                        </button>
                        <a href="#" class="btn btn-primary">
                            <i class="bi bi-plus-circle"></i> Nueva Notificación
                        </a>
                    </div>
                </div>

                <!-- Buscador -->
                <div class="row mb-3">
                    <div class="col-md-6 offset-md-6">
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Buscar notificaciones...">
                            <button class="btn btn-outline-secondary" type="button">
                                <i class="bi bi-search"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Filtros colapsables -->
                <div class="collapse mb-3" id="collapseFilters">
                    <div class="card card-body bg-light">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label class="form-label">Fecha desde</label>
                                <input type="date" class="form-control form-control-sm">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Fecha hasta</label>
                                <input type="date" class="form-control form-control-sm">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Estado</label>
                                <select class="form-select form-select-sm">
                                    <option value="">Todos</option>
                                    <option value="sent">Enviados</option>
                                    <option value="pending">Pendientes</option>
                                    <option value="failed">Fallidos</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Método de envío</label>
                                <select class="form-select form-select-sm">
                                    <option value="">Todos</option>
                                    <option value="app">Aplicación</option>
                                    <option value="email">Email</option>
                                    <option value="sms">SMS</option>
                                </select>
                            </div>
                            <div class="col-12 text-end">
                                <button class="btn btn-sm btn-outline-secondary me-2">Limpiar</button>
                                <button class="btn btn-sm btn-secondary">Aplicar filtros</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Título</th>
                                <th>Mensaje</th>
                                <th>Destinatarios</th>
                                <th>Métodos</th>
                                <th>Estado</th>
                                <th>Fecha</th>
                                <th class="text-end">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>Actualización del sistema</td>
                                <td>El sistema se actualizará esta noche a las 22:00.</td>
                                <td>
                                    <span class="badge bg-primary">Sector: Administración</span>
                                </td>
                                <td>
                                    <span class="badge rounded-pill bg-info text-white">
                                        <i class="bi bi-envelope me-1"></i>Email
                                    </span>
                                </td>
                                <td><span class="badge bg-success">Enviado</span></td>
                                <td>08/04/2025 12:34</td>
                                <td class="text-end">
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                            Acciones
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li><a class="dropdown-item" href="#"><i class="bi bi-eye"></i> Ver detalles</a></li>
                                            <li><a class="dropdown-item" href="#"><i class="bi bi-pencil"></i> Editar</a></li>
                                            <li><a class="dropdown-item" href="#"><i class="bi bi-arrow-repeat"></i> Reenviar</a></li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li><a class="dropdown-item text-danger" href="#"><i class="bi bi-trash"></i> Eliminar</a></li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>Mantenimiento programado</td>
                                <td>Habrá una pausa por mantenimiento el viernes.</td>
                                <td>
                                    <span class="badge bg-info">Todos los sectores</span>
                                </td>
                                <td>
                                    <span class="badge rounded-pill bg-light text-dark border"><i class="bi bi-app me-1"></i>App</span>
                                </td>
                                <td><span class="badge bg-success">Enviado</span></td>
                                <td>06/04/2025 09:15</td>
                                <td class="text-end">
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                            Acciones
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li><a class="dropdown-item" href="#"><i class="bi bi-eye"></i> Ver detalles</a></li>
                                            <li><a class="dropdown-item" href="#"><i class="bi bi-pencil"></i> Editar</a></li>
                                            <li><a class="dropdown-item" href="#"><i class="bi bi-arrow-repeat"></i> Reenviar</a></li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li><a class="dropdown-item text-danger" href="#"><i class="bi bi-trash"></i> Eliminar</a></li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Paginación -->
                <nav aria-label="Page navigation" class="mt-3">
                    <ul class="pagination justify-content-end">
                        <li class="page-item disabled">
                            <a class="page-link" href="#" tabindex="-1" aria-disabled="true">Anterior</a>
                        </li>
                        <li class="page-item active"><a class="page-link" href="#">1</a></li>
                        <li class="page-item"><a class="page-link" href="#">2</a></li>
                        <li class="page-item"><a class="page-link" href="#">3</a></li>
                        <li class="page-item">
                            <a class="page-link" href="#">Siguiente</a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </section>

    
    
@endsection
                        ['color', ['color']],
                        ['para', ['ul', 'ol', 'paragraph']],
                        ['insert', ['link']]
                    ]
                });
            }

            // Configuración de tooltips
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });
    </script>
    @endpush
@endsection