@extends('layouts.nice', ['active' => 'orgs.macromedidor.index', 'title' => 'Registro de Lecturas Macromedidor'])

@section('content')
    <style>
      /* Botón Volver verde personalizado */
            .btn-volver-green {
              position: fixed;
              top: 24px;
              right: 24px;
              background: linear-gradient(90deg, #22c55e 0%, #38bdf8 100%);
              color: #fff;
              border: none;
              border-radius: 8px;
              padding: 12px 20px;
              font-size: 1rem;
              font-weight: 600;
              box-shadow: var(--shadow);
              cursor: pointer;
              z-index: 1000;
              display: flex;
              align-items: center;
              gap: 8px;
              transition: background 0.2s, transform 0.2s;
            }
            .btn-volver-green:hover {
              background: linear-gradient(90deg, #38bdf8 0%, #22c55e 100%);
              color: #fff;
              transform: translateY(-2px);
            }
            @media (max-width: 600px) {
              .btn-volver-green {
                top: 12px;
                right: 12px;
                padding: 10px 16px;
                font-size: 0.9rem;
              }
            }
      @media (max-width: 600px) {
        body {
          background: #f4f6fb !important;
        }
        .container {
          padding: 0;
        }
        .float-card, .card, .card-body, .card-header {
          box-shadow: none !important;
          border-radius: 18px !important;
          padding-left: 0 !important;
          padding-right: 0 !important;
          margin-left: 0 !important;
          margin-right: 0 !important;
        }
        .float-card {
          background: #fff !important;
          border: none !important;
          margin-bottom: 18px !important;
          padding: 18px 8px !important;
        }
        .form-grid {
          display: flex !important;
          flex-direction: column !important;
          gap: 0 !important;
        }
        .medidor-card {
          width: 100% !important;
          margin-bottom: 18px !important;
          padding: 20px 12px !important;
          font-size: 1.18rem !important;
          border-radius: 16px !important;
          box-shadow: 0 8px 24px rgba(30, 64, 175, 0.25) !important;
        }
        .medidor-header {
          font-size: 1.18rem !important;
          padding: 12px 10px !important;
          border-radius: 12px !important;
        }
        .loss-card, .form-group, .form-section {
          margin-bottom: 14px !important;
          padding: 10px 8px !important;
          border-radius: 16px !important;
          box-shadow: none !important;
        }
        input, select, textarea {
          font-size: 1.18rem !important;
          padding: 13px 8px !important;
          width: 100% !important;
          min-width: 0 !important;
          border-radius: 12px !important;
        }
        .action-button {
          width: 100% !important;
          font-size: 1.22rem !important;
          padding: 18px 0 !important;
          border-radius: 14px !important;
          position: relative !important;
          margin-bottom: 0 !important;
        }
        .button-group {
          flex-direction: column !important;
          gap: 8px !important;
          position: fixed !important;
          left: 0;
          right: 0;
          bottom: 0;
          width: 100vw !important;
          background: #f4f6fb !important;
          padding: 12px 12px 18px 12px !important;
          z-index: 100;
          box-shadow: 0 -2px 12px rgba(0,0,0,0.04);
        }
        h1, h3, h5 {
          font-size: 1.28rem !important;
        }
        .section-title {
          font-size: 1.18rem !important;
        }
        label {
          font-size: 1.18rem !important;
        }
        #textoPerdidas, #textoDistribucion {
          font-size: 1.18rem !important;
        }
        .back-to-top {
          display: none !important;
        }
        /* Mejoras para gráficos en móviles */
        .charts-row {
          flex-direction: column !important;
          gap: 0 !important;
          margin-bottom: 0 !important;
        }
        .chart-container {
          min-width: 0 !important;
          max-width: 100vw !important;
          width: 100vw !important;
          padding: 0 !important;
          margin-bottom: 18px !important;
        }
        .chart-container canvas {
          width: 100vw !important;
          max-width: 100vw !important;
          height: 260px !important;
          margin: 0 auto !important;
          display: block !important;
        }
        .section-title {
          font-size: 1.22rem !important;
          text-align: center !important;
        }
        #textoPerdidas, #textoDistribucion {
          font-size: 1.18rem !important;
          text-align: center !important;
          margin-top: 10px !important;
        }
      }
      @media (max-width: 900px) {
        .charts-row {
          flex-direction: column !important;
          gap: 18px !important;
        }
        .chart-container {
          max-width: 100% !important;
          min-width: 0 !important;
          padding: 12px 4px !important;
        }
        .card-body {
          padding: 18px 6px !important;
        }
        .card-header {
          padding: 18px 8px 10px 8px !important;
        }
        .action-button {
          padding: 12px 10px !important;
          font-size: 1rem !important;
        }
        .button-group {
          gap: 10px !important;
        }
        h1, h3, h5 {
          font-size: 1.1rem !important;
        }
        .section-title {
          font-size: 1rem !important;
        }
        label {
          font-size: 0.98rem !important;
        }
        input, select, textarea {
          font-size: 0.98rem !important;
          padding: 10px !important;
        }
        .loss-card {
          padding: 12px 6px !important;
        }
        .medidor-card {
          padding: 16px 8px !important;
        }
        .float-card {
          padding: 12px 6px !important;
        }
        .form-grid {
          gap: 10px !important;
        }
        #textoPerdidas, #textoDistribucion {
          font-size: 0.98rem !important;
        }
      }
      :root {
        --primary: #2563eb;
        --secondary: #22c55e;
        --danger: #ef4444;
        --bg: #f8fafc;
        --card-bg: #fff;
        --input-bg: #f1f5f9;
        --border: #e5e7eb;
        --shadow: 0 4px 24px rgba(0,0,0,0.08);
        --radius: 14px;
        --text-dark-blue: #1e3a8a; /* Azul oscuro para textos */
      }
      body {
        background: #f8fafc;
        font-family: 'Poppins', 'Nunito', 'Open Sans', sans-serif;
        color: var(--text-dark-blue) !important; /* Azul oscuro para todo el texto */
        min-height: 100vh;
        margin: 0;
        padding: 0;
        width: 100vw;
        box-sizing: border-box;
      }
      :root {
        --container-width: 800px; /* Cambia este valor manualmente */
      }
      .container {
  width: calc(0.4 * (100vw - 300px));
  max-width: calc(0.4 * (100vw - 300px));
        margin-left: 300px;
        margin-right: 0;
        padding: 0 32px;
        box-sizing: border-box;
      }
      @media (max-width: 1199px) {
        .container {
          width: 100vw;
          max-width: 100vw;
          margin-left: 0;
          margin-right: 0;
          padding: 0 8px;
        }
      }
      .card {
        background: var(--card-bg);
        border-radius: 18px;
        box-shadow: 0 8px 32px rgba(37,99,235,0.12), 0 2px 8px rgba(37,99,235,0.07);
        border: 1px solid var(--border);
        overflow: hidden;
        position: relative;
      }
      .card:hover {
        box-shadow: var(--shadow);
        transform: none;
        z-index: 2;
      }
      /* Tarjetas flotantes adicionales */
      .float-card {
        background: var(--card-bg);
        border-radius: 18px;
        box-shadow: 0 8px 32px rgba(37,99,235,0.15), 0 2px 8px rgba(37,99,235,0.07);
        border: 1px solid var(--border);
        padding: 28px 24px;
        margin-bottom: 32px;
        position: relative;
      }
      .float-card:hover {
        box-shadow: 0 8px 32px rgba(37,99,235,0.15), 0 2px 8px rgba(37,99,235,0.07);
        transform: none;
        z-index: 3;
      }
      .card-header {
        background: var(--primary);
        color: #fff;
        padding: 32px 24px 18px 24px;
        border-bottom: 1px solid var(--border);
        text-align: center;
      }
      .card-header h1 {
        font-size: 2.2rem;
        font-weight: 700;
        margin-bottom: 8px;
      }
      .card-header .instructions {
        font-size: 1.1rem;
        opacity: 0.9;
      }
      .card-body {
        padding: 32px 24px;
      }
      .form-section {
        margin-bottom: 24px;
      }
      .form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
        margin-bottom: 24px;
      }
      @media (max-width: 700px) {
        .form-grid {
          grid-template-columns: 1fr;
        }
        .container {
          max-width: 100%;
        }
      }
      .form-group {
        display: flex;
        flex-direction: column;
      }
      label {
        font-weight: 600;
        margin-bottom: 7px;
        color: var(--text-dark-blue) !important; /* Azul oscuro para labels */
      }
      .required:after {
        content: " *";
        color: var(--danger);
      }
      input, select, textarea {
        background: var(--input-bg);
        border: 1px solid var(--border);
        border-radius: 8px;
        padding: 13px;
        font-size: 1rem;
        margin-bottom: 0;
        transition: border-color 0.2s;
      }
      input:focus, select:focus, textarea:focus {
        border-color: var(--primary);
        outline: none;
        background: #fff;
      }
      textarea {
        min-height: 80px;
        resize: vertical;
      }
      .medidor-card {
        background: #3b82f6;
        border-radius: 12px;
        padding: 18px 16px;
        box-shadow: 0 8px 24px rgba(30, 64, 175, 0.25), 0 4px 12px rgba(59, 130, 246, 0.15);
        border: 2px solid rgba(59, 130, 246, 0.3);
        color: white;
        position: relative;
        overflow: hidden;
      }
      
      .medidor-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(255,255,255,0.05);
        pointer-events: none;
      }
      
      .medidor-card:hover {
        box-shadow: 0 12px 32px rgba(30, 64, 175, 0.35), 0 6px 16px rgba(59, 130, 246, 0.25);
        transform: translateY(-2px);
        transition: all 0.3s ease;
      }
      
      .medidor-produccion {
        background: #10b981;
        box-shadow: 0 8px 24px rgba(5, 150, 105, 0.20), 0 4px 12px rgba(16, 185, 129, 0.12);
        border: 2px solid rgba(16, 185, 129, 0.25);
      }
      
      .medidor-produccion:hover {
        box-shadow: 0 12px 32px rgba(5, 150, 105, 0.28), 0 6px 16px rgba(16, 185, 129, 0.18);
      }
      
      .medidor-distribucion {
        background: #38bdf8;
        box-shadow: 0 8px 24px rgba(14, 165, 233, 0.20), 0 4px 12px rgba(56, 189, 248, 0.12);
        border: 2px solid rgba(56, 189, 248, 0.25);
      }
      
      .medidor-distribucion:hover {
        box-shadow: 0 12px 32px rgba(14, 165, 233, 0.28), 0 6px 16px rgba(56, 189, 248, 0.18);
      }
      .medidor-header {
        background: rgba(255, 255, 255, 0.2);
        color: #fff;
        border-radius: 8px;
        padding: 12px 16px;
        margin-bottom: 16px;
        font-size: 1.2rem;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 10px;
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.3);
        /* text-shadow: 0 2px 4px rgba(0,0,0,0.3); */
      }
      
      .medidor-card label {
        color: rgba(255, 255, 255, 0.95);
        font-weight: 600;
        /* text-shadow: 0 1px 2px rgba(0,0,0,0.3); */
      }
      
      .medidor-card input {
        background: rgba(255, 255, 255, 0.95);
        border: 2px solid rgba(255, 255, 255, 0.3);
        color: #1f2937;
        font-weight: 600;
      }
      
      .medidor-card input:focus {
        background: #ffffff;
        border-color: rgba(255, 255, 255, 0.8);
        box-shadow: 0 0 0 3px rgba(255, 255, 255, 0.2);
      }
      .loss-card {
        background: #fca5a5;
        border-left: 5px solid #ef4444;
        border-radius: 10px;
        padding: 18px 16px;
        margin-bottom: 24px;
        box-shadow: 0 4px 16px rgba(239, 68, 68, 0.18), 0 2px 8px rgba(239, 68, 68, 0.12);
        border: 1px solid rgba(239, 68, 68, 0.25);
      }
      .loss-card h5 {
        color: #b91c1c;
        font-size: 1.1rem;
        font-weight: 700;
        margin-bottom: 16px;
      }
      .highlight-loss {
        background: #fca5a5 !important;
        color: #b91c1c;
        font-weight: 700;
      }
      .button-group {
        display: flex;
        justify-content: center;
        gap: 18px;
        margin-top: 32px;
      }
      .action-button {
        background: linear-gradient(135deg, #2563eb 0%, #4f8cff 100%);
        color: #fff;
        border: none;
        border-radius: 12px;
        padding: 16px 36px;
        font-size: 1.12rem;
        font-weight: 600;
        box-shadow: 0 4px 16px rgba(37,99,235,0.10);
        cursor: pointer;
        transition: background 0.2s, transform 0.2s;
        display: flex;
        align-items: center;
        gap: 10px;
        letter-spacing: 0.02em;
      }
      .action-button:hover {
        background: linear-gradient(135deg, #22c55e 0%, #4f8cff 100%);
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(37,99,235,0.18);
      }
      
      /* Estilo específico para el botón Guardar Lecturas */
      .action-button.submit-btn {
        background: #dc3545 !important; /* Rojo Bootstrap */
        box-shadow: 0 2px 8px rgba(220, 53, 69, 0.2);
      }
      
      .action-button.submit-btn:hover {
        background: #c82333 !important; /* Rojo más oscuro al hacer hover */
        box-shadow: 0 4px 12px rgba(220, 53, 69, 0.3);
        transform: translateY(-2px);
      }
      
      /* Estilo específico para el botón Ver Gráficos */
      #btnVerGraficos {
        background: #28a745 !important; /* Verde Bootstrap */
        box-shadow: 0 2px 8px rgba(40, 167, 69, 0.2);
      }
      
      #btnVerGraficos:hover {
        background: #218838 !important; /* Verde más oscuro al hacer hover */
        box-shadow: 0 4px 12px rgba(40, 167, 69, 0.3);
        transform: translateY(-2px);
      }
      
      /* Estilos globales para texto azul oscuro */
      h1, h2, h3, h4, h5, h6, p, span, div, td, th, 
      .section-title, .instructions, .medidor-header h5,
      #textoPerdidas, #textoDistribucion, #textoHistorico {
        color: var(--text-dark-blue) !important;
      }
      
      /* Específicos para elementos de formulario */
      .form-control, input, select, textarea {
        color: var(--text-dark-blue) !important;
      }
      
      /* Para textos en tarjetas especiales */
      .medidor-card h5 {
        color: var(--text-dark-blue) !important;
      }
      
      .back-to-top {
        position: fixed;
        bottom: 24px;
        right: 24px;
        background: var(--primary);
        color: #fff;
        border-radius: 50%;
        width: 48px;
        height: 48px;
        box-shadow: var(--shadow);
        font-size: 2rem;
        z-index: 100;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: background 0.2s;
      }
      .back-to-top:hover {
        background: var(--secondary);
      }
      
      /* Botón volver superior derecha */
      .btn-volver {
        position: fixed;
        top: 24px;
        right: 24px;
        background: linear-gradient(90deg, #f8bcbc 0%, #e57373 100%);
        color: #6c1a1a;
        border: none;
        border-radius: 8px;
        padding: 12px 20px;
        font-size: 1rem;
        font-weight: 600;
        box-shadow: var(--shadow);
        cursor: pointer;
        z-index: 1000;
        display: flex;
        align-items: center;
        gap: 8px;
        transition: background 0.2s, transform 0.2s;
      }
      
      .btn-volver:hover {
        background: linear-gradient(90deg, #e57373 0%, #f8bcbc 100%);
        color: #a31515;
        transform: translateY(-2px);
      }
      
      @media (max-width: 600px) {
        .btn-volver {
          top: 12px;
          right: 12px;
          padding: 10px 16px;
          font-size: 0.9rem;
          background: linear-gradient(90deg, #f8bcbc 0%, #e57373 100%);
          color: #6c1a1a;
        }
      }
      
      /* Estilos para la tabla de registros */
      .table-responsive {
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 8px 32px rgba(37,99,235,0.10);
        margin-bottom: 32px;
      }
      
      .table {
        margin-bottom: 0;
        font-size: 0.9rem;
      }
      
      .table thead th {
        background: linear-gradient(90deg, #2563eb 0%, #4f8cff 100%);
        color: #fff;
        border: none;
        padding: 18px 14px;
        font-weight: 700;
        text-align: center;
        white-space: nowrap;
        font-size: 1.05rem;
        letter-spacing: 0.01em;
        box-shadow: 0 2px 8px rgba(37,99,235,0.08);
      }
      
      .table tbody td {
        padding: 14px 10px;
        vertical-align: middle;
        text-align: center;
        border-color: #e5e7eb;
        font-size: 0.98rem;
        background: #f8fafc;
      }
      
      .table-striped tbody tr:nth-of-type(odd) {
        background-color: #e6f0ff;
      }
      
      .table-hover tbody tr:hover {
        background-color: #dbeafe;
        transition: background 0.2s;
      }
      
      .btn-sm {
        padding: 4px 8px;
        font-size: 0.8rem;
        border-radius: 4px;
        margin: 0 2px;
      }
      
      .text-success {
        color: #22c55e !important;
        font-weight: 600;
      }
      
      .text-danger {
        color: #ef4444 !important;
        font-weight: 600;
      }
      
      .text-primary {
        color: var(--primary) !important;
        font-weight: 600;
      }
      
      @media (max-width: 900px) {
        .table {
          font-size: 0.8rem;
        }
        
        .table thead th,
        .table tbody td {
          padding: 8px 6px;
        }
        
        .btn-sm {
          padding: 2px 6px;
          font-size: 0.7rem;
        }
      }
    </style>
<body>

@include('orgs.contable.partials.contable-styles')

<!-- Botón Volver -->
<button class="btn-volver" onclick="window.history.back()" title="Volver a la página anterior">
  <i class="bi bi-arrow-left"></i> Volver
</button>

<!-- Header y Sidebar si se requiere -->

<main class="container">
  <!-- Botón Volver verde superior derecha -->
  <button type="button" class="btn-volver-green" id="btnVolverOperador">
    <i class="bi bi-arrow-left-circle"></i> Volver a Operador
  </button>
  <div class="float-card mt-5" id="vistaFormulario">
            
    <div class="card-header section-header">
      <h1>Registro de Lecturas</h1>
      <p class="instructions">Ingrese los datos de lectura de macromedidor y distribución. Los cálculos de pérdidas se mostrarán automáticamente.</p>
    </div>
    <div class="card-body">
  <form id="registroMacromedidor" class="form-section" style="width:70%; min-width:320px; margin:0 auto;">
        <div class="form-grid">
          <div class="form-group">
            <label>Fecha de Lectura</label>
            <div id="fechaLecturaTexto" class="form-control" style="background-color: #f8f9fa; color: #6c757d; font-weight:600;">
              {{ \Carbon\Carbon::now()->format('d-m-Y') }}
            </div>
            <input type="hidden" id="fechaLectura" name="fecha" value="{{ date('Y-m-d') }}">
          </div>
          <div class="form-group">
            <label class="required">Frecuencia de Lectura</label>
            <select class="form-control" id="frecuenciaLectura" required>
              <option value="diaria">Diaria</option>
              <option value="semanal" selected>Semanal</option>
              <option value="quincenal">Quincenal</option>
              <option value="mensual">Mensual</option>
              <option value="anual">Anual</option>
            </select>
          </div>
        </div>
        <div class="espacio-superior"></div>
        <div class="form-grid">
          <div class="medidor-card float-card medidor-produccion">
            <div class="medidor-header">
              <h5 style="color: #1e3a8a;"><i class="bi bi-water"></i> Lectura Producción (m³)</h5>
            </div>
            <div class="form-group">
              <label class="required">Lectura Anterior</label>
              <input type="number" class="form-control" id="lecturaAnteriorExtraccion" step="1" value="0" readonly style="background-color: #f8f9fa; color: #6c757d;">
            </div>
            <div class="form-group">
              <label class="required">Lectura Actual</label>
              <input type="number" class="form-control" id="lecturaActualExtraccion" step="1" required>
            </div>
          </div>
          <div class="medidor-card float-card medidor-distribucion">
            <div class="medidor-header">
              <h5 style="color: #1e3a8a;"><i class="bi bi-truck"></i> Lectura Distribución (m³)</h5>
            </div>
            <div class="form-group">
              <label class="required">Lectura Anterior</label>
              <input type="number" class="form-control" id="lecturaAnteriorEntrega" step="1" value="0" readonly style="background-color: #f8f9fa; color: #6c757d;">
            </div>
            <div class="form-group">
              <label class="required">Lectura Actual</label>
              <input type="number" class="form-control" id="lecturaActualEntrega" step="1" required>
            </div>
          </div>
        </div>
        <div class="espacio-superior"></div>
        <div class="loss-card float-card">
          <h5><i class="bi bi-exclamation-triangle"></i> Pérdidas de Agua</h5>
          <div class="form-grid">
            <div class="form-group">
              <label>Producción Total (m³)</label>
              <input type="text" class="form-control" id="extraccionTotal" readonly style="background-color: #f8f9fa; color: #6c757d; cursor: not-allowed;">
            </div>
            <div class="form-group">
              <label>Distribución Total (m³)</label>
              <input type="text" class="form-control" id="entregaTotal" readonly style="background-color: #f8f9fa; color: #6c757d; cursor: not-allowed;">
            </div>
            <div class="form-group">
              <label>Pérdidas (m³)</label>
              <input type="text" class="form-control highlight-loss" id="perdidasTotal" readonly>
            </div>
            <div class="form-group" style="grid-column: span 3;">
              <label>Porcentaje de Pérdidas</label>
              <input type="text" class="form-control highlight-loss" id="porcentajePerdidas" readonly>
            </div>
          </div>
        </div>
        <div class="form-group float-card">
          <label>Observaciones</label>
          <textarea class="form-control" id="observaciones" rows="3"></textarea>
        </div>
        <div class="form-section float-card">
          <h3 class="section-title">Responsable</h3>
          <div class="form-group">
            <label class="required">Nombre</label>
            <input type="text" class="form-control" id="nombreResponsable" required>
          </div>
        </div>
        <div class="button-group">
          <button type="button" class="action-button submit-btn" id="btnGuardar">
            <i class="bi bi-save"></i> Guardar Lecturas
          </button>
          <button type="button" class="action-button" id="btnVerGraficos">
            <i class="bi bi-bar-chart"></i> Ver Gráficos
          </button>
          <button type="button" class="action-button" id="btnVerRegistros">
            <i class="bi bi-table"></i> Ver Registros
          </button>
        </div>
      </form>
    </div>
  </div>

  <!-- Vista de registros -->
  <div class="float-card mt-5" id="vistaRegistros" style="display:none;">
    <div class="card-header section-header">
      <h1>Historial de Registros</h1>
      <p class="instructions">Visualiza todos los registros de lecturas guardados en el sistema.</p>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-striped table-hover" id="tablaRegistros">
          <thead class="table-primary">
            <tr>
              <th>#</th>
              <th>Fecha</th>
              <th>Frecuencia</th>
              <th>Producción Anterior</th>
              <th>Producción Actual</th>
              <th>Producción Total</th>
              <th>Distribución Anterior</th>
              <th>Distribución Actual</th>
              <th>Distribución Total</th>
              <th>Pérdidas</th>
              <th>% Pérdidas</th>
              <th>Responsable</th>
              <th>Observaciones</th>
              <th>Acciones</th>
            </tr>
          </thead>
          <tbody id="tablaRegistrosBody">
            <!-- Los registros se cargarán aquí dinámicamente -->
          </tbody>
        </table>
      </div>
      <div class="button-group">
        <button type="button" class="action-button" id="btnVolverFormularioDesdeRegistros">
          <i class="bi bi-arrow-left"></i> Volver al Formulario
        </button>
        <button type="button" class="action-button" id="btnExportarRegistros">
          <i class="bi bi-download"></i> Exportar a CSV
        </button>
      </div>
    </div>
  </div>

  <!-- Vista de gráficos -->
  <div class="float-card mt-5" id="vistaGraficos" style="display:none;">
    <div class="card-header section-header">
      <h1>Dashboard de Lecturas</h1>
      <p class="instructions">Visualiza los resultados históricos y el análisis de pérdidas por periodo y año.</p>
    </div>
    <div class="card-body">
      <div class="form-grid" style="margin-bottom: 18px;">
        <div class="form-group">
          <label>Filtrar por Año</label>
          <select class="form-control" id="filtroAnio">
            <!-- Opciones de años se llenan dinámicamente -->
          </select>
        </div>
        <div class="form-group">
          <label>Filtrar por Frecuencia</label>
          <select class="form-control" id="filtroFrecuencia">
            <option value="todos">Todos</option>
            <option value="diaria">Diaria</option>
            <option value="semanal">Semanal</option>
            <option value="quincenal">Quincenal</option>
            <option value="mensual">Mensual</option>
            <option value="anual">Anual</option>
          </select>
        </div>
      </div>
      <div class="charts-row" style="display: flex; gap: 32px; justify-content: center; align-items: flex-start; margin-bottom: 32px;">
        <div class="form-section chart-container" style="flex:1; min-width:280px;">
          <h3 class="section-title">Pérdidas de Agua</h3>
          <canvas id="graficoPerdidas" style="width:100%;max-width:600px;"></canvas>
          <div id="textoPerdidas" class="mt-3" style="font-size:1.08rem; color:#1e3a8a; text-align:center;"></div>
        </div>
        <div class="form-section chart-container" style="flex:1; min-width:280px;">
          <h3 class="section-title">Distribución vs Producción</h3>
          <canvas id="graficoDistribucion" style="width:100%;max-width:600px;"></canvas>
          <div id="textoDistribucion" class="mt-3" style="font-size:1.08rem; color:#1e3a8a; text-align:center;"></div>
        </div>
        <div class="form-section chart-container" style="flex:1; min-width:280px;">
          <h3 class="section-title">Histórico por Periodo</h3>
          <canvas id="graficoHistorico" style="width:100%;max-width:600px;"></canvas>
          <div id="textoHistorico" class="mt-3" style="font-size:1.08rem; color:#1e3a8a; text-align:center;"></div>
        </div>
      </div>
      <div class="button-group">
        <button type="button" class="action-button" id="btnVolverFormulario">
          <i class="bi bi-arrow-left"></i> Volver al Formulario
        </button>
      </div>
    </div>
  </div>
</main>

<!-- Footer -->
 <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>
    <!-- Vendor JS Files -->
    <script src="https://hydrosite.cl/public/theme/common/js/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://hydrosite.cl/public/theme/nice/assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script>
      $(document).ready(function() {
        // Detectar orgId desde la URL
          // Mejor detección de orgId para cualquier estructura de URL
          var orgId = null;
          var pathParts = window.location.pathname.split('/');
          // Busca el primer segmento numérico después de 'org'
          for (var i = 0; i < pathParts.length; i++) {
            if (pathParts[i] === 'org' && i + 1 < pathParts.length && !isNaN(parseInt(pathParts[i + 1]))) {
              orgId = pathParts[i + 1];
              break;
            }
          }

        // Botón Volver a la vista principal operador
        $('#btnVolverOperador').click(function() {
          if (orgId) {
            window.location.href = '/org/' + orgId + '/operator';
          } else {
            alert('No se pudo detectar el ID de la organización en la URL.');
          }
        });
          // Botón Volver desde Registros al panel de operador
          $('#btnVolverFormularioDesdeRegistros').after(
            `<button type="button" class="action-button" id="btnVolverOperadorDesdeRegistros">
              <i class="bi bi-arrow-left-circle"></i> Volver a Operador
            </button>`
          );
          $(document).on('click', '#btnVolverOperadorDesdeRegistros', function() {
            if (orgId) {
              window.location.href = '/org/' + orgId + '/operator';
            } else {
              alert('No se pudo detectar el ID de la organización en la URL.');
            }
          });

          // Botón Volver desde Gráficos al panel de operador
          $('#btnVolverFormulario').after(
            `<button type="button" class="action-button" id="btnVolverOperadorDesdeGraficos">
              <i class="bi bi-arrow-left-circle"></i> Volver a Operador
            </button>`
          );
          $(document).on('click', '#btnVolverOperadorDesdeGraficos', function() {
            if (orgId) {
              window.location.href = '/org/' + orgId + '/operator';
            } else {
              alert('No se pudo detectar el ID de la organización en la URL.');
            }
          });

        // Cargar lecturas anteriores al inicializar
        cargarUltimasLecturas();

        // Calcular pérdidas al cambiar valores
        $('#lecturaAnteriorExtraccion, #lecturaActualExtraccion, #lecturaAnteriorEntrega, #lecturaActualEntrega, #frecuenciaLectura').on('input change', function() {
          calcularPerdidas();
        });

        function calcularPerdidas() {
          const lecturaAnteriorExtraccion = parseFloat($('#lecturaAnteriorExtraccion').val()) || 0;
          const lecturaActualExtraccion = parseFloat($('#lecturaActualExtraccion').val()) || 0;
          const lecturaAnteriorEntrega = parseFloat($('#lecturaAnteriorEntrega').val()) || 0;
          const lecturaActualEntrega = parseFloat($('#lecturaActualEntrega').val()) || 0;
          const extraccionTotal = lecturaActualExtraccion - lecturaAnteriorExtraccion;
          const entregaTotal = lecturaActualEntrega - lecturaAnteriorEntrega;
          const perdidasTotal = extraccionTotal - entregaTotal;
          let porcentajePerdidas = 0;
          if (extraccionTotal !== 0) {
            porcentajePerdidas = (perdidasTotal / extraccionTotal) * 100;
          }
          // Mostrar siempre el valor, aunque sea negativo o cero
          $('#extraccionTotal').val(Math.round(extraccionTotal));
          $('#entregaTotal').val(Math.round(entregaTotal));
          $('#perdidasTotal').val(Math.round(perdidasTotal));
          $('#porcentajePerdidas').val(Math.round(porcentajePerdidas) + '%');
        // Inicializar fecha al cargar el formulario
        function actualizarFechaActual() {
          const hoy = new Date();
          const yyyy = hoy.getFullYear();
          const mm = String(hoy.getMonth() + 1).padStart(2, '0');
          const dd = String(hoy.getDate()).padStart(2, '0');
          const fechaActual = `${yyyy}-${mm}-${dd}`;
          $('#fechaLectura').val(fechaActual);
          $('#fechaLectura').attr('placeholder', fechaActual);
        }
        actualizarFechaActual();
        }

        // Guardar lectura en la base de datos
        $('#btnGuardar').click(function() {
          let isValid = true;
          $('[required]').each(function() {
            if (!$(this).val()) {
              isValid = false;
              $(this).addClass('is-invalid');
            } else {
              $(this).removeClass('is-invalid');
            }
          });
          if (!isValid) {
            alert('Por favor complete todos los campos requeridos.');
            return;
          }
          // Preparar datos
          const data = {
            _token: $('meta[name="csrf-token"]').attr('content'),
            fecha: $('#fechaLectura').val(),
            frecuencia: $('#frecuenciaLectura').val(),
            lectura_anterior_extraccion: $('#lecturaAnteriorExtraccion').val(),
            lectura_actual_extraccion: $('#lecturaActualExtraccion').val(),
            lectura_anterior_entrega: $('#lecturaAnteriorEntrega').val(),
            lectura_actual_entrega: $('#lecturaActualEntrega').val(),
            extraccion_total: $('#extraccionTotal').val(),
            entrega_total: $('#entregaTotal').val(),
            perdidas_total: $('#perdidasTotal').val(),
            porcentaje_perdidas: $('#porcentajePerdidas').val(),
            responsable: $('#nombreResponsable').val(),
            observaciones: $('#observaciones').val()
          };
          if (!orgId) {
            alert('No se pudo detectar el ID de la organización en la URL.');
            return;
          }
          var guardarUrl = '/org/' + orgId + '/aguapotable/macromedidor/guardar';
          $.post(guardarUrl, data, function(resp) {
            if (resp.success) {
              alert('Lectura guardada correctamente!');
              limpiarCampos();
            } else {
              alert('Error al guardar.');
            }
          }).fail(function(xhr) {
            alert('Error al guardar: ' + (xhr.responseJSON?.message || 'Error de red'));
          });
        });

        function limpiarCampos() {
          $('#lecturaAnteriorExtraccion, #lecturaActualExtraccion, #lecturaAnteriorEntrega, #lecturaActualEntrega, #observaciones, #nombreResponsable').val('');
          $('#extraccionTotal, #entregaTotal, #perdidasTotal, #porcentajePerdidas').val('');
          actualizarFechaActual();
          cargarUltimasLecturas(); // Cargar las lecturas anteriores desde la última entrada
        }

        // Función para cargar las últimas lecturas como lecturas anteriores
        function cargarUltimasLecturas() {
          if (!orgId) return;
          $.get(`/org/${orgId}/aguapotable/macromedidor/listar`, function(registros) {
            if (registros.length > 0) {
              const ultimaLectura = registros[0]; // El primer registro es el más reciente
              $('#lecturaAnteriorExtraccion').val(ultimaLectura.lectura_actual_extraccion);
              $('#lecturaAnteriorEntrega').val(ultimaLectura.lectura_actual_entrega);
            }
          });
        }

        // Cargar años únicos para filtro
        function cargarAniosFiltro() {
          if (!orgId) return;
          $.get(`/org/${orgId}/aguapotable/macromedidor/listar`, function(registros) {
            const anios = [...new Set(registros.map(r => r.fecha.substring(0,4)))];
            const filtro = $('#filtroAnio');
            filtro.empty();
            filtro.append('<option value="">Todos</option>');
            anios.forEach(anio => filtro.append(`<option value="${anio}">${anio}</option>`));
          });
        }
        cargarAniosFiltro();

        // Cargar registros en la tabla con filtros
        function cargarTablaRegistros() {
          if (!orgId) return;
          const anio = $('#filtroAnio').val();
          const frecuencia = $('#filtroFrecuencia').val();
          $.get(`/org/${orgId}/aguapotable/macromedidor/listar`, { anio, frecuencia }, function(registros) {
            const tbody = $('#tablaRegistrosBody');
            tbody.empty();
            if (registros.length === 0) {
              tbody.append(`
                <tr>
                  <td colspan="14" class="text-center text-muted">
                    <i class="bi bi-info-circle"></i> No hay registros guardados aún
                  </td>
                </tr>
              `);
              return;
            }
            registros.forEach((registro, index) => {
              const perdidaClass = registro.perdidas_total > 0 ? 'text-danger' : (registro.perdidas_total < 0 ? 'text-success' : 'text-warning');
              let porcentajeNum = 0;
              if (registro.porcentaje_perdidas && typeof registro.porcentaje_perdidas === 'string') {
                porcentajeNum = parseFloat(registro.porcentaje_perdidas);
              } else if (typeof registro.porcentaje_perdidas === 'number') {
                porcentajeNum = registro.porcentaje_perdidas;
              }
              const porcentajeClass = porcentajeNum > 10 ? 'text-danger' : (porcentajeNum < 0 ? 'text-success' : (porcentajeNum > 5 ? 'text-warning' : 'text-success'));
              // Formatear fecha a DD-MM-YYYY
              let fechaFormateada = '';
              if (registro.fecha && /^\d{4}-\d{2}-\d{2}$/.test(registro.fecha)) {
                const partes = registro.fecha.split('-');
                fechaFormateada = `${partes[2]}-${partes[1]}-${partes[0]}`;
              } else if (registro.fecha && /\d{1,2} de [a-zA-Z]+ de \d{4}/.test(registro.fecha)) {
                // Si viene en formato largo, intentar extraer los números
                const meses = {
                  'enero': '01', 'febrero': '02', 'marzo': '03', 'abril': '04', 'mayo': '05', 'junio': '06',
                  'julio': '07', 'agosto': '08', 'septiembre': '09', 'octubre': '10', 'noviembre': '11', 'diciembre': '12'
                };
                const match = registro.fecha.match(/(\d{1,2}) de ([a-zA-Z]+) de (\d{4})/);
                if (match) {
                  const dia = match[1].padStart(2, '0');
                  const mes = meses[match[2].toLowerCase()] || '01';
                  const anio = match[3];
                  fechaFormateada = `${dia}-${mes}-${anio}`;
                } else {
                  fechaFormateada = registro.fecha;
                }
              } else {
                fechaFormateada = registro.fecha;
              }
              tbody.append(`
                <tr>
                  <td>${index+1}</td>
                  <td>${fechaFormateada}</td>
                  <td><span class="badge bg-primary">${registro.frecuencia}</span></td>
                  <td class="text-primary">${registro.lectura_anterior_extraccion}</td>
                  <td class="text-primary">${registro.lectura_actual_extraccion}</td>
                  <td class="text-success"><strong>${registro.extraccion_total}</strong></td>
                  <td class="text-info">${registro.lectura_anterior_entrega}</td>
                  <td class="text-info">${registro.lectura_actual_entrega}</td>
                  <td class="text-success"><strong>${registro.entrega_total}</strong></td>
                  <td class="${perdidaClass}"><strong>${registro.perdidas_total}</strong></td>
                  <td class="${porcentajeClass}"><strong>${registro.porcentaje_perdidas}</strong></td>
                  <td>${registro.responsable}</td>
                  <td style="max-width: 150px; overflow: hidden; text-overflow: ellipsis;" title="${registro.observaciones}">
                    ${registro.observaciones || '-'}
                  </td>
                  <td></td>
                </tr>
              `);
            });
          });
        }
        $('#filtroAnio, #filtroFrecuencia').on('change', cargarTablaRegistros);

        // Exportar registros a CSV
        function exportarCSV() {
          if (!orgId) return;
          const anio = $('#filtroAnio').val();
          const frecuencia = $('#filtroFrecuencia').val();
          $.get(`/org/${orgId}/aguapotable/macromedidor/listar`, { anio, frecuencia }, function(registros) {
            if (registros.length === 0) {
              alert('No hay registros para exportar');
              return;
            }
            const headers = [
              'ID', 'Fecha', 'Frecuencia', 'Producción Anterior', 'Producción Actual', 'Producción Total',
              'Distribución Anterior', 'Distribución Actual', 'Distribución Total', 'Pérdidas',
              'Porcentaje Pérdidas', 'Responsable', 'Observaciones'
            ];
            let csvContent = headers.join(',') + '\n';
            registros.forEach((registro, i) => {
              // Formatear fecha a DD-MM-YYYY
              let fechaFormateada = '';
              if (registro.fecha && /^\d{4}-\d{2}-\d{2}$/.test(registro.fecha)) {
                const partes = registro.fecha.split('-');
                fechaFormateada = `${partes[2]}-${partes[1]}-${partes[0]}`;
              } else if (registro.fecha && /\d{1,2} de [a-zA-Z]+ de \d{4}/.test(registro.fecha)) {
                const meses = {
                  'enero': '01', 'febrero': '02', 'marzo': '03', 'abril': '04', 'mayo': '05', 'junio': '06',
                  'julio': '07', 'agosto': '08', 'septiembre': '09', 'octubre': '10', 'noviembre': '11', 'diciembre': '12'
                };
                const match = registro.fecha.match(/(\d{1,2}) de ([a-zA-Z]+) de (\d{4})/);
                if (match) {
                  const dia = match[1].padStart(2, '0');
                  const mes = meses[match[2].toLowerCase()] || '01';
                  const anio = match[3];
                  fechaFormateada = `${dia}-${mes}-${anio}`;
                } else {
                  fechaFormateada = registro.fecha;
                }
              } else {
                fechaFormateada = registro.fecha;
              }
              // Asegurar que los valores negativos o cero se muestren correctamente
              let perdidasTotal = (typeof registro.perdidas_total !== 'undefined') ? registro.perdidas_total : 0;
              let porcentajePerdidas = (typeof registro.porcentaje_perdidas !== 'undefined') ? registro.porcentaje_perdidas : '0%';
              if (typeof porcentajePerdidas === 'number') {
                porcentajePerdidas = Math.round(porcentajePerdidas) + '%';
              }
              const row = [
                i+1,
                fechaFormateada,
                registro.frecuencia,
                registro.lectura_anterior_extraccion,
                registro.lectura_actual_extraccion,
                registro.extraccion_total,
                registro.lectura_anterior_entrega,
                registro.lectura_actual_entrega,
                registro.entrega_total,
                perdidasTotal,
                porcentajePerdidas,
                `"${registro.responsable}"`,
                `"${registro.observaciones || ''}"`
              ];
              csvContent += row.join(',') + '\n';
            });
            const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
            const link = document.createElement('a');
            link.href = URL.createObjectURL(blob);
            link.download = `registros_lecturas_${new Date().toISOString().split('T')[0]}.csv`;
            link.click();
          });
        }
        $('#btnExportarRegistros').click(function() {
          exportarCSV();
        });

        // Renderizar gráficos con datos reales
        function renderGraficos() {
          if (!orgId) return;
          const anio = $('#filtroAnio').val();
          const frecuencia = $('#filtroFrecuencia').val();
          $.get(`/org/${orgId}/aguapotable/macromedidor/listar`, { anio, frecuencia }, function(registros) {
            if (registros.length === 0) return;
            // Formatear fechas para los gráficos
            const labels = registros.map(r => {
              if (r.fecha && /^\d{4}-\d{2}-\d{2}$/.test(r.fecha)) {
                const partes = r.fecha.split('-');
                return `${partes[2]}-${partes[1]}-${partes[0]}`;
              } else if (r.fecha && /\d{1,2} de [a-zA-Z]+ de \d{4}/.test(r.fecha)) {
                const meses = {
                  'enero': '01', 'febrero': '02', 'marzo': '03', 'abril': '04', 'mayo': '05', 'junio': '06',
                  'julio': '07', 'agosto': '08', 'septiembre': '09', 'octubre': '10', 'noviembre': '11', 'diciembre': '12'
                };
                const match = r.fecha.match(/(\d{1,2}) de ([a-zA-Z]+) de (\d{4})/);
                if (match) {
                  const dia = match[1].padStart(2, '0');
                  const mes = meses[match[2].toLowerCase()] || '01';
                  const anio = match[3];
                  return `${dia}-${mes}-${anio}`;
                } else {
                  return r.fecha;
                }
              } else {
                return r.fecha;
              }
            });
            const extraccion = registros.map(r => r.extraccion_total);
            const entrega = registros.map(r => r.entrega_total);
            const perdidas = registros.map(r => r.perdidas_total);
            const ultima = registros[0];
            const porcentajeUltima = parseFloat(ultima.porcentaje_perdidas);
            $('#textoPerdidas').html(
              `<b style="color:#1e3a8a">Última lectura (${ultima.fecha}):</b><br>
              <span style="color:#1e3a8a">Entrega:</span> <span style='color:#22c55e'>${ultima.entrega_total} m³</span><br>
              <span style="color:#1e3a8a">Pérdidas:</span> <span style='color:#ef4444'>${ultima.perdidas_total} m³</span><br>
              <span style="color:#1e3a8a">Porcentaje de pérdidas:</span> <span style='color:#ef4444'>${porcentajeUltima}%</span>`
            );
            $('#textoDistribucion').html(
              `<b style="color:#1e3a8a">Última lectura (${ultima.fecha}):</b><br>
              <span style="color:#1e3a8a">Producción:</span> <span style='color:#2563eb'>${ultima.extraccion_total} m³</span><br>
              <span style="color:#1e3a8a">Distribución:</span> <span style='color:#22c55e'>${ultima.entrega_total} m³</span>`
            );
            if (window.perdidasChart) window.perdidasChart.destroy();
            if (window.distribucionChart) window.distribucionChart.destroy();
            const ctxPerdidas = document.getElementById('graficoPerdidas').getContext('2d');
            window.perdidasChart = new Chart(ctxPerdidas, {
              type: 'bar',
              data: {
                labels: labels,
                datasets: [
                  {
                    label: 'Pérdidas (m³)',
                    data: perdidas,
                    backgroundColor: 'rgba(255, 99, 132, 0.6)',
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderWidth: 2
                  },
                  {
                    label: 'Entrega (m³)',
                    data: entrega,
                    backgroundColor: 'rgba(75, 192, 192, 0.4)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 2
                  }
                ]
              },
              options: {
                responsive: true,
                maintainAspectRatio: true,
                aspectRatio: 2,
                plugins: { legend: { position: 'bottom' }, title: { display: true, text: 'Pérdidas de Agua por Lectura' } },
                scales: {
                  x: { title: { display: true, text: 'Fecha' } },
                  y: { title: { display: true, text: 'm³' }, beginAtZero: true }
                }
              }
            });
            const ctxDistribucion = document.getElementById('graficoDistribucion').getContext('2d');
            window.distribucionChart = new Chart(ctxDistribucion, {
              type: 'polarArea',
              data: {
                labels: ['Producción (m³)', 'Distribución (m³)'],
                datasets: [{
                  label: 'Distribución vs Producción',
                  data: [ultima.extraccion_total, ultima.entrega_total],
                  backgroundColor: [
                    'rgba(54, 162, 235, 0.7)',
                    'rgba(75, 192, 192, 0.7)'
                  ],
                  borderColor: [
                    'rgba(54, 162, 235, 1)',
                    'rgba(75, 192, 192, 1)'
                  ],
                  borderWidth: 2
                }]
              },
              options: {
                responsive: true,
                maintainAspectRatio: true,
                aspectRatio: 2,
                plugins: { legend: { position: 'bottom' } }
              }
            });
          });
        }

        // Botón para mostrar registros
          // Botón para mostrar registros
          $('#btnVerRegistros').click(function() {
            $('#vistaFormulario').hide();
            $('#vistaGraficos').hide();
            $('#vistaRegistros').show();
            cargarTablaRegistros();
          });
          // Botón para mostrar gráficos
          $('#btnVerGraficos').click(function() {
            $('#vistaFormulario').hide();
            $('#vistaRegistros').hide();
            $('#vistaGraficos').show();
            renderGraficos();
          });
          // Funciones del botón Volver eliminadas según solicitud
      });
    </script>
</script>
</body>
</html>