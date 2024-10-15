<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    // definizioni fillable service

    protected $fillable = [
        'name',
    ];

    // relazione con apartment

    public function apartment()

    {

        // relazione con tabella pivot
        return $this->belongsToMany(Apartment::class, 'apartment_service');
    }
}
