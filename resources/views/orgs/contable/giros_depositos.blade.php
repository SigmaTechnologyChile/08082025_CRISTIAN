<!DOCTYPE html>
@extends('layouts.nice', ['active' => 'giros_depositos'])

@section('title', 'Giros y Depósitos')

{{-- Incluir estilos modernos del módulo contable --}}
@include('orgs.contable.partials.contable-styles')

<!-- Bootstrap Icons -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">

<!-- CSRF Token -->
<meta name="csrf-token" content="{{ csrf_token() }}">

<style>
/* Variables basadas en balance.blade.php */
:root {
  --primary-color: #2c5282;
  --secondary-color: #4CAF50;
  --danger-color: #e53e3e;
  --success-color: #48bb78;
  --warning-color: #dd6b20;
  --info-color: #3182ce;
  --light-bg: #f8f9fa;
  --dark-text: #2d3748;
  --border-color: #e2e8f0;
  --shadow-card: 0 4px 12px rgba(0, 0, 0, 0.08);
  --shadow-hover: 0 8px 20px rgba(0, 0, 0, 0.12);
}

/* Contenedor principal estilo balance */
.balance-section {
  padding: 20px;
  max-width: 1400px;
  margin: 0 auto;
  background: var(--light-bg);
}

/* Header de la sección estilo balance */
.section-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin-bottom: 30px;
  background: white;
  padding: 25px;
  border-radius: 15px;
  box-shadow: var(--shadow-card);
}

.section-header h1 {
  color: var(--primary-color);
  font-size: 2.5rem;
  margin: 0;
  font-weight: 700;
  display: flex;
  align-items: center;
  gap: 15px;
}

.section-header p {
  color: #718096;
  font-size: 1.1rem;
  margin: 0;
  margin-top: 5px;
}

/* Tarjetas de formulario estilo balance */
.form-card-balance {
  background: white;
  border-radius: 15px;
  overflow: hidden;
  box-shadow: var(--shadow-card);
  transition: transform 0.3s ease, box-shadow 0.3s ease;
  border-top: 5px solid var(--primary-color);
}

.form-card-balance:hover {
  transform: translateY(-3px);
  box-shadow: var(--shadow-hover);
}

.form-card-balance.giros {
  border-top-color: var(--info-color);
}

.form-card-balance.depositos {
  border-top-color: var(--secondary-color);
}

