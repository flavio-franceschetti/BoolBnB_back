<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class View extends Model
{
    use HasFactory;

    // definizione fillable per view

    protected $fillable = [
        'apartment_id', // ID dell'appartamento associato
        'ip_address',   // Indirizzo IP
    ];

    // relazione con apartment

    public function apartment()
    {

        // Una view ha un appartamento / l' appartamento ha piu visualizzazioni
        return $this->belongsTo(Apartment::class);
    }
}
