<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reading extends Model
{
    public $timestamps = true;

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    protected $fillable = [
        'org_id', 'member_id', 'service_id', 'period', 'current_reading', 'previous_reading', 'cm3',
        // agrega aquí otros campos relevantes de la tabla readings
    ];
}