/* Headers de las tarjetas estilo balance */
.form-card-header-balance {
  background: linear-gradient(135deg, var(--primary-color) 0%, #1a365d 100%);
  color: white;
  padding: 20px 25px;
  position: relative;
  overflow: hidden;
}

.form-card-header-balance.giros {
  background: linear-gradient(135deg, var(--info-color) 0%, #2c5282 100%);
}

.form-card-header-balance.depositos {
  background: linear-gradient(135deg, var(--secondary-color) 0%, #388e3c 100%);
}

.form-card-header-balance::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Ccircle cx='30' cy='30' r='2'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E") repeat;
  pointer-events: none;
}

.form-card-header-balance h3 {
  font-size: 1.3rem;
  font-weight: 700;
  display: flex;
  align-items: center;
  gap: 12px;
  margin: 0;
  position: relative;
  z-index: 1;
}

.form-card-header-balance p {
  margin: 8px 0 0 0;
  opacity: 0.9;
  font-size: 0.95rem;
  position: relative;
  z-index: 1;
}

/* Cuerpo de las tarjetas estilo balance */
.form-card-body-balance {
  padding: 25px;
  background: #fafbfc;
}

/* Grid de formularios estilo balance */
.form-grid-balance {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
  gap: 20px;
}

.form-grid-balance-2 {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
  gap: 30px;
  margin-bottom: 30px;
}

/* Grupos de formulario estilo balance */
.form-group-balance {
  margin-bottom: 20px;
}

.form-label-balance {
  display: flex;
  align-items: center;
  gap: 8px;
  margin-bottom: 8px;
  font-weight: 600;
  color: var(--dark-text);
  font-size: 0.95rem;
}

/* Inputs estilo balance */
.form-input-balance,
.form-select-balance {
  width: 100%;
  padding: 15px 18px;
  border: 2px solid var(--border-color);
  border-radius: 12px;
  font-size: 16px;
  color: var(--dark-text);
  background-color: #f8fafc;
  transition: all 0.3s ease;
  font-weight: 500;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.02);
}

.form-input-balance:focus,
.form-select-balance:focus {
  border-color: var(--primary-color);
  background-color: #fff;
  box-shadow: 0 0 0 4px rgba(44, 82, 130, 0.15);
  outline: none;
  transform: translateY(-2px);
}

.form-input-balance:hover,
.form-select-balance:hover {
  background-color: #fff;
  border-color: #cbd5e0;
  transform: translateY(-1px);
}

.form-input-balance:read-only {
  background-color: #f1f5f9;
  color: #64748b;
  cursor: not-allowed;
}

/* Botones estilo balance */
.btn-balance-primary {
  background: linear-gradient(135deg, var(--primary-color) 0%, #1a365d 100%);
  color: white;
  border: none;
  border-radius: 12px;
  padding: 18px 35px;
  font-size: 18px;
  font-weight: 700;
  cursor: pointer;
  transition: all 0.3s ease;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 12px;
  text-decoration: none;
  position: relative;
  overflow: hidden;
  box-shadow: 0 4px 12px rgba(44, 82, 130, 0.3);
  width: 100%;
  max-width: 350px;
}

.btn-balance-success {
  background: linear-gradient(135deg, var(--secondary-color) 0%, #388e3c 100%);
  color: white;
  border: none;
  border-radius: 12px;
  padding: 18px 35px;
  font-size: 18px;
  font-weight: 700;
  cursor: pointer;
  transition: all 0.3s ease;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 12px;
  text-decoration: none;
  position: relative;
  overflow: hidden;
  box-shadow: 0 4px 12px rgba(76, 175, 80, 0.3);
  width: 100%;
  max-width: 350px;
}

.btn-balance-primary::before,
.btn-balance-success::before {
  content: '';
  position: absolute;
  top: 0;
  left: -100%;
  width: 100%;
  height: 100%;
  background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent);
  transition: left 0.6s;
}

.btn-balance-primary:hover,
.btn-balance-success:hover {
  transform: translateY(-3px);
  box-shadow: 0 8px 25px rgba(44, 82, 130, 0.4);
}

.btn-balance-success:hover {
  box-shadow: 0 8px 25px rgba(76, 175, 80, 0.4);
}

.btn-balance-primary:hover::before,
.btn-balance-success:hover::before {
  left: 100%;
}

/* Tabla estilo balance */
.table-card-balance {
  background: white;
  border-radius: 15px;
  overflow: hidden;
  box-shadow: var(--shadow-card);
  margin-top: 30px;
  border-top: 5px solid var(--warning-color);
}

.table-header-balance {
  background: linear-gradient(135deg, var(--warning-color) 0%, #c05621 100%);
  color: white;
  padding: 20px 25px;
  position: relative;
  overflow: hidden;
}

.table-header-balance::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Ccircle cx='30' cy='30' r='2'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E") repeat;
  pointer-events: none;
}

.table-header-balance h3 {
  font-size: 1.3rem;
  font-weight: 700;
  display: flex;
  align-items: center;
  gap: 12px;
  margin: 0;
  position: relative;
  z-index: 1;
}

.table-container-balance {
  padding: 25px;
  background: #fafbfc;
  overflow-x: auto;
}

.table-balance {
  width: 100%;
  border-collapse: collapse;
  background: white;
  border-radius: 12px;
  overflow: hidden;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
}

.table-balance th {
  background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
  color: var(--dark-text);
  font-weight: 600;
  padding: 15px 20px;
  text-align: left;
  border-bottom: 2px solid var(--border-color);
  font-size: 0.95rem;
}

.table-balance td {
  padding: 15px 20px;
  border-bottom: 1px solid #f1f5f9;
  color: var(--dark-text);
  font-weight: 500;
}

.table-balance tbody tr:hover {
  background: #f8fafc;
}

.table-balance .text-center {
  text-align: center;
}

.table-balance .text-secondary {
  color: #718096;
}

/* Acciones del formulario estilo balance */
.form-actions-balance {
  text-align: center;
  padding-top: 30px;
  border-top: 2px solid var(--border-color);
  margin-top: 30px;
}

/* Notificaciones estilo balance */
.notification-balance {
  position: fixed;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  z-index: 9999;
  min-width: 350px;
  max-width: 90vw;
  padding: 20px 25px;
  background: var(--success-color);
  color: white;
  border-radius: 12px;
  font-size: 1.1rem;
  text-align: center;
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
  opacity: 0.97;
  transition: all 0.3s ease;
  display: none;
  align-items: center;
  justify-content: center;
  gap: 12px;
}

.notification-balance.error {
  background: var(--danger-color);
}

.notification-balance.success {
  background: var(--success-color);
}

/* Responsivo */
@media (max-width: 768px) {
  .balance-section {
    padding: 15px;
  }

  .section-header {
    padding: 20px;
    flex-direction: column;
    text-align: center;
    gap: 10px;
  }

  .section-header h1 {
    font-size: 2rem;
    flex-direction: column;
    gap: 10px;
  }

  .form-grid-balance-2 {
    grid-template-columns: 1fr;
    gap: 20px;
  }

  .form-card-header-balance,
  .form-card-body-balance {
    padding: 15px 20px;
  }

  .form-grid-balance {
    grid-template-columns: 1fr;
    gap: 15px;
  }

  .btn-balance-primary,
  .btn-balance-success {
    font-size: 16px;
    padding: 15px 25px;
  }

  .table-container-balance {
    padding: 15px;
  }
}

@media (max-width: 480px) {
  .section-header h1 {
    font-size: 1.75rem;
  }

  .form-card-body-balance {
    padding: 15px;
  }

  .form-input-balance,
  .form-select-balance {
    padding: 12px 15px;
  }
}
</style>

@section('content')
{{-- Módulo de movimientos habilitado --}}
<div class="contable-container">
  <div class="balance-section">
    <!-- Header de la sección estilo balance -->
    <div class="section-header">
      <div>
        <h1>
          <i class="bi bi-arrow-left-right"></i>
          Giros y Depósitos
        </h1>
                    $ {{ number_format((float)$movimientos->sum('monto'), 0, ',', '.') }}
      </div>
    </div>

    <!-- Formularios de Giros y Depósitos -->
    <div class="form-grid-balance-2">
      <!-- Sección Giros -->
      <div class="form-card-balance giros">
        <div class="form-card-header-balance giros">
          <h3>
            <i class="bi bi-send"></i> GIROS
          </h3>
          <p>Desde Cta. Cte./ Cta. Ahorro → Caja General</p>
        </div>
        <div class="form-card-body-balance">
          <form id="girosForm">
            <input type="hidden" name="grupo" value="Giros">
            <div class="form-grid-balance">
              <div class="form-group-balance">
                <label for="fecha-giro" class="form-label-balance">
                  <i class="bi bi-calendar3"></i>
                  Fecha
                </label>
                <input type="date" id="fecha-giro" name="fecha" required class="form-input-balance">
              </div>
              
              <div class="form-group-balance">
                <label for="monto-giro" class="form-label-balance">
                  <i class="bi bi-currency-dollar"></i>
                  Monto
                </label>
                <input type="number" id="monto-giro" name="monto" step="0.01" required class="form-input-balance">
              </div>
              
              <div class="form-group-balance">
                <label for="cuenta-giro" class="form-label-balance">
                  <i class="bi bi-bank"></i>
                  Cuenta Origen
                </label>
                <select id="cuenta-giro" name="cuenta_origen" required class="form-select-balance">
                  <option value="">-- Seleccione cuenta origen --</option>
                  @foreach($cuentas as $cuenta)
                    @if($cuenta->tipo !== 'caja_general')
                      <option value="{{ $cuenta->id }}">{{ $cuenta->tipo }}</option>
                    @endif
                  @endforeach
                </select>
              </div>
              
              <div class="form-group-balance">
                <label for="detalle-giro" class="form-label-balance">
                  <i class="bi bi-file-text"></i>
                  Descripción
                </label>
                <input type="text" id="detalle-giro" name="descripcion" placeholder="Descripción del giro" required class="form-input-balance">
              </div>
              
              <div class="form-group-balance">
                <label for="nro-dcto-giro" class="form-label-balance">
                  <i class="bi bi-receipt"></i>
                  N° Comprobante
                </label>
                <input type="number" id="nro-dcto-giro" name="nro_dcto" required class="form-input-balance" placeholder="Solo número de comprobante" min="1" step="1">
              </div>
            </div>

            <div class="form-actions-balance">
              <button type="submit" class="btn-balance-primary">
                <i class="bi bi-save"></i> Registrar Giro
              </button>
            </div>
          </form>
        </div>
      </div>

      <!-- Sección Depósitos -->
      <div class="form-card-balance depositos">
        <div class="form-card-header-balance depositos">
          <h3>
            <i class="bi bi-bank"></i> DEPÓSITOS
          </h3>
          <p>Desde Caja General → Cta. Cte./ Cta. Ahorro</p>
        </div>
        <div class="form-card-body-balance">
          <form id="depositosForm">
            <input type="hidden" name="grupo" value="Depósitos">
            <div class="form-grid-balance">
              <div class="form-group-balance">
                <label for="fecha-deposito" class="form-label-balance">
                  <i class="bi bi-calendar3"></i>
                  Fecha
                </label>
                <input type="date" id="fecha-deposito" name="fecha" required class="form-input-balance">
              </div>
              
              <div class="form-group-balance">
                <label for="monto-deposito" class="form-label-balance">
                  <i class="bi bi-currency-dollar"></i>
                  Monto
                </label>
                <input type="number" id="monto-deposito" name="monto" step="0.01" required class="form-input-balance">
              </div>
              
              <div class="form-group-balance">
                <label for="cuenta-deposito" class="form-label-balance">
                  <i class="bi bi-bank"></i>
                  Cuenta Destino
                </label>
                <select id="cuenta-deposito" name="cuenta_destino" required class="form-select-balance">
                  <option value="">-- Seleccione cuenta destino --</option>
                  @foreach($cuentas as $cuenta)
                    @if($cuenta->tipo !== 'caja_general')
                      <option value="{{ $cuenta->id }}">{{ $cuenta->tipo }}</option>
                    @endif
                  @endforeach
                </select>
              </div>
              
              <div class="form-group-balance">
                <label for="detalle-deposito" class="form-label-balance">
                  <i class="bi bi-file-text"></i>
                  Descripción
                </label>
                <input type="text" id="detalle-deposito" name="descripcion" placeholder="Descripción del depósito" required class="form-input-balance">
              </div>
              
              <div class="form-group-balance">
                <label for="nro-dcto-deposito" class="form-label-balance">
                  <i class="bi bi-receipt"></i>
                  N° Comprobante
                </label>
                <input type="number" id="nro-dcto-deposito" name="nro_dcto" required class="form-input-balance" placeholder="Solo número de comprobante" min="1" step="1">
              </div>
            </div>

            <div class="form-actions-balance">
              <button type="submit" class="btn-balance-success">
                <i class="bi bi-save"></i> Registrar Depósito
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- Historial de movimientos -->
    <div class="table-card-balance">
      <div class="table-header-balance">
        <h3>
          <i class="bi bi-clock-history"></i>
          Historial de Movimientos
        </h3>
      </div>
      <div class="table-container-balance">
        <table class="table-balance">
          <thead>
            <tr>
              <th>
                <i class="bi bi-calendar3"></i>
                Fecha
              </th>
              <th>
                <i class="bi bi-arrow-left-right"></i>
                Tipo
              </th>
              <th>
                <i class="bi bi-bank"></i>
                Cuenta
              </th>
              <th>
                <i class="bi bi-currency-dollar"></i>
                Monto
              </th>
              <th>
                <i class="bi bi-file-text"></i>
                Detalle
              </th>
              <th>
                <i class="bi bi-receipt"></i>
                Comprobante
              </th>
            </tr>
          </thead>
          <tbody id="historialMovimientos">
            @forelse($movimientos as $mov)
              <tr>
                <td>{{ $mov->fecha instanceof \Illuminate\Support\Carbon ? $mov->fecha->format('d/m/Y') : date('d/m/Y', strtotime((string)$mov->fecha)) }}</td>
                <td>
                  <span class="badge {{ $mov->subtipo == 'giro' ? 'badge-info' : 'badge-success' }}">
                    {{ strtoupper($mov->subtipo) }}
                  </span>
                </td>
                <td>
                  @if($mov->subtipo == 'giro')
                    {{ $mov->cuenta_origen_id ? ($cuentas->find($mov->cuenta_origen_id)->tipo ?? '-') : '-' }}
                  @else
                    {{ $mov->cuenta_destino_id ? ($cuentas->find($mov->cuenta_destino_id)->tipo ?? '-') : '-' }}
                  @endif
                </td>
                  <td>$ {{ number_format((float)$mov->monto, 2, ',', '.') }}</td>
                <td>{{ $mov->descripcion }}</td>
                <td>
                  @if(!empty($mov->nro_dcto))
                    {{ $mov->nro_dcto }}
                  @elseif(!empty($mov->numero_documento))
                    {{ $mov->numero_documento }}
                  @else
                    -
                  @endif
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="6" class="text-center text-secondary" style="padding: 30px;">
                  <i class="bi bi-inbox" style="font-size: 2rem; display: block; margin-bottom: 15px; opacity: 0.5;"></i>
                  No hay movimientos registrados
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<!-- Notification div -->
<div id="notification" class="notification-balance"></div>


<script>
document.addEventListener('DOMContentLoaded', function() {
  // Funciones auxiliares
  function obtenerFechaActual() {
    return new Date().toISOString().split('T')[0];
  }

  function showNotification(message, type = 'success') {
        const notification = document.getElementById('notification');
        notification.innerHTML = `<i class="bi bi-${type === 'success' ? 'check-circle' : 'exclamation-triangle'}"></i> ${message}`;
        notification.className = `notification-balance ${type}`;
        notification.style.display = 'flex';
        
        setTimeout(() => {
            notification.style.display = 'none';
        }, 3000);
    }

    // Inicializar campos automáticos
    function inicializarCampos() {
        // Giros
        const fechaGiro = document.getElementById('fecha-giro');
        const nroDctoGiro = document.getElementById('nro-dcto-giro');
    if (fechaGiro) {
      fechaGiro.value = obtenerFechaActual();
      fechaGiro.readOnly = true;
    }
    // El usuario ingresa el número de comprobante manualmente
    // Depósitos
    const fechaDeposito = document.getElementById('fecha-deposito');
    if (fechaDeposito) {
      fechaDeposito.value = obtenerFechaActual();
      fechaDeposito.readOnly = true;
    }
    // El usuario ingresa el número de comprobante manualmente
    }


    // Event listeners para formularios
    document.getElementById('girosForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        
        // Validar campos requeridos
        const requiredFields = ['fecha', 'monto', 'cuenta_origen', 'descripcion', 'nro_dcto'];
        for (let field of requiredFields) {
            if (!formData.get(field)) {
                showNotification(`El campo ${field} es requerido`, 'error');
                return;
            }
        }

        if (parseFloat(formData.get('monto')) <= 0) {
            showNotification('El monto debe ser mayor a 0', 'error');
            return;
        }

        // Agregar org_id
        formData.append('org_id', {{ $orgId }});
        formData.append('numero_comprobante', formData.get('nro_dcto'));

        // Enviar al servidor
        fetch('{{ route("procesar_giro") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification(data.message, 'success');
                this.reset();
                inicializarCampos();
                // Recargar la página para actualizar saldos
                setTimeout(() => {
                    window.location.reload();
                }, 1500);
            } else {
                showNotification(data.message, 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Error al procesar el giro', 'error');
        });
    });

    document.getElementById('depositosForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        
        // Validar campos requeridos
        const requiredFields = ['fecha', 'monto', 'cuenta_destino', 'descripcion', 'nro_dcto'];
        for (let field of requiredFields) {
            if (!formData.get(field)) {
                showNotification(`El campo ${field} es requerido`, 'error');
                return;
            }
        }

        if (parseFloat(formData.get('monto')) <= 0) {
            showNotification('El monto debe ser mayor a 0', 'error');
            return;
        }

        // Agregar org_id
        formData.append('org_id', {{ $orgId }});
        formData.append('numero_comprobante', formData.get('nro_dcto'));

        // Enviar al servidor
        fetch('{{ route("procesar_deposito") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification(data.message, 'success');
                this.reset();
                inicializarCampos();
                // Recargar la página para actualizar saldos
                setTimeout(() => {
                    window.location.reload();
                }, 1500);
            } else {
                showNotification(data.message, 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Error al procesar el depósito', 'error');
        });
    });

    // Inicializar al cargar la página
    inicializarCampos();
  // cargarHistorialMovimientos(); // Eliminado: ahora la tabla usa solo datos del backend
    
    console.log('🎉 Giros y Depósitos con estilos del Balance inicializado!');
});
</script>
@endsection
