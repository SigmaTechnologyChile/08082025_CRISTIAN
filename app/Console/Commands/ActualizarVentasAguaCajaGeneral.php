<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Movimiento;
use App\Models\Cuenta;

class ActualizarVentasAguaCajaGeneral extends Command
{
    protected $signature = 'movimientos:ventas-agua-caja';
    protected $description = 'Actualiza los movimientos VENTA DE AGUA para que tengan como destino Caja General';

    public function handle()
    {
        $cajaGeneral = Cuenta::where('nombre', 'like', '%Caja General%')->first();
        if (!$cajaGeneral) {
            $this->error('No se encontró la cuenta Caja General.');
            return;
        }
        $ventasAgua = Movimiento::where('descripcion', 'like', '%VENTA DE AGUA%')->get();
        $total = 0;
        foreach ($ventasAgua as $mov) {
            $mov->cuenta_destino_id = $cajaGeneral->id;
            $mov->save();
            $total++;
        }
        $this->info("Movimientos actualizados: $total");
    }
}
