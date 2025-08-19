<?php

namespace App\Exports;

use App\Models\Service;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Facades\DB;

class ServiceExport implements FromCollection, WithHeadings
{
        protected $filters;

    public function __construct($filters = [])
    {
        $this->filters = $filters;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $query = DB::table('services')
            ->join('members', 'services.member_id', '=', 'members.id')
            ->join('locations', 'services.locality_id', '=', 'locations.id')
            ->select(
                'services.nro as ID Servicio',
                'members.full_name as Miembro',
                'locations.name as Sector',
                'services.meter_number as N° Medidor',
                'services.invoice_type as Boleta/Factura',
                'services.meter_plan as MIDEPLAN',
                'services.percentage as Porcentaje',
                'services.diameter as Diámetro'
            );

        if (!empty($this->filters['org_id'])) {
            $query->where('services.org_id', $this->filters['org_id']);
        }
        if (!empty($this->filters['sector'])) {
            $query->where('services.locality_id', $this->filters['sector']);
        }
        if (!empty($this->filters['nro'])) {
            $query->where('services.nro', 'like', '%' . $this->filters['nro'] . '%');
        }
        if (!empty($this->filters['search'])) {
            $search = $this->filters['search'];
            $query->where(function($q) use ($search) {
                $q->where('members.full_name', 'like', "%$search%")
                  ->orWhere('members.rut', 'like', "%$search%");
            });
        }
        if (!empty($this->filters['sort']) && !empty($this->filters['order'])) {
            $query->orderBy($this->filters['sort'], $this->filters['order']);
        }

        return collect($query->get());
    }
    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            "ID Servicio", 
            "Miembro", 
            "Sector", 
            "N° Medidor", 
            "Boleta/Factura", 
            "MIDEPLAN", 
            "Porcentaje", 
            "Diámetro"
        ];
    }
}