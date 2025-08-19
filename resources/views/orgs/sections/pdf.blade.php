@extends('layouts.nice', ['active' => 'orgs.sections.index', 'title' => 'Tramos'])

@section('content')
    <div class="pagetitle">
        <h1>{{$org->name}}</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item"><a href="{{route('orgs.index')}}">Organizaciones</a></li>
                <li class="breadcrumb-item"><a href="{{route('orgs.dashboard', $org->id)}}">{{$org->name}}</a></li>
                <li class="breadcrumb-item active">Tramos</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section dashboard">
    <div class="card top-selling overflow-auto">
        <div class="card-body pt-2">
            <h4>Configuración de Costos Fijos</h4>
            <table class="table table-bordered mb-4">
                <thead class="table-light">
                    <tr>
                        <th>Cargo Fijo</th>
                        <th>Penalización por Reposición</th>
                        <th>Mora</th>
                        <th>Penalización por Vencimiento</th>
                        <th>Máx. m³ Cubiertos</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ $fixedCostConfig->fixed_charge_penalty }}</td>
                        <td>{{ $fixedCostConfig->replacement_penalty }}</td>
                        <td>{{ $fixedCostConfig->late_fee_penalty }}</td>
                        <td>{{ $fixedCostConfig->expired_penalty }}</td>
                        <td>{{ $fixedCostConfig->max_covered_m3 }}</td>
                    </tr>
                </tbody>
            </table>

            <h4>Tramos</h4>
            <table class="table table-bordered table-hover">
                <thead class="table-light">
                    <tr>
                        <th class="text-center">Tramo</th>
                        <th class="text-center">Desde</th>
                        <th class="text-center">Hasta</th>
                        <th class="text-center">Valor $</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($tiers as $tier)
                    <tr>
                        <td class="text-center">{{ $tier->tier_name}}</td>
                        <td class="text-center">{{$tier->range_from}}</td>
                        <td class="text-center">{{$tier->range_to}}</td>
                        <td class="text-center">{{$tier->value}}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    </section>
@endsection
