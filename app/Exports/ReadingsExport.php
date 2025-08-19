<?php

namespace App\Exports;

use App\Models\Reading;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ReadingsExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return \App\Models\Reading::select([
            'id',
            'org_id',
            'member_id',
            'service_id',
            'period',
            'previous_reading',
            'current_reading',
            'cm3',
            'vc_water',
            'total_mounth',
            'sub_total',
            'total',
            'created_at',
            'updated_at'
        ])->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Org ID',
            'Member ID',
            'Service ID',
            'Periodo',
            'Lectura Anterior',
            'Lectura Actual',
            'CM3',
            'VC Water',
            'Total Mes',
            'Sub Total',
            'Total',
            'Created At',
            'Updated At'
        ];
    }
}
