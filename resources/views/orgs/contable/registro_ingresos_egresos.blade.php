@extends('layouts.nice', ['active' => 'registro-ingresos-egresos', 'title' => 'Registro de Ingresos y Egresos'])

{{-- Incluir estilos modernos del módulo contable --}}
@include('orgs.contable.partials.contable-styles')

@section('content')
<div class="contable-container">
    <!-- Breadcrumbs modernos -->
    <nav class="contable-breadcrumb">
  <a href="{{ route('orgs.dashboard', ['id' => $orgId ?? 864]) }}" class="contable-breadcrumb-item">
            <i class="bi bi-house-door"></i> Inicio
        </a>
        <span class="contable-breadcrumb-separator">
            <i class="bi bi-chevron-right"></i>
        </span>
        <a href="#" class="contable-breadcrumb-item">Contable</a>
        <span class="contable-breadcrumb-separator">
            <i class="bi bi-chevron-right"></i>
        </span>
        <span class="contable-breadcrumb-item active">Registro de Ingresos y Egresos</span>
    </nav>

    <!-- Sección principal -->
    <div class="contable-section">
        <div class="contable-header">
            <div>
                <h1 class="contable-title">
                    <i class="bi bi-journal-text"></i>
                    Registro de Ingresos y Egresos
                </h1>
                <p class="contable-subtitle">Registre y gestione los movimientos de entrada y salida de dinero</p>
            </div>
        </div>

        <div class="contable-body">
            <!-- Alerta informativa -->
            <div class="contable-alert contable-alert-info contable-mb-xl">
                <i class="bi bi-info-circle"></i>
                <div>
                    <p><strong>Seleccione el tipo de registro:</strong> Elija entre registro de ingresos (entradas de dinero) o egresos (salidas de dinero) para continuar.</p>
                </div>
            </div>
            
            <!-- Botones de selección modernizados -->
            <div class="contable-text-center">
                <div style="display: flex; gap: var(--spacing-xl); justify-content: center; flex-wrap: wrap;">
                    <button id="ingresosBtn" class="contable-btn contable-btn-success contable-btn-lg" style="min-width: 250px;">
                        <i class="bi bi-arrow-up-circle"></i> 
                        Registro de Ingresos
                    </button>
                    <button id="egresosBtn" class="contable-btn contable-btn-danger contable-btn-lg" style="min-width: 250px;">
                        <i class="bi bi-arrow-down-circle"></i> 
                        Registro de Egresos
                    </button>
                </div>
                
                <!-- Cards informativos -->
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: var(--spacing-xl); margin-top: var(--spacing-2xl);">
                    <div class="contable-card">
                        <div class="contable-card-body contable-text-center">
                            <div style="color: var(--success-color); font-size: 3rem; margin-bottom: var(--spacing-md);">
                                <i class="bi bi-cash-coin"></i>
                            </div>
                            <h3 class="contable-card-title" style="color: var(--success-color);">Ingresos</h3>
                            <p class="contable-text-secondary">Registre entradas de dinero como pagos de clientes, cuotas, ventas y otros ingresos.</p>
                        </div>
                    </div>
                    
                    <div class="contable-card">
                        <div class="contable-card-body contable-text-center">
                            <div style="color: var(--danger-color); font-size: 3rem; margin-bottom: var(--spacing-md);">
                                <i class="bi bi-credit-card"></i>
                            </div>
                            <h3 class="contable-card-title" style="color: var(--danger-color);">Egresos</h3>
                            <p class="contable-text-secondary">Registre salidas de dinero como gastos operacionales, compras, servicios y otros pagos.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Efectos adicionales para los botones de selección */
    #ingresosBtn:hover {
        box-shadow: 0 8px 25px rgba(72, 187, 120, 0.4) !important;
    }
    
    #egresosBtn:hover {
        box-shadow: 0 8px 25px rgba(229, 62, 62, 0.4) !important;
    }
    .notification {
      position: fixed;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      z-index: 9999;
      min-width: 320px;
      max-width: 90vw;
      padding: 20px 40px;
      background: #222;
      color: #fff;
      border-radius: 10px;
      font-size: 1.2rem;
      text-align: center;
      box-shadow: 0 4px 24px rgba(0,0,0,0.25);
      display: none;
      opacity: 0.97;
      transition: opacity 0.2s;
    }
    .notification.success { background: #28a745; color: #fff; }
    .notification.error { background: #dc3545; color: #fff; }

    /* Modal styles */
    .modal {
      display: none;
      position: fixed;
      z-index: 1000;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0, 0, 0, 0.7);
      backdrop-filter: blur(5px);
    }

    .modal.show {
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .modal-content {
      background: #fff;
      border-radius: 12px;
      width: 90%;
      max-width: 800px;
      max-height: 90vh;
      overflow-y: auto;
      box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
      animation: modalSlideIn 0.3s ease-out;
    }

    @keyframes modalSlideIn {
      from {
        opacity: 0;
        transform: scale(0.9) translateY(-20px);
      }
      to {
        opacity: 1;
        transform: scale(1) translateY(0);
      }
    }

    .modal-header {
      background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
      color: white;
      padding: 20px 30px;
      border-radius: 12px 12px 0 0;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .modal-header h2 {
      margin: 0;
      color: #fff;
      font-size: 1.5rem;
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .modal-close {
      background: #dc3545;
      color: #fff;
      border: none;
      border-radius: 4px;
      padding: 6px 12px;
      cursor: pointer;
    }

    .modal-close:hover {
      background: #c82333;
    }

    .modal form {
      padding: 30px;
    }

    .modal label {
      display: block;
      margin-bottom: 8px;
      margin-top: 16px;
      font-weight: 600;
      color: #333;
    }

    .modal input,
    .modal select,
    .modal textarea {
      width: 100%;
      padding: 12px;
      border: 2px solid #e1e5e9;
      border-radius: 8px;
      font-size: 14px;
      transition: border-color 0.3s ease;
    }

    .modal input:focus,
    .modal select:focus,
    .modal textarea:focus {
      border-color: #28a745;
      outline: none;
      box-shadow: 0 0 0 3px rgba(40, 167, 69, 0.1);
    }

    .submit-btn {
      background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
      color: white;
      border: none;
      padding: 12px 24px;
      border-radius: 8px;
      font-size: 16px;
      font-weight: 600;
      cursor: pointer;
      transition: transform 0.2s ease;
      display: flex;
      align-items: center;
      gap: 8px;
      justify-content: center;
    }

    .submit-btn:hover {
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
    }

    /* Estilos específicos para modal de egresos */
    .modal-header-egresos {
      background: linear-gradient(135deg, #dc3545 0%, #c82333 100%) !important;
    }

    .submit-btn-egresos {
      background: linear-gradient(135deg, #dc3545 0%, #c82333 100%) !important;
    }

    .submit-btn-egresos:hover {
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(220, 53, 69, 0.3) !important;
    }

    #egresosModal .modal input:focus,
    #egresosModal .modal select:focus,
    #egresosModal .modal textarea:focus {
      border-color: #dc3545 !important;
      box-shadow: 0 0 0 3px rgba(220, 53, 69, 0.1) !important;
    }

    @media (max-width: 768px) {
      .modal-content {
        width: 95%;
        margin: 20px;
      }
      
      .modal form {
        padding: 20px;
      }
      
      .modal form > div[style*="grid"] {
        grid-template-columns: 1fr !important;
      }
    }
</style>

<!-- Modal de Ingresos -->
<div id="ingresosModal" class="modal">
  <div class="modal-content">
    <div class="modal-header">
      <h2><i class="bi bi-cash-coin"></i> Registro de Ingresos</h2>
      <button class="modal-close" id="closeIngresosModal">Cerrar</button>
    </div>

    <form id="ingresosForm">
      <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
        <!-- Columna 1 -->
        <div>
          <label for="fecha-ingresos">Fecha</label>
          <input type="date" id="fecha-ingresos" name="fecha" required style="width: 100%;">

          <label for="nro-dcto-ingresos">N° Comprobante</label>
          <input type="text" id="nro-dcto-ingresos" name="nro_dcto" placeholder="Ej: ING-0001" required style="width: 100%;">

          <label for="categoria-ingresos">Categoría de Ingreso</label>
          <select id="categoria-ingresos" name="categoria" required style="width: 100%;">
            <option value="">-- Selecciona una categoría --</option>
            @foreach($categoriasIngresos as $categoria)
              <option value="{{ $categoria->id }}">{{ $categoria->nombre }}</option>
            @endforeach
          </select>

          <label for="cuenta-destino">Cuenta Destino</label>
          <select id="cuenta-destino" name="cuenta_destino" required style="width: 450px; max-width: 100%;">
            <option value="">-- Selecciona una cuenta --</option>
            @foreach($cuentas as $cuenta)
                <option value="{{ $cuenta->id }}">
                  {{ $cuenta->tipo }} - {{ $cuenta->nombre_banco }} - {{ $cuenta->numero_cuenta }}
                </option>
            @endforeach
          </select>
        </div>

        <!-- Columna 2 -->
        <div>
          <label for="descripcion-ingresos">Descripción</label>
          <textarea id="descripcion-ingresos" name="descripcion" required
                    style="width: 100%; padding: 14px; min-height: 100px; border: 1px solid #ddd; border-radius: 8px; resize: vertical;"></textarea>

          <label for="monto-ingresos">Monto</label>
          <input type="number" id="monto-ingresos" name="monto" step="0.01" required class="monto-input" style="width: 100%;">
        </div>
      </div>

      <div style="grid-column: span 2; margin-top: 20px; display: flex; gap: 15px;">
        <button type="submit" class="submit-btn" style="flex: 1;">
          <i class="bi bi-save"></i> Registrar Ingreso
        </button>
      </div>
    </form>
  </div>
</div>

<!-- Modal de Egresos -->
<div id="egresosModal" class="modal">
  <div class="modal-content">
    <div class="modal-header modal-header-egresos">
      <h2><i class="bi bi-credit-card"></i> Registro de Egresos</h2>
      <button class="modal-close" id="closeEgresosModal">Cerrar</button>
    </div>
    <form id="egresosForm">
      <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
        <!-- Columna 1 -->
        <div>
          <label for="fecha-egresos">Fecha</label>
          <input type="date" id="fecha-egresos" name="fecha" required style="width: 100%;">

          <label for="nro-dcto-egresos">N° Boleta/Factura</label>
          <input type="number" id="nro-dcto-egresos" name="nro_dcto" placeholder="Solo números" required style="width: 100%;" min="1" step="1" pattern="[0-9]+">

          <label for="categoria-egresos">Categoría de Egreso</label>
          <select id="categoria-egresos" name="categoria" required style="width: 100%;">
            <option value="">-- Selecciona una categoría --</option>
            @foreach($categoriasEgresos as $categoria)
              <option value="{{ $categoria->id }}">{{ $categoria->nombre }}</option>
            @endforeach
          </select>

          <label for="cuenta-origen">Cuenta Origen</label>
          <select id="cuenta-origen" name="cuenta_origen" required style="width: 450px; max-width: 100%;">
            <option value="">-- Selecciona una cuenta --</option>
            @foreach($cuentas as $cuenta)
                <option value="{{ $cuenta->id }}">
                  {{ $cuenta->tipo }} - {{ $cuenta->nombre_banco }} - {{ $cuenta->numero_cuenta }}
                </option>
            @endforeach
          </select>
        </div>

        <!-- Columna 2 -->
        <div>
          <label for="proveedor">Razón Social Proveedor</label>
          <input type="text" id="razon_social" name="razon_social" placeholder="Razón Social" required style="width: 100%;">

          <label for="domicilio">R.U.T.</label>
          <input type="text" id="rut" name="rut_proveedor" placeholder="Ej: 12345678-9" style="width: 100%;" pattern="^\d{7,8}-[\dkK]{1}$" title="Formato: 12345678-9">

          <label for="descripcion-egresos">Descripción</label>
            <textarea id="descripcion-egresos" name="descripcion" required
                      style="width: 100%; padding: 14px; min-height: 100px; border: 1px solid #ddd; border-radius: 8px; resize: vertical;"></textarea>

          <label for="monto-egresos">Monto</label>
          <input type="number" id="monto-egresos" name="monto" step="0.01" required class="monto-input" style="width: 100%;">
        </div>
      </div>

      <div style="grid-column: span 2; margin-top: 20px; display: flex; gap: 15px;">
        <button type="submit" class="submit-btn submit-btn-egresos" style="flex: 1;">
          <i class="bi bi-save"></i> Registrar Egreso
        </button>
      </div>
    </form>
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Variables globales
    let comprobanteCounter = parseInt(localStorage.getItem('comprobanteCounter')) || 1;

    // Funciones auxiliares
    function generarNumeroComprobante(tipo) {
      const prefijo = tipo === 'ingreso' ? 'ING-' : 'EGR-';
      const numero = prefijo + comprobanteCounter.toString().padStart(4, '0');
      comprobanteCounter++;
      localStorage.setItem('comprobanteCounter', comprobanteCounter.toString());
      return numero;
    }

    function obtenerFechaActual() {
      return new Date().toISOString().split('T')[0];
    }

    function showNotification(message, type = 'success') {
      const notification = document.createElement('div');
      notification.className = `notification ${type}`;
      notification.textContent = message;
      document.body.appendChild(notification);
      
      notification.style.display = 'block';
      
      setTimeout(() => {
        notification.style.opacity = '0';
        setTimeout(() => {
          document.body.removeChild(notification);
        }, 200);
      }, 3000);
    }

    // Event listeners para botones principales
    document.getElementById('ingresosBtn').addEventListener('click', () => {
      document.getElementById('ingresosModal').classList.add('show');
      const nroDctoInput = document.getElementById('nro-dcto-ingresos');
      if (nroDctoInput) {
        nroDctoInput.value = generarNumeroComprobante('ingreso');
        nroDctoInput.readOnly = true;
      }
      const fechaInput = document.getElementById('fecha-ingresos');
      if (fechaInput) {
        fechaInput.value = obtenerFechaActual();
        fechaInput.readOnly = true;
      }
    });

    document.getElementById('egresosBtn').addEventListener('click', () => {
      document.getElementById('egresosModal').classList.add('show');
      const nroDctoInput = document.getElementById('nro-dcto-egresos');
      if (nroDctoInput) {
        nroDctoInput.value = ''; // Limpiar el campo
        nroDctoInput.readOnly = false; // Permitir edición manual
        nroDctoInput.focus(); // Enfocar el campo para facilitar la entrada
      }
      const fechaInput = document.getElementById('fecha-egresos');
      if (fechaInput) {
        fechaInput.value = obtenerFechaActual();
        fechaInput.readOnly = true;
      }
    });

    // Event listeners para cierre de modales
    document.getElementById('closeIngresosModal').addEventListener('click', () => {
      document.getElementById('ingresosModal').classList.remove('show');
      document.getElementById('ingresosForm').reset();
    });

    document.getElementById('closeEgresosModal').addEventListener('click', () => {
      document.getElementById('egresosModal').classList.remove('show');
      document.getElementById('egresosForm').reset();
    });

    // Event listeners para formularios
    document.getElementById('ingresosForm').addEventListener('submit', function(e) {
      e.preventDefault();
      
      const formData = new FormData(this);
      const data = {
        org_id: {{ $orgId }},
        fecha: formData.get('fecha'),
        numero_comprobante: formData.get('nro_dcto'),
        categoria: formData.get('categoria'),
        cuenta_destino: formData.get('cuenta_destino'),
        descripcion: formData.get('descripcion'),
        monto: parseFloat(formData.get('monto'))
      };
      
      // Validar datos
      if (!data.fecha || !data.numero_comprobante || !data.categoria || !data.cuenta_destino || !data.descripcion || !data.monto) {
        showNotification('Por favor complete todos los campos requeridos', 'error');
        return;
      }

      if (data.monto <= 0) {
        showNotification('El monto debe ser mayor a 0', 'error');
        return;
      }

      // Enviar al servidor
      fetch('{{ route("procesar_ingreso") }}', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify(data)
      })
      .then(response => response.json())
      .then(result => {
        if (result.success) {
          showNotification(
            `✅ ${result.message}\n💰 Nuevo saldo de ${result.data.cuenta_nombre}: $${result.data.nuevo_saldo.toLocaleString()}`,
            'success'
          );
          
          // Limpiar formulario y cerrar modal
          document.getElementById('ingresosForm').reset();
          document.getElementById('ingresosModal').classList.remove('show');
          
          // Recargar página para mostrar cambios
          setTimeout(() => {
            window.location.reload();
          }, 2000);
        } else {
          showNotification('❌ Error: ' + result.message, 'error');
        }
      })
      .catch(error => {
        console.error('Error:', error);
        showNotification('❌ Error de conexión: ' + error.message, 'error');
      });
    });

    let egresoEnviado = false;
    document.getElementById('egresosForm').addEventListener('submit', function(e) {
      e.preventDefault();
      if (egresoEnviado) return;
      egresoEnviado = true;

      const formData = new FormData(this);
      const data = {
        org_id: {{ $orgId }},
        fecha: formData.get('fecha'),
        numero_comprobante: formData.get('nro_dcto'),
        categoria: formData.get('categoria'),
        cuenta_origen: formData.get('cuenta_origen'),
        descripcion: formData.get('descripcion'),
        monto: parseFloat(formData.get('monto')),
        razon_social: formData.get('razon_social'),
        rut_proveedor: formData.get('rut_proveedor')
      };

      // Validar datos
      if (!data.fecha || !data.numero_comprobante || !data.categoria || !data.cuenta_origen || !data.descripcion || !data.monto) {
        showNotification('Por favor complete todos los campos requeridos', 'error');
        egresoEnviado = false;
        return;
      }

      if (data.monto <= 0) {
        showNotification('El monto debe ser mayor a 0', 'error');
        egresoEnviado = false;
        return;
      }

      // Enviar al servidor
      fetch('{{ route("procesar_egreso") }}', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify(data)
      })
      .then(response => response.json())
      .then(result => {
        egresoEnviado = false;
        if (result.success) {
          showNotification(
            `✅ ${result.message}\n💰 Nuevo saldo de ${result.data.cuenta_nombre}: $${result.data.nuevo_saldo.toLocaleString()}`,
            'success'
          );
          // Limpiar formulario y cerrar modal
          document.getElementById('egresosForm').reset();
          document.getElementById('egresosModal').classList.remove('show');
          // Recargar página para mostrar cambios
          setTimeout(() => {
            window.location.reload();
          }, 2000);
        } else {
          showNotification('❌ Error: ' + result.message, 'error');
        }
      })
      .catch(error => {
        egresoEnviado = false;
        console.error('Error:', error);
        showNotification('❌ Error de conexión: ' + error.message, 'error');
      });
    });

    // Cerrar modal al hacer clic fuera
    window.addEventListener('click', function(e) {
      const ingresosModal = document.getElementById('ingresosModal');
      const egresosModal = document.getElementById('egresosModal');
      
      if (e.target === ingresosModal) {
        ingresosModal.classList.remove('show');
      }
      if (e.target === egresosModal) {
        egresosModal.classList.remove('show');
      }
    });

    // Función para verificar saldo de la cuenta
    function verificarSaldoCuenta(cuentaId, monto) {
      if (!cuentaId || !monto || monto <= 0) return;
      
      fetch('{{ route("verificar_saldo_cuenta") }}', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
          cuenta_id: cuentaId,
          monto: parseFloat(monto)
        })
      })
      .then(response => response.json())
      .then(data => {
        if (data.success && data.saldo_insuficiente) {
          showNotification(
            `⚠️ SALDO INSUFICIENTE: La cuenta "${data.cuenta_nombre}" tiene un saldo de $${data.saldo_actual.toLocaleString()}. ` +
            `El egreso de $${data.monto_egreso.toLocaleString()} excede el saldo disponible. ` +
            `Saldo resultante sería: $${data.saldo_resultante.toLocaleString()}`, 
            'warning'
          );
        }
      })
      .catch(error => {
        console.error('Error al verificar saldo:', error);
      });
    }

    // Event listeners para verificación de saldo en modal de egresos
    const cuentaOrigenSelect = document.getElementById('cuenta-origen');
    const montoEgresosInput = document.getElementById('monto-egresos');

    function validarSaldoEgreso() {
      const cuentaId = cuentaOrigenSelect.value;
      const monto = montoEgresosInput.value;
      
      if (cuentaId && monto) {
        verificarSaldoCuenta(cuentaId, monto);
      }
    }

    // Verificar saldo cuando cambia la cuenta o el monto
    cuentaOrigenSelect?.addEventListener('change', validarSaldoEgreso);
    montoEgresosInput?.addEventListener('blur', validarSaldoEgreso);
    montoEgresosInput?.addEventListener('input', function() {
      // Agregar un pequeño delay para evitar múltiples llamadas
      clearTimeout(this.saldoTimer);
      this.saldoTimer = setTimeout(validarSaldoEgreso, 500);
    });
  });
</script>

@endsection
