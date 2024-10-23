<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Log;

class Apartment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'title',
        'slug',
        'rooms',
        'beds',
        'bathrooms',
        'mq',
        'address',
        'longitude',
        'latitude',
        'is_visible',
        'sponsorship_hours',
    ];

    public function images()
    {
        return $this->hasMany(ApartmentImage::class);
    }

    public function sponsorships()
    {
        return $this->belongsToMany(Sponsorship::class, 'apartment_sponsorship')
            ->withPivot('end_date');
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function views()
    {
        return $this->hasMany(View::class);
    }

    public function services()
    {
        return $this->belongsToMany(Service::class, 'apartment_service')
            ->withTimestamps();
    }

    // Aggiungi questo metodo per calcolare le ore residue di sponsorizzazione
    public function getRemainingSponsorshipHours()
    {
        $sponsorshipHours = 0;

        foreach ($this->sponsorships as $sponsorship) {
            $endDate = $sponsorship->pivot->end_date;

            // Log delle sponsorizzazioni
            Log::info('Controllo sponsorizzazione: ', [
                'sponsorship_id' => $sponsorship->id,
                'end_date' => $endDate,
                'is_active' => $endDate > now(),
            ]);

            if ($endDate > now()) {
                $sponsorshipHours += $sponsorship->duration; // Assicurati che 'duration' sia il campo corretto
            }
        }

        return $sponsorshipHours;
    }
}
