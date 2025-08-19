@extends('layouts.nice', ['active'=>'orgs.payments.create','title'=>'Crear Pago'])

@section('content')
    <div class="pagetitle">
      <h1>{{$org->name}}</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="/">Home</a></li>
          <li class="breadcrumb-item"><a href="{{route('orgs.index')}}">Organizaciones</a></li>
          <li class="breadcrumb-item"><a href="{{route('orgs.dashboard',$org->id)}}">{{$org->name}}</a></li>
          <li class="breadcrumb-item"><a href="{{route('orgs.payments.index',$org->id)}}">Pagos</a></li>
          <li class="breadcrumb-item active">Crear Pago</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="card">
            <div class="card-body">
                    <form id="newMemberForm" action="" class="m-3" method="POST">
                        @csrf
                        @php
                            // Obtener cuentas por tipo
                            $cuentaCorriente = \App\Models\Cuenta::porOrganizacion($org->id)->porTipo('corriente')->first();
                            $cajaGeneral = \App\Models\Cuenta::porOrganizacion($org->id)->porTipo('caja')->first();
                        @endphp
                        <div class="row">
                            <!-- Columna izquierda - Datos personales -->
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="description" class="form-label">Descripción del Artículo</label>
                                    <input type="text" class="form-control" id="description" name="description" required>
                                </div>
                            </div>
                            <div class="col-md-4">    
                                <div class="mb-3">
                                    <label for="qxt" class="form-label">Cantidad</label>
                                    <input type="number" class="form-control" id="qxt" name="qxt" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="order_date" class="form-label">Fecha último Pedido</label>
                                    <input type="date" class="form-control" id="order_date" name="order_date" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="amount" class="form-label">Valor</label>
                                    <input type="number" class="form-control" id="amount" name="amount" required>
                                </div>
                            </div>
                            <div class="col-md-4">                                
                                <div class="mb-3">
                                    <label for="payment_method_id" class="form-label">Método de Pago</label>
                                    <select class="form-select" id="payment_method_id" name="payment_method_id" onchange="asignarCuentaDestino()">
                                        <option value="1">POS</option>
                                        <option value="2">Efectivo</option>
                                        <option value="3">Transferencia</option>
                                    </select>
                                </div>
                                <input type="hidden" id="cuenta_destino" name="cuenta_destino">
                                <div class="mb-3">
                                    <label class="form-label">Cuenta destino</label>
                                    <input type="text" class="form-control" id="cuenta_destino_nombre" readonly>
                                </div>
                                <script>
                                    function asignarCuentaDestino() {
                                        var metodo = document.getElementById('payment_method_id').value;
                                        var cuentaDestinoInput = document.getElementById('cuenta_destino');
                                        var cuentaDestinoNombre = document.getElementById('cuenta_destino_nombre');
                                        if (metodo == '1') { // POS
                                            cuentaDestinoInput.value = '{{ $cuentaCorriente ? $cuentaCorriente->id : '' }}';
                                            cuentaDestinoNombre.value = '{{ $cuentaCorriente ? $cuentaCorriente->nombre : 'No disponible' }}';
                                        } else if (metodo == '2') { // Efectivo
                                            cuentaDestinoInput.value = '{{ $cajaGeneral ? $cajaGeneral->id : '' }}';
                                            cuentaDestinoNombre.value = '{{ $cajaGeneral ? $cajaGeneral->nombre : 'No disponible' }}';
                                        } else {
                                            cuentaDestinoInput.value = '';
                                            cuentaDestinoNombre.value = '';
                                        }
                                    }
                                    document.addEventListener('DOMContentLoaded', asignarCuentaDestino);
                                    document.getElementById('newMemberForm').addEventListener('submit', function(e) {
                                        asignarCuentaDestino(); // Asegura que el campo esté actualizado justo antes de enviar
                                    });
                                </script>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="location" class="form-label">Ubicación</label>
                                    <input type="text" class="form-control" id="location" name="location">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="responsible" class="form-label">Nombre del responsable</label>
                                    <input type="text" class="form-control" id="responsible" name="responsible">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="low_date" class="form-label">Fecha de Traslado o Baja</label>
                                    <input type="date" class="form-control" id="low_date" name="low_date">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="observations" class="form-label">Observaciones (Opcional)</label>
                                    <textarea class="form-control" id="observations" name="observations" rows="3"></textarea>
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-grid gap-2 mt-4">
                            <button type="submit" class="btn btn-primary">Añadir Inventario</button>
                        </div>
                    </form>
            </div>
          </div>
    </section>
@endsection