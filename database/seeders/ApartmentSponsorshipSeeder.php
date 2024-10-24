<?php

namespace Database\Seeders;

use App\Models\Apartment;
use App\Models\ApartmentSponsorship;
use App\Models\Sponsorship;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class ApartmentSponsorshipSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Recupero tutti gli apartment e sponsorship dalla configurazione
        $apartments = Apartment::all();
        $sponsorships = Sponsorship::all();

        // Associo ogni appartamento con una sponsorship randomica
        foreach ($apartments as $apartment) {
            // Seleziona una sponsorizzazione casuale
            $sponsorship = $sponsorships->random();

            // Calcola sponsorship_hours, assumendo che 'duration' sia in ore
            $sponsorshipHours = $sponsorship->duration; // Durata in ore

            // Calcola end_date basandoti sulle ore di sponsorizzazione
            $endDate = Carbon::now()->addHours($sponsorshipHours);

            // Crea o aggiorna la relazione nel database
            $apartment->sponsorships()->attach($sponsorship->id, [
                'end_date' => $endDate,
                'sponsorship_hours' => $sponsorshipHours,
            ]);

            // Modifica l'appartamento se necessario
            $apartment->update([
                'last_sponsorship' => $sponsorship->name, // Aggiungi un campo per tenere traccia dell'ultima sponsorizzazione
                'sponsorship_price' => $sponsorship->price, // Aggiungi un campo per il prezzo della sponsorizzazione
                // Puoi aggiungere ulteriori modifiche necessarie all'appartamento
            ]);
        }
    }
}
