<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Movimiento;
use App\Models\Cuenta;

class ActualizarCuentasMovimientos extends Command
{
    protected $signature = 'movimientos:actualizar-cuentas';
    protected $description = 'Actualiza los campos cuenta_destino_id en la tabla movimientos según el método de pago';

    public function handle()
    {
        $corriente = Cuenta::porTipo('corriente')->first();
        $caja = Cuenta::porTipo('caja')->first();

        // POS (incluye pagos y ventas)
        $pos = Movimiento::where(function($q) {
            $q->where('descripcion', 'like', '%POS%')
              ->orWhere('descripcion', 'like', '%VTA POS%');
        })->get();
        foreach ($pos as $mov) {
            $mov->cuenta_destino_id = $corriente ? $corriente->id : null;
            $mov->save();
        }

        // Efectivo (incluye pagos y ventas)
        $efectivo = Movimiento::where(function($q) {
            $q->where('descripcion', 'like', '%Efectivo%')
              ->orWhere('descripcion', 'like', '%VTA EFECTIVO%');
        })->get();
        foreach ($efectivo as $mov) {
            $mov->cuenta_destino_id = $caja ? $caja->id : null;
            $mov->save();
        }

        $this->info('Movimientos actualizados correctamente.');
    }
}
