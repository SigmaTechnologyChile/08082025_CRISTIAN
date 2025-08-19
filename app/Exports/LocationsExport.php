<?php

namespace App\Exports;

use App\Models\Location;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class LocationsExport implements FromCollection, WithHeadings
{
    protected $orgId;
    protected $search;

    public function __construct($orgId = null, $search = null)
    {
        $this->orgId = $orgId;
        $this->search = $search;
    }

    public function collection()
    {
        $query = Location::query();
        if ($this->orgId) {
            $query->where('org_id', $this->orgId);
        }
        if ($this->search) {
            $query->where('name', 'like', '%'.$this->search.'%');
        }
        return $query->select('id', 'name')->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Sector'
        ];
    }
}
