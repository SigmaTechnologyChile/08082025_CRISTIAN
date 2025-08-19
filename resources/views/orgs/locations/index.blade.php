@extends('layouts.nice', ['active'=>'orgs.locations.index','title'=>'Sectores'])

@section('content')
    <div class="pagetitle">
      <h1>{{$org->name}}</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="/">Home</a></li>
          <li class="breadcrumb-item"><a href="{{route('orgs.index')}}">Organizaciones</a></li>
          <li class="breadcrumb-item"><a href="{{route('orgs.dashboard',$org->id)}}">{{$org->name}}</a></li>
          <li class="breadcrumb-item active">Sectores</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section dashboard">
    <div class="card top-selling overflow-auto">
        <div class="card-body pt-2">
            <div class="row mb-4 align-items-center">  <!-- A�0�9adido align-items-center -->
                <!-- Search Bar - Reducido a col-md-7 -->
                <div class="col-md-7">
                    <div class="search-container position-relative z-index-1">
                        <div class="search-backdrop rounded-2 p-1"></div>
                        <form method="GET" id="searchForm" class="position-relative">
                            <div class="input-group input-group-sm search-input-group">
                                <span class="input-group-text border-0 bg-white ps-4">
                                    <i class="bi bi-search fs-4 text-primary"></i>
                                </span>
                                <input type="text" name="search" id="searchInput" class="form-control rounded-3 me-2"
                                    placeholder="Buscar por Sector" value="{{ request('search') }}">
                                <button type="submit" class="btn btn-primary px-4 rounded-end-3 pulse-btn">
                                    <span class="d-none d-md-inline">Buscar</span>
                                    <i class="bi bi-arrow-right d-inline d-md-none"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                
                <!-- Botones - Ajustado a col-md-5 y centrado -->
                <div class="col-md-5 d-flex justify-content-end align-items-center gap-2 mt-3 mt-md-0">
                    <a href="{{route('orgs.locations.create',$org->id)}}" class="btn btn-primary pulse-btn px-3 rounded-2 d-flex align-items-center" style="height: 34px;">
                        <i class="bi bi-plus-circle-fill me-2"></i>
                        <span>Nuevo</span>
                    </a>
                    <a href="{{ route('orgs.locations.export', ['id' => $org->id, 'search' => request('search')]) }}" class="btn btn-primary pulse-btn px-3 rounded-2 d-flex align-items-center" style="height: 34px;">
                        <i class="bi bi-box-arrow-up-right me-2"></i>
                        <span>Exportar</span>
                    </a>
                </div>
            </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive mt-3">
                <table class="table table-bordered table-hover">
                    <thead class="table-light">
                        <tr>
                                <th scope="col">ID</th>
                                <th scope="col">Sector</th>
                                <th scope="col">Comuna</th>
                                <th scope="col">Región</th>
                                <th scope="col" class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                           @foreach($locations as $location)
                            <tr>
                                <td>{{ $location->id }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="icon-box bg-light-primary rounded-circle me-2">
                                            <i class="ri-map-2-fill text-primary"></i>
                                        </div>
                                        <span class="fw-medium">{{$location->name}}</span>
                                    </div>
                                </td>
                                <td>{{$location->name_city}}</td>
                                <td>{{$location->name_state}}</td>
                                <td class="text-center">
                                    <a href="{{ route('orgs.locations.edit', [$org->id, $location->id]) }}" class="btn btn-primary btn-sm" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Editar"><i class="bi bi-pencil"></i></a>

                                </td>
                            </tr>
                          @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {!! $locations->render('pagination::bootstrap-4') !!}
                </div>
            </div>
        </div>
    </section>
@endsection
