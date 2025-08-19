@extends('layouts.nice', ['active' => 'modal_cuentas_exacto'])

{{-- Incluir estilos modernos del módulo contable --}}
@include('orgs.contable.partials.contable-styles')

@section('title', 'Configuración de Cuentas Iniciales')

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
        <span class="contable-breadcrumb-item active">Configuración de Cuentas Exacto</span>
    </nav>

    <!-- Sección principal -->
    <div class="contable-section">
        <div class="contable-header">
            <div>
                <h1 class="contable-title">
                    <i class="bi bi-gear-fill"></i>
                    Configuración de Cuentas Exacto
                </h1>
                <p class="contable-subtitle">Configure las cuentas exactas para el sistema contable</p>
            </div>
        </div>

        <div class="contable-body">
            <!-- Alerta de advertencia -->
            <div class="contable-alert contable-alert-warning contable-mb-xl">
                <i class="bi bi-exclamation-triangle-fill"></i>
                <div>
                    <strong>Importante:</strong> Esta configuración debe realizarse cuidadosamente ya que afectará todos los cálculos contables del sistema.
                </div>
            </div>
      padding: 10px 15px;
      margin-bottom: 20px;
      display: flex;
      align-items: center;
    }
    
    .warning-box i {
      font-size: 24px;
      color: #ffc107;
      margin-right: 10px;
    }
    
    .form-section {
      margin-bottom: 20px;
      padding: 15px;
      border: 1px solid #eee;
      border-radius: 8px;
    }
    
    .form-grid {
      display: grid;
      grid-template-columns: 1fr 1fr 1fr;
      gap: 15px;
    }
    
    .form-group {
      margin-bottom: 15px;
    }
    
    .form-group label {
      display: block;
      margin-bottom: 5px;
      font-weight: 500;
    }
    
    .form-group input,
    .form-group select {
      width: 100%;
      padding: 8px 12px;
      border: 1px solid #ddd;
      border-radius: 4px;
      font-size: 14px;
    }
    
    .form-group input:focus,
    .form-group select:focus {
      border-color: #007bff;
      outline: none;
    }
    
    .required {
      color: #dc3545;
      font-weight: bold;
    }
    
    .button-group {
      text-align: center;
      margin-top: 20px;
    }
    
    .submit-btn {
      background: #007bff;
      color: #fff;
      border: none;
      border-radius: 4px;
      padding: 10px 20px;
      font-size: 16px;
      cursor: pointer;
    }
    
    .submit-btn:hover {
      background: #0056b3;
    }

    /* Modal styles */
    .modal {
      display: none;
      position: fixed;
      z-index: 9999;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0,0,0,0.6);
      align-items: center;
      justify-content: center;
    }

    .modal.show {
      display: flex;
    }

    .modal-content {
      max-width: 700px;
      width: 90%;
      background: #fff;
      border-radius: 8px;
      padding: 20px;
      box-shadow: 0 4px 6px rgba(0,0,0,0.1);
      position: relative;
    }

    .modal-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      border-bottom: 1px solid #eee;
      margin-bottom: 20px;
      padding-bottom: 10px;
    }

    .modal-header h2 {
      margin: 0;
      color: #333;
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

    @media (max-width: 768px) {
      .form-grid {
        grid-template-columns: 1fr;
      }
      
      .modal-content {
        width: 95%;
        padding: 15px;
      }
    }
  </style>
</head>

<body>
  <!-- Contenido principal de la página -->
  <div style="padding: 20px; text-align: center;">
    <h1>Configuración de Cuentas Iniciales</h1>
    <p>Haga clic en el botón para abrir el modal de configuración.</p>
    <button id="cuentasInicialesBtn" class="btn btn-primary">
      <i class="bi bi-journal-plus"></i> Configurar Cuentas Iniciales
    </button>
  </div>

  <!-- Modal Cuentas Iniciales -->
  <div id="cuentasInicialesModal" class="modal">
    <div class="modal-content" style="max-width: 700px;">
      <div class="modal-header">
        <h2><i class="bi bi-journal-plus"></i> Configuración de Cuentas Iniciales</h2>
        <button class="modal-close" id="closeCuentasInicialesModal">Cerrar</button>
      </div>
      
      <form id="cuentasInicialesForm">
        <div class="warning-box">
          <i class="bi bi-exclamation-triangle"></i>
          <p>¿Está seguro(a) de guardar los cambios? Esta operación solo se podrá realizar una sola vez.</p>
        </div>
        
        <div class="form-section">
          <h3>Saldo Inicial de Cuentas</h3>
          <div class="form-grid">
            <div class="form-group">
              <label for="saldo-caja-general">Saldo Caja General</label>
              <input type="number" id="saldo-caja-general" name="saldo_caja_general" step="0.01" min="0" required>
            </div>
            
            <div class="form-group">
              <label for="banco-caja-general">Banco</label>
              <select id="banco-caja-general" name="banco_caja_general">
                <option value="">Sin banco</option>
                <option value="banco_estado">Banco Estado</option>
                <option value="banco_chile">Banco de Chile</option>
                <option value="banco_bci">BCI</option>
                <option value="banco_santander">Santander</option>
                <option value="banco_itau">Itaú</option>
                <option value="banco_scotiabank">Scotiabank</option>
                <option value="banco_bice">BICE</option>
                <option value="banco_security">Security</option>
                <option value="banco_falabella">Falabella</option>
                <option value="banco_ripley">Ripley</option>
                <option value="banco_consorcio">Consorcio</option>
                <option value="otro">Otro</option>
              </select>
            </div>
            
            <div class="form-group">
              <label for="numero-caja-general">Número de Cuenta</label>
              <input type="text" id="numero-caja-general" name="numero_caja_general">
            </div>
          </div>
        </div>
        
        <div class="form-section">
          <div class="form-grid">
            <div class="form-group">
              <label for="saldo-cta-corriente-1">Saldo Cuenta Corriente 1</label>
              <input type="number" id="saldo-cta-corriente-1" name="saldo_cta_corriente_1" step="0.01" min="0" required>
            </div>
            
            <div class="form-group">
              <label for="banco-cta-corriente-1">Banco</label>
              <select id="banco-cta-corriente-1" name="banco_cta_corriente_1">
                <option value="">Sin banco</option>
                <option value="banco_estado">Banco Estado</option>
                <option value="banco_chile">Banco de Chile</option>
                <option value="banco_bci">BCI</option>
                <option value="banco_santander">Santander</option>
                <option value="banco_itau">Itaú</option>
                <option value="banco_scotiabank">Scotiabank</option>
                <option value="banco_bice">BICE</option>
                <option value="banco_security">Security</option>
                <option value="banco_falabella">Falabella</option>
                <option value="banco_ripley">Ripley</option>
                <option value="banco_consorcio">Consorcio</option>
                <option value="otro">Otro</option>
              </select>
            </div>
            
            <div class="form-group">
              <label for="numero-cta-corriente-1">Número de Cuenta</label>
              <input type="text" id="numero-cta-corriente-1" name="numero_cta_corriente_1">
            </div>
          </div>
        </div>
        
        <div class="form-section">
          <div class="form-grid">
            <div class="form-group">
              <label for="saldo-cta-corriente-2">Saldo Cuenta Corriente 2</label>
              <input type="number" id="saldo-cta-corriente-2" name="saldo_cta_corriente_2" step="0.01" min="0" required>
            </div>
            
            <div class="form-group">
              <label for="banco-cta-corriente-2">Banco</label>
              <select id="banco-cta-corriente-2" name="banco_cta_corriente_2">
                <option value="">Sin banco</option>
                <option value="banco_estado">Banco Estado</option>
                <option value="banco_chile">Banco de Chile</option>
                <option value="banco_bci">BCI</option>
                <option value="banco_santander">Santander</option>
                <option value="banco_itau">Itaú</option>
                <option value="banco_scotiabank">Scotiabank</option>
                <option value="banco_bice">BICE</option>
                <option value="banco_security">Security</option>
                <option value="banco_falabella">Falabella</option>
                <option value="banco_ripley">Ripley</option>
                <option value="banco_consorcio">Consorcio</option>
                <option value="otro">Otro</option>
              </select>
            </div>
            
            <div class="form-group">
              <label for="numero-cta-corriente-2">Número de Cuenta</label>
              <input type="text" id="numero-cta-corriente-2" name="numero_cta_corriente_2">
            </div>
          </div>
        </div>
        
        <div class="form-section">
          <div class="form-grid">
            <div class="form-group">
              <label for="saldo-cuenta-ahorro">Saldo Cuenta de Ahorro</label>
              <input type="number" id="saldo-cuenta-ahorro" name="saldo_cuenta_ahorro" step="0.01" min="0" required>
            </div>
            
            <div class="form-group">
              <label for="banco-cuenta-ahorro">Banco</label>
              <select id="banco-cuenta-ahorro" name="banco_cuenta_ahorro">
                <option value="">Sin banco</option>
                <option value="banco_estado">Banco Estado</option>
                <option value="banco_chile">Banco de Chile</option>
                <option value="banco_bci">BCI</option>
                <option value="banco_santander">Santander</option>
                <option value="banco_itau">Itaú</option>
                <option value="banco_scotiabank">Scotiabank</option>
                <option value="banco_bice">BICE</option>
                <option value="banco_security">Security</option>
                <option value="banco_falabella">Falabella</option>
                <option value="banco_ripley">Ripley</option>
                <option value="banco_consorcio">Consorcio</option>
                <option value="otro">Otro</option>
              </select>
            </div>
            
            <div class="form-group">
              <label for="numero-cuenta-ahorro">Número de Cuenta</label>
              <input type="text" id="numero-cuenta-ahorro" name="numero_cuenta_ahorro">
            </div>
          </div>
        </div>
        
        <div class="form-section">
          <div class="form-group">
            <label for="responsable">Nombre Responsable <span class="required">*</span></label>
            <input type="text" id="responsable" name="responsable" required>
          </div>
        </div>
        
        <div class="button-group" style="margin-top: 20px;">
          <button type="submit" class="submit-btn">
            <i class="bi bi-save"></i> Guardar Cuentas Iniciales
          </button>
        </div>
      </form>
    </div>
  </div>

  <!-- Notification div -->
  <div id="notification" class="notification"></div>

  <script>
    // Variables globales
    let saldosCuentas = {
      caja_general: 0,
      cuenta_corriente_1: 0,
      cuenta_corriente_2: 0,
      cuenta_ahorro: 0
    };

    let accountDetails = {
      caja_general: { banco: '', numero: '' },
      cuenta_corriente_1: { banco: '', numero: '' },
      cuenta_corriente_2: { banco: '', numero: '' },
      cuenta_ahorro: { banco: '', numero: '' }
    };

    let initialAccountsSet = false;

    // Función para mostrar notificaciones
    function mostrarNotificacion(mensaje, tipo = 'success') {
      const notification = document.getElementById('notification');
      notification.textContent = mensaje;
      notification.className = `notification ${tipo}`;
      notification.style.display = 'block';
      
      setTimeout(() => {
        notification.style.display = 'none';
      }, 3000);
    }

    // Función para guardar detalles de cuentas
    function guardarDetallesCuentas() {
      localStorage.setItem('accountDetails', JSON.stringify(accountDetails));
    }

    // Función para cargar datos guardados
    function cargarDatosGuardados() {
      const savedSaldos = localStorage.getItem('saldosCuentas');
      const savedDetails = localStorage.getItem('accountDetails');
      const savedInitial = localStorage.getItem('initialAccountsSet');

      if (savedSaldos) {
        saldosCuentas = JSON.parse(savedSaldos);
      }

      if (savedDetails) {
        accountDetails = JSON.parse(savedDetails);
      }

      if (savedInitial === 'true') {
        initialAccountsSet = true;
        // Deshabilitar formulario si ya fue configurado
        const inputs = document.querySelectorAll('#cuentasInicialesForm input, #cuentasInicialesForm select');
        inputs.forEach(input => {
          input.disabled = true;
        });
        
        // Cargar valores en el formulario
        document.getElementById('saldo-caja-general').value = saldosCuentas.caja_general;
        document.getElementById('saldo-cta-corriente-1').value = saldosCuentas.cuenta_corriente_1;
        document.getElementById('saldo-cta-corriente-2').value = saldosCuentas.cuenta_corriente_2;
        document.getElementById('saldo-cuenta-ahorro').value = saldosCuentas.cuenta_ahorro;

        // Cargar detalles de cuentas
        document.getElementById('banco-caja-general').value = accountDetails.caja_general.banco;
        document.getElementById('numero-caja-general').value = accountDetails.caja_general.numero;
        document.getElementById('banco-cta-corriente-1').value = accountDetails.cuenta_corriente_1.banco;
        document.getElementById('numero-cta-corriente-1').value = accountDetails.cuenta_corriente_1.numero;
        document.getElementById('banco-cta-corriente-2').value = accountDetails.cuenta_corriente_2.banco;
        document.getElementById('numero-cta-corriente-2').value = accountDetails.cuenta_corriente_2.numero;
        document.getElementById('banco-cuenta-ahorro').value = accountDetails.cuenta_ahorro.banco;
        document.getElementById('numero-cuenta-ahorro').value = accountDetails.cuenta_ahorro.numero;
      }
    }

    // Función para configurar cuentas iniciales
    function configurarCuentasIniciales(e) {
      e.preventDefault();
      
      if (!confirm('¿Está seguro(a) de guardar los cambios? Esta operación solo se podrá realizar una sola vez.')) {
        return;
      }
      
      // Obtener valores del formulario
      saldosCuentas.caja_general = parseFloat(document.getElementById('saldo-caja-general').value) || 0;
      saldosCuentas.cuenta_corriente_1 = parseFloat(document.getElementById('saldo-cta-corriente-1').value) || 0;
      saldosCuentas.cuenta_corriente_2 = parseFloat(document.getElementById('saldo-cta-corriente-2').value) || 0;
      saldosCuentas.cuenta_ahorro = parseFloat(document.getElementById('saldo-cuenta-ahorro').value) || 0;
      
      // Guardar detalles de cuentas
      accountDetails.caja_general = {
        banco: document.getElementById('banco-caja-general').value,
        numero: document.getElementById('numero-caja-general').value
      };
      
      accountDetails.cuenta_corriente_1 = {
        banco: document.getElementById('banco-cta-corriente-1').value,
        numero: document.getElementById('numero-cta-corriente-1').value
      };
      
      accountDetails.cuenta_corriente_2 = {
        banco: document.getElementById('banco-cta-corriente-2').value,
        numero: document.getElementById('numero-cta-corriente-2').value
      };
      
      accountDetails.cuenta_ahorro = {
        banco: document.getElementById('banco-cuenta-ahorro').value,
        numero: document.getElementById('numero-cuenta-ahorro').value
      };
      
      // Guardar en localStorage
      localStorage.setItem('saldosCuentas', JSON.stringify(saldosCuentas));
      guardarDetallesCuentas();
      localStorage.setItem('initialAccountsSet', 'true');
      initialAccountsSet = true;
      
      // Bloquear formulario
      const inputs = document.querySelectorAll('#cuentasInicialesForm input, #cuentasInicialesForm select');
      inputs.forEach(input => {
        input.disabled = true;
      });
      
      mostrarNotificacion('Cuentas iniciales guardadas correctamente');
      
      // Cerrar modal después de 2 segundos
      setTimeout(() => {
        document.getElementById('cuentasInicialesModal').classList.remove('show');
      }, 2000);
    }

    // Inicializar cuando se carga la página
    document.addEventListener('DOMContentLoaded', function() {
      cargarDatosGuardados();
      
      // Event listener para el formulario
      document.getElementById('cuentasInicialesForm').addEventListener('submit', configurarCuentasIniciales);
      
      // Botón para abrir modal de cuentas iniciales
      document.getElementById('cuentasInicialesBtn').addEventListener('click', () => {
        document.getElementById('cuentasInicialesModal').classList.add('show');
      });
      
      // Cerrar modal de cuentas iniciales
      document.getElementById('closeCuentasInicialesModal').addEventListener('click', () => {
        document.getElementById('cuentasInicialesModal').classList.remove('show');
      });
    });
  </script>
</body>
</html>
@endsection
