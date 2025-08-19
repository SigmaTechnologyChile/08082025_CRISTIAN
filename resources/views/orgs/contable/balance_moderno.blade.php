@extends('layouts.nice', ['active' => 'balance_moderno', 'title' => 'Balance Financiero Moderno'])

@section('content')
<div class="container my-4">
  <h2 class="mb-3 text-primary">Balance Financiero Moderno</h2>
  <div class="row mb-4">
    <div class="col-md-4">
      <div class="card shadow-sm">
        <div class="card-body">
          <h5 class="card-title text-success">Activos Corrientes</h5>
          <p class="card-text fs-4 fw-bold">${{ number_format($activosCorrientes ?? 0, 0, ',', '.') }}</p>
          <small class="text-muted">Efectivo, bancos, cuentas por cobrar, inventarios...</small>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card shadow-sm">
        <div class="card-body">
          <h5 class="card-title text-info">Activos No Corrientes</h5>
          <p class="card-text fs-4 fw-bold">${{ number_format($activosNoCorrientes ?? 0, 0, ',', '.') }}</p>
          <small class="text-muted">Propiedades, equipo, intangibles, inversiones...</small>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card shadow-sm">
        <div class="card-body">
          <h5 class="card-title text-warning">Activos Digitales</h5>
          <p class="card-text fs-4 fw-bold">${{ number_format($activosDigitales ?? 0, 0, ',', '.') }}</p>
          <small class="text-muted">Licencias, propiedad intelectual, criptomonedas...</small>
        </div>
      </div>
    </div>
  </div>
  <div class="row mb-4">
    <div class="col-md-4">
      <div class="card shadow-sm">
        <div class="card-body">
          <h5 class="card-title text-danger">Pasivos Corrientes</h5>
          <p class="card-text fs-4 fw-bold">${{ number_format($pasivosCorrientes ?? 0, 0, ',', '.') }}</p>
          <small class="text-muted">Proveedores, deudas, obligaciones fiscales...</small>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card shadow-sm">
        <div class="card-body">
          <h5 class="card-title text-secondary">Pasivos No Corrientes</h5>
          <p class="card-text fs-4 fw-bold">${{ number_format($pasivosNoCorrientes ?? 0, 0, ',', '.') }}</p>
          <small class="text-muted">Préstamos, bonos, provisiones...</small>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card shadow-sm">
        <div class="card-body">
          <h5 class="card-title text-success">Pasivos ESG/Ambientales</h5>
          <p class="card-text fs-4 fw-bold">${{ number_format($pasivosESG ?? 0, 0, ',', '.') }}</p>
          <small class="text-muted">Compensaciones de carbono, provisiones medioambientales...</small>
        </div>
      </div>
    </div>
  </div>
  <div class="row mb-4">
    <div class="col-md-6">
      <div class="card shadow-sm">
        <div class="card-body">
          <h5 class="card-title text-primary">Patrimonio</h5>
          <p class="card-text fs-4 fw-bold">${{ number_format($patrimonio ?? 0, 0, ',', '.') }}</p>
          <small class="text-muted">Capital social, reservas, resultados acumulados...</small>
        </div>
      </div>
    </div>
    <div class="col-md-6">
      <div class="card shadow-sm">
        <div class="card-body">
          <h5 class="card-title text-info">Otros Resultados Integrales</h5>
          <p class="card-text fs-4 fw-bold">${{ number_format($otrosResultados ?? 0, 0, ',', '.') }}</p>
          <small class="text-muted">Ajustes por revaluación, diferencias de conversión...</small>
        </div>
      </div>
    </div>
  </div>
  <div class="row mb-4">
    <div class="col-md-6">
      <h5 class="mb-2">Ratios Financieros Modernos</h5>
      <ul class="list-group">
        <li class="list-group-item">Liquidez Corriente: <span class="fw-bold">{{ number_format($ratioLiquidez ?? 0, 2) }}</span></li>
        <li class="list-group-item">Endeudamiento: <span class="fw-bold">{{ number_format($ratioEndeudamiento ?? 0, 2) }}%</span></li>
        <li class="list-group-item">Rentabilidad (ROE): <span class="fw-bold">{{ number_format($roe ?? 0, 2) }}%</span></li>
        <li class="list-group-item">EBITDA: <span class="fw-bold">${{ number_format($ebitda ?? 0, 0, ',', '.') }}</span></li>
        <li class="list-group-item">Flujo de Caja Libre: <span class="fw-bold">${{ number_format($flujoCajaLibre ?? 0, 0, ',', '.') }}</span></li>
        <li class="list-group-item">Índice de Sostenibilidad ESG: <span class="fw-bold">{{ number_format($indiceESG ?? 0, 2) }}%</span></li>
      </ul>
    </div>
    <div class="col-md-6">
      <h5 class="mb-2">Gráfico de Composición</h5>
      <canvas id="balanceChart" height="120"></canvas>
    </div>
  </div>
  <div class="table-responsive">
    <table class="table table-bordered">
      <thead class="table-light">
        <tr>
          <th>Cuenta</th>
          <th>Tipo</th>
          <th>Monto</th>
        </tr>
      </thead>
      <tbody>
        @foreach($cuentas as $cuenta)
        <tr>
          <td>{{ $cuenta->nombre }}</td>
          <td>{{ $cuenta->tipo }}</td>
          <td>${{ number_format($cuenta->monto, 0, ',', '.') }}</td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
  <div class="row mt-4">
    <div class="col-md-12">
      <h5 class="mb-2">Notas Explicativas y Auditoría</h5>
      <div class="alert alert-info">
        <strong>Notas:</strong> Detalle de activos intangibles, inversiones en tecnología, riesgos emergentes, historial de cambios y control de acceso.
      </div>
    </div>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('balanceChart').getContext('2d');
new Chart(ctx, {
  type: 'doughnut',
  data: {
    labels: ['Activos', 'Pasivos', 'Patrimonio'],
    datasets: [{
      data: [
        {{ ($activosCorrientes ?? 0) + ($activosNoCorrientes ?? 0) + ($activosDigitales ?? 0) }},
        {{ ($pasivosCorrientes ?? 0) + ($pasivosNoCorrientes ?? 0) + ($pasivosESG ?? 0) }},
        {{ $patrimonio ?? 0 }}
      ],
      backgroundColor: ['#198754', '#dc3545', '#0dcaf0'],
    }]
  }
});
</script>
@endsection
