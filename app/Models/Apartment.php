<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

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
        'last_sponsorship',
        'sponsorship_price',
        'sponsorship_hours',
    ];

    public function images()
    {
        return $this->hasMany(ApartmentImage::class);
    }

    public function sponsorships()
    {
        return $this->belongsToMany(Sponsorship::class, 'apartment_sponsorship')
            ->withPivot('sponsorship_hours', 'end_date')
            ->withTimestamps();
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function views()
    {
        return $this->hasMany(View::class);
    }

    public function getTotalViews()
    {
        return $this->views()->count();
    }

    public function getDailyViews()
    {
        return $this->views()->whereDate('created_at', now()->format('Y-m-d'))->count();
    }

    public function services()
    {
        return $this->belongsToMany(Service::class, 'apartment_service')
            ->withTimestamps();
    }

    // Calcola le ore residue di sponsorizzazione
    public function getRemainingSponsorshipHours()
    {
        $sponsorshipHours = 0;

        foreach ($this->sponsorships as $sponsorship) {
            $endDate = $sponsorship->pivot->end_date;

            // Log delle sponsorizzazioni attive
            Log::info('Controllo sponsorizzazione: ', [
                'sponsorship_id' => $sponsorship->id,
                'end_date' => $endDate,
                'is_active' => $endDate > now(),
            ]);

            if ($endDate > now()) {
                $sponsorshipHours += $sponsorship->duration;
            }
        }

        return $sponsorshipHours;
    }

    // Metodo per estendere una sponsorizzazione esistente o crearne una nuova
    public function extendSponsorship(Sponsorship $sponsorship)
    {
        // Trova la sponsorizzazione più recente
        $currentSponsorship = $this->sponsorships()
            ->where('sponsorship_id', $sponsorship->id)
            ->latest('pivot_end_date')
            ->first();

        if ($currentSponsorship && $currentSponsorship->pivot->end_date > now()) {
            // Se esiste una sponsorizzazione attiva, estendi la durata
            $newEndDate = Carbon::parse($currentSponsorship->pivot->end_date)->addSeconds($sponsorship->duration);
            $currentSponsorship->pivot->end_date = $newEndDate;
            $currentSponsorship->pivot->sponsorship_hours += $sponsorship->duration;
            $currentSponsorship->pivot->save();
        } else {
            // Altrimenti, crea una nuova associazione
            $this->sponsorships()->attach($sponsorship->id, [
                'end_date' => Carbon::now()->addSeconds($sponsorship->duration),
                'sponsorship_hours' => $sponsorship->duration,
            ]);
        }
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
