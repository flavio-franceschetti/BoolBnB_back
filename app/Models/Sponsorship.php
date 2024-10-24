<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sponsorship extends Model
{
    use HasFactory;

    // definizione delle fillable per sponsorship

    protected $fillable = [
        'name',
        'slug',
        'hours',
        'price',
        'duration',
    ];


    // relazione con apartment

    public function apartment()
    {
        // una sponsorizzazione appartiene ad un appartamento
        return $this->belongsToMany(Apartment::class, 'apartment_sponsorship')
            ->withPivot('end_date')
            ->withTimestamps();
    }
}
