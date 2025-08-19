<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Movimiento;

class ListarMovimientosSinCuenta extends Command
{
    protected $signature = 'movimientos:sin-cuenta';
    protected $description = 'Lista los movimientos que no tienen cuenta de origen ni destino asignada';

    public function handle()
    {
        $movimientos = Movimiento::whereNull('cuenta_origen_id')
            ->whereNull('cuenta_destino_id')
            ->orderBy('fecha', 'desc')
            ->get();
        if ($movimientos->isEmpty()) {
            $this->info('Todos los movimientos tienen cuenta asignada.');
            return;
        }
        $this->line("ID | Fecha | Descripción");
        foreach ($movimientos as $mov) {
            $this->line("{$mov->id} | {$mov->fecha} | {$mov->descripcion}");
        }
    }
}
