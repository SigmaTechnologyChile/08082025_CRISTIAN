@extends('layouts.app')
@php($title = 'Editar Cuenta Inicial')
@php($active = '')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <h2><i class="bi bi-pencil"></i> Editar Cuenta Inicial</h2>
            <a href="{{ route('configuracion-inicial.index') }}" class="btn btn-secondary float-end">Volver</a>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('configuracion-inicial.update', $configuracion->id) }}">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label for="cuenta_id" class="form-label">Cuenta</label>
                    <select name="cuenta_id" id="cuenta_id" class="form-control" required>
                        @foreach($cuentas as $cuenta)
                            <option value="{{ $cuenta->id }}" {{ $configuracion->cuenta_id == $cuenta->id ? 'selected' : '' }}>{{ $cuenta->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label for="saldo_inicial" class="form-label">Saldo Inicial</label>
                    <input type="number" step="0.01" name="saldo_inicial" id="saldo_inicial" class="form-control" value="{{ old('saldo_inicial', $configuracion->saldo_inicial) }}" required>
                </div>
                <div class="mb-3">
                    <label for="banco_id" class="form-label">Banco</label>
                    <select name="banco_id" id="banco_id" class="form-control" required>
                        <option value="">Seleccione banco</option>
                        @foreach($bancos as $banco)
                            <option value="{{ $banco->id }}" {{ $configuracion->banco_id == $banco->id ? 'selected' : '' }}>{{ $banco->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label for="numero_cuenta" class="form-label">Número de Cuenta</label>
                    <input type="text" name="numero_cuenta" id="numero_cuenta" class="form-control" value="{{ old('numero_cuenta', $configuracion->numero_cuenta) }}">
                </div>
                <div class="mb-3">
                    <label for="tipo_cuenta" class="form-label">Tipo de Cuenta</label>
                    <select name="tipo_cuenta" id="tipo_cuenta" class="form-control" required>
                        <option value="caja_general" {{ $configuracion->tipo_cuenta == 'caja_general' ? 'selected' : '' }}>Caja General</option>
                        <option value="cuenta_corriente_1" {{ $configuracion->tipo_cuenta == 'cuenta_corriente_1' ? 'selected' : '' }}>Cuenta Corriente 1</option>
                        <option value="cuenta_corriente_2" {{ $configuracion->tipo_cuenta == 'cuenta_corriente_2' ? 'selected' : '' }}>Cuenta Corriente 2</option>
                        <option value="cuenta_ahorro" {{ $configuracion->tipo_cuenta == 'cuenta_ahorro' ? 'selected' : '' }}>Cuenta de Ahorro</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="responsable" class="form-label">Responsable</label>
                    <input type="text" name="responsable" id="responsable" class="form-control" value="{{ old('responsable', $configuracion->responsable) }}" required>
                </div>
                <button type="submit" class="btn btn-success"><i class="bi bi-save"></i> Guardar Cambios</button>
            </form>
        </div>
    </div>
</div>
@endsection
