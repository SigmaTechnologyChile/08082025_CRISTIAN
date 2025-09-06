@extends('layouts.nice', ['active'=>'orgs','title'=>'Organizaciones'])
@php
    $orgId = Auth::user()->org_id ?? null;
    if ($orgId == 2240) {
        header('Location: ' . route('orgs.dashboard', ['id' => 2240]));
        exit;
    }
@endphp



@section('content')

    <head>

        <link rel="stylesheet" href="{{ asset('theme/resi/assets/css/orgs.css') }}">

    </head>



    <div class="orgs-container">
        

        <section class="orgs-dashboard">

            <!-- Buscador general -->

            <div class="card mb-4">

                <div class="card-body">

                    <form method="GET" action="{{ route('orgs.index') }}" class="row g-3">
                        
                        <div class="row g-3 align-items-center">

                        <div class="col-md-8">

                            <input type="text" name="search" class="form-control" 

                                   placeholder="Buscar por Nombre o RUT..." 

                                   value="{{ request('search') }}">

                        </div>

                        <div class="col-md-2">

                            <button type="submit" class="btn btn-primary w-100">

                                <i class="bi bi-search"></i> Buscar

                            </button>

                        </div>

                        <div class="col-md-2">

                            <a href="{{ route('orgs.index') }}" class="btn btn-outline-secondary w-100">

                                <i class="bi bi-arrow-counterclockwise"></i> Limpiar

                            </a>

                        </div>
                        
                        </div>

                    </form>

                </div>

            </div>



            <!-- Tabla con ordenamiento -->

            <div class="table-responsive">

                <table class="table table-striped table-hover">

                    <thead class="table-light">

                        <tr>

                            @php

                                $sortIcon = function($field) {

                                    if(request('sort') == $field) {

                                        return request('direction') == 'asc' 

                                            ? '<i class="bi bi-caret-up-fill ms-1"></i>' 

                                            : '<i class="bi bi-caret-down-fill ms-1"></i>';

                                    }

                                    return $field == 'active' && !request('sort') 

                                        ? '<i class="bi bi-caret-down-fill ms-1"></i>' 

                                        : '';

                                };

                                $getDirection = function($field) {

                                    if(request('sort') == $field) {

                                        return request('direction') == 'asc' ? 'desc' : 'asc';

                                    }

                                    return $field == 'active' && !request('sort') ? 'desc' : 'asc';

                                };

                            @endphp

                            

                            <th>

                                <a href="{{ request()->fullUrlWithQuery([

                                    'sort' => 'active', 

                                    'direction' => $getDirection('active')

                                ]) }}" class="text-decoration-none text-dark">

                                    Estado {!! $sortIcon('active') !!}

                                </a>

                            </th>

                            <th>

                                <a href="{{ request()->fullUrlWithQuery([

                                    'sort' => 'rut', 

                                    'direction' => request('sort') == 'rut' && request('direction') == 'asc' ? 'desc' : 'asc'

                                ]) }}" class="text-decoration-none text-dark">

                                    RUT {!! $sortIcon('rut') !!}

                                </a>

                            </th>

                            <th>

                                <a href="{{ request()->fullUrlWithQuery([

                                    'sort' => 'name', 

                                    'direction' => request('sort') == 'name' && request('direction') == 'asc' ? 'desc' : 'asc'

                                ]) }}" class="text-decoration-none text-dark">

                                    Raz¨®n Social {!! $sortIcon('name') !!}

                                </a>

                            </th>

                            <th>

                                <a href="{{ request()->fullUrlWithQuery([

                                    'sort' => 'commune', 

                                    'direction' => request('sort') == 'commune' && request('direction') == 'asc' ? 'desc' : 'asc'

                                ]) }}" class="text-decoration-none text-dark">

                                    Comuna {!! $sortIcon('commune') !!}

                                </a>

                            </th>

                            <th>

                                <a href="{{ request()->fullUrlWithQuery([

                                    'sort' => 'state', 

                                    'direction' => request('sort') == 'state' && request('direction') == 'asc' ? 'desc' : 'asc'

                                ]) }}" class="text-decoration-none text-dark">

                                    Regi¨®n {!! $sortIcon('state') !!}

                                </a>

                            </th>

                            <th>Acciones</th>

                        </tr>

                    </thead>

                    <tbody>

                        @foreach($orgs as $org)

                        <tr>

                            <td>

                                @if($org->active)

                                    <span class="badge bg-success">Activado</span>

                                @else

                                    <span class="badge bg-secondary">Desactivado</span>

                                @endif

                            </td>

                            <td>{{$org->rut}}</td>

                            <td>

                                <a href="{{route('orgs.dashboard',$org->id)}}" class="text-primary text-decoration-none">

                                    {{$org->name}}

                                </a>

                            </td>

                            <td>{{$org->commune}}</td>

                            <td>{{$org->state}}</td>

                            <td>

                                <a href="{{route('orgs.dashboard',$org->id)}}" class="btn btn-primary btn-sm custom-xs-btn">

                                    Editar <i class="bi bi-arrow-right"></i>

                                </a>

                            </td>

                        </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>



            <div class="orgs-pagination">

                {!! $orgs->appends(request()->query())->links('pagination::bootstrap-4') !!}

            </div>

        </section>

    </div>

@endsection



@push('styles')

    <link rel="stylesheet" href="{{ asset('css/organizations.css') }}">

    <style>

        .custom-xs-btn {

            padding: 0.15rem 0.35rem !important;

            font-size: 0.75rem !important;

            line-height: 1.2;

        }

        .table-responsive {

            margin-top: 20px;

            border-radius: 8px;

            overflow: hidden;

            box-shadow: 0 0 10px rgba(0,0,0,0.1);

        }

        .table-light {

            background-color: #f8f9fa !important;

        }

        .table th {

            white-space: nowrap;

            vertical-align: middle;

            color: #000 !important;

        }

        .table th a {

            color: #000 !important;

            text-decoration: none;

            display: flex;

            justify-content: space-between;

            align-items: center;

        }

        .badge {

            font-size: 0.85em;

            padding: 5px 8px;

        }

        a.text-primary {

            color: #0dcaf0 !important;

        }

        a.text-primary:hover {

            color: #0b8da8 !important;

            text-decoration: underline;

        }

    </style>

@endpush