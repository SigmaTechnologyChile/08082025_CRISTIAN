<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Movimiento;
use App\Models\OrderItem;

class AveriguarMetodoPagoMovimiento extends Command
{
    protected $signature = 'movimiento:metodo-pago {descripcion}';
    protected $description = 'Busca el método de pago de un movimiento por descripción';

    public function handle()
    {
        $descripcion = $this->argument('descripcion');
        $mov = Movimiento::where('descripcion', 'like', "%$descripcion%")
            ->orderBy('fecha', 'desc')
            ->first();
        if (!$mov) {
            $this->error('No se encontró el movimiento.');
            return;
        }
        // Buscar el OrderItem relacionado por folio
        $orderItem = OrderItem::where('folio', $mov->nro_dcto)->first();
        if ($orderItem && $orderItem->payment_method_id) {
            $this->info('Método de pago ID: ' . $orderItem->payment_method_id);
        } else {
            $this->warn('No se pudo determinar el método de pago para este movimiento.');
        }
    }
}
