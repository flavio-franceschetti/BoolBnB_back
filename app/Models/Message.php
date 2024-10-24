<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Message extends Model
{
    use SoftDeletes;
    use HasFactory;

    // definizione di fillable per message

    protected $fillable = [
        'apartment_id', // ID dell'appartamento associato
        'name',        // Nome del mittente
        'surname',     // Cognome del mittente
        'email',       // Email del mittente
        'content',     // Contenuto del messaggio
    ];

    // relazione con apartment

    public function apartment()
    {

        // un appartamento piu messaggi / un mess un appartamento
        return $this->belongsTo(Apartment::class);
    }
}
