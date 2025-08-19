<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class MovimientosYExtractosSeeder extends Seeder
{
    public function run()
    {
        // Poblar movimientos
        DB::table('movimientos')->insert([
            [
                'org_id' => 1,
                'fecha' => Carbon::now()->subDays(5),
                'tipo' => 'ingreso',
                'monto' => 150000,
                'descripcion' => 'Pago de socio Juan Pérez',
                'cuenta' => 'Cta. Corriente',
                'conciliado' => 1,
            ],
            [
                'org_id' => 1,
                'fecha' => Carbon::now()->subDays(3),
                'tipo' => 'egreso',
                'monto' => -50000,
                'descripcion' => 'Pago de factura de luz',
                'cuenta' => 'Cta. Corriente',
                'conciliado' => 0,
            ],
            [
                'org_id' => 1,
                'fecha' => Carbon::now()->subDays(1),
                'tipo' => 'ingreso',
                'monto' => 200000,
                'descripcion' => 'Transferencia bancaria',
                'cuenta' => 'Cta. Ahorro',
                'conciliado' => 1,
            ],
        ]);

        // Poblar extractos
        DB::table('extractos')->insert([
            [
                'org_id' => 1,
                'fecha' => Carbon::now()->subDays(5),
                'descripcion' => 'Abono Juan Pérez',
                'monto' => 150000,
                'conciliado' => 1,
            ],
            [
                'org_id' => 1,
                'fecha' => Carbon::now()->subDays(3),
                'descripcion' => 'Pago factura luz',
                'monto' => -50000,
                'conciliado' => 0,
            ],
            [
                'org_id' => 1,
                'fecha' => Carbon::now()->subDays(1),
                'descripcion' => 'Transferencia bancaria',
                'monto' => 200000,
                'conciliado' => 1,
            ],
        ]);
    }
}
