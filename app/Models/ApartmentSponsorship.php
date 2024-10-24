<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApartmentSponsorship extends Model
{
    use HasFactory;

    // definizione nome table
    protected $table = 'apartment_sponsorship';

    // definizione delle fillable
    protected $fillable = [
        'apartment_id',
        'sponsorship_id',
        'end_date',
    ];

    // relazione con apartment
    public function apartment()
    {
        return $this->belongsTo(Apartment::class);
    }

    // relazione con  Sponsorship
    public function sponsorship()
    {
        return $this->belongsTo(Sponsorship::class);
    }

    // Metodo per verificare se una sponsorizzazione è attiva
    public function isActive()
    {
        return $this->end_date > now();
    }
}
