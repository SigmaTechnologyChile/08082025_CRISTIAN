<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Movimiento;
use App\Models\Cuenta;

class ListarMovimientosCuentas extends Command
{
    protected $signature = 'movimientos:listar-cuentas';
    protected $description = 'Lista los últimos 20 movimientos con sus cuentas de origen y destino';

    public function handle()
    {
        $movimientos = Movimiento::orderBy('fecha', 'desc')->take(20)->get();
        $this->line("ID | Fecha | Descripción | Origen | Destino");
        foreach ($movimientos as $mov) {
            $origen = $mov->cuentaOrigen ? $mov->cuentaOrigen->nombre : '-';
            $destino = $mov->cuentaDestino ? $mov->cuentaDestino->nombre : '-';
            $this->line("{$mov->id} | {$mov->fecha} | {$mov->descripcion} | {$origen} | {$destino}");
        }
    }
}
