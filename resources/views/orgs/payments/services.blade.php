@extends('layouts.nice', ['active'=>'orgs.payments.index','title'=>'Seleccionar Servicios'])

@section('content')
  @if(session('error'))
    <div class="alert alert-danger">
      {{ session('error') }}
    </div>
  @endif
  @if($errors->any())
    <div class="alert alert-danger">
      <ul>
        @foreach($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif
    <div class="pagetitle">
      <h1>Seleccionar Servicios</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="/">Home</a></li>
          <li class="breadcrumb-item"><a href="{{ route('orgs.index') }}">Organizaciones</a></li>
          <li class="breadcrumb-item"><a href="{{ route('orgs.dashboard', $org->id) }}">{{ $org->name }}</a></li>
          <li class="breadcrumb-item active">Seleccionar Servicios</li>
        </ol>
      </nav>
    </div>

    <div class="col-12">
      <div class="card">
        <div class="card-body m-2">
          <div class="d-flex justify-content-end mb-2">
            <button type="button" class="btn btn-outline-danger" style="background-color: #f8d7da; color: #721c24; border-color: #f5c6cb;" onclick="window.history.back();">
              Volver
            </button>
          </div>
           <div class="mb-4">
              <h4><strong>{{ $member->first_name }} {{ $member->last_name }}, tienes los siguientes servicios:</strong></h4>
            </div>
            
            @if(config('app.debug'))
            <div class="alert alert-info" role="alert">
                <small>
                    <strong>Debug Info:</strong><br>
                    RUT: {{ $rut ?? 'N/A' }}<br>
                    Member ID: {{ $member->id ?? 'N/A' }}<br>
                    Services Count: {{ $services ? $services->count() : 'null' }}<br>
                    Org ID: {{ $org->id ?? 'N/A' }}
                </small>
            </div>
            @endif
            
            @if($services && $services->count() > 0)
            <form method="POST" action="{{ route('orgs.orders.store', $org->id) }}">
            @csrf

            <div class="table-responsive">
              <table class="table table-hover align-middle">
                <thead class="table-light">
                  <tr>
                    <th scope="col">N° Servicio</th>
                    <th scope="col">Sector</th>
                    <th scope="col">Período</th>
                    <th scope="col">Folio</th>
                    <th scope="col">Estado</th>
                    <th scope="col">Total ($)</th>
                    <th scope="col">Seleccionar</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($services as $service)
                  <tr>
                    <td>
                        <span class="badge bg-info" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="ID Servicio: {{ $service->service_id }} | Reading ID: {{ $service->reading_id }}">
                            {{ str_pad($service->nro, 5, '0', STR_PAD_LEFT) }}
                        </span>
                    </td>
                    <td>{{ ucwords(str_replace('_', ' ', strtolower($service->sector))) }}</td>
                    <td>
                        <span class="badge bg-secondary">{{ $service->period ?? 'N/A' }}</span>
                    </td>
                    <td>
                        <small class="text-muted">{{ $service->folio ?? 'N/A' }}</small>
                    </td>
                    <td>
                        @if($service->total_sum > 0)
                            <span class="badge bg-warning">Pendiente de pago</span>
                        @else
                            <span class="badge bg-success">Sin deudas</span>
                        @endif
                    </td>
                    <td class="text-end">@money($service->total_sum)</td>
                    <td>
                        <input type="checkbox" class="service-checkbox" data-total="{{ $service->total_sum }}" value="{{ $service->reading_id }}" name="services[]">
                        <style>
                        .service-checkbox {
                          width: 28px;
                          height: 28px;
                          accent-color: #3498db;
                          margin-right: 8px;
                          cursor: pointer;
                        }
                        </style>
                    </td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
            </div>

            <div class="mt-3">
              <strong>Total Seleccionado: </strong>
              <span id="totalAmount">$0</span>
              <span id="selecteId"></span>
            </div>

            <!-- Bloque de forma de pago deshabilitado inicialmente -->
            <div class="mt-4 text-center" id="paymentMethods" style="display:none;">
              <label class="form-label d-block mb-3"><strong>Seleccione la forma de pago:</strong></label>

              <div class="d-inline-flex justify-content-center gap-3">
                <div>
                  <input type="radio" class="btn-check" name="payment_method_id" id="pos" value="1" autocomplete="off" required>
                  <label class="btn btn-outline-primary" for="pos">POS</label>
                </div>
                <div>
                  <input type="radio" class="btn-check" name="payment_method_id" id="efectivo" value="2" autocomplete="off" required>
                  <label class="btn btn-outline-primary" for="efectivo">Efectivo</label>
                </div>
                <div>
                  <div style="display:none">
                    <input type="radio" class="btn-check" name="payment_method_id" id="transferencia" value="3" autocomplete="off" required>
                    <label class="btn btn-outline-primary" for="transferencia">Transferencia</label>
                  </div>
                </div>
              </div>
            </div>

            <div class="d-grid gap-2 mt-4">
                <button type="submit" class="btn btn-primary" id="payButton" disabled>Continuar</button>
            </div>

          </form>
          
          @else
          <div class="alert alert-warning" role="alert">
              <h5><i class="bi bi-exclamation-triangle"></i> No se encontraron servicios pendientes</h5>
              <p class="mb-2">No se encontraron servicios con deudas pendientes para <strong>{{ $member->first_name }} {{ $member->last_name }}</strong> (RUT: {{ $rut }}).</p>
              <p class="mb-0">Esto puede deberse a que:</p>
              <ul class="mb-0">
                  <li>Todos los servicios están al día</li>
                  <li>Los servicios pertenecen a otra organización</li>
                  <li>Hay un problema con los datos de lecturas</li>
              </ul>
          </div>
          
          @if(config('app.debug'))
          <div class="card mt-3">
              <div class="card-header bg-danger text-white">
                  <small>Debug Info Detallado (solo en desarrollo)</small>
              </div>
              <div class="card-body">
                  <small>
                      <strong>Member ID:</strong> {{ $member->id }}<br>
                      <strong>RUT:</strong> {{ $rut }}<br>
                      <strong>Org ID:</strong> {{ $org->id }}<br>
                      <strong>Services Variable:</strong> {{ $services ? 'Set' : 'null' }}<br>
                      <strong>Services Count:</strong> {{ $services ? $services->count() : 'N/A' }}
                  </small>
              </div>
          </div>
          @endif
          @endif

        </div>
      </div>
    </div>


@endsection
@section('js')

<script>
  // Función para verificar si hay servicios seleccionados y forma de pago elegida
  function updatePayButtonState() {
    const totalAmount = Array.from(document.querySelectorAll('.service-checkbox:checked'))
      .reduce((sum, cb) => sum + parseFloat(cb.getAttribute('data-total')), 0);
    const paymentSelected = document.querySelector('input[name="payment_method_id"]:checked') !== null;
    document.getElementById('totalAmount').textContent = totalAmount.toLocaleString('es-CL', { style: 'currency', currency: 'CLP' });
    document.getElementById('paymentMethods').style.display = totalAmount > 0 ? 'block' : 'none';
    document.getElementById('payButton').disabled = !(totalAmount > 0 && paymentSelected);
  }

  // Actualizar estado al cambiar servicios
  document.querySelectorAll('.service-checkbox').forEach(function(checkbox) {
    checkbox.addEventListener('change', updatePayButtonState);
  });

  // Actualizar estado al cambiar forma de pago
  document.querySelectorAll('input[name="payment_method_id"]').forEach(function(radio) {
    radio.addEventListener('change', updatePayButtonState);
  });

  // Inicializar estado al cargar la página
  document.addEventListener('DOMContentLoaded', updatePayButtonState);
</script>

@endsection

