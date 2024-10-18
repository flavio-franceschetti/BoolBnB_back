<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApartmentService extends Model
{
    use HasFactory;

    // definisco il nome della tabella

    protected $table = 'apartment_service';

    // definisco le fillable

    protected $fillable = [
        'apartment_id',
        'service_id',
    ];

    // relaziono con apartment

    public function apartment()
    {
        return $this->belongsTo(Apartment::class);
    }

    // relaziono con service

    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}
