<?php

namespace Database\Seeders;

use App\Models\Apartment;
use App\Models\ApartmentSponsorship;
use App\Models\Sponsorship;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ApartmentSponsorshipSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // recupero tutti gli apartment e sponsorship dalla configurazione

        $apartments = Apartment::all();
        $sponsorships = Sponsorship::all();

        // associo ogni appartamento con una sponsorship randomica

        foreach ($apartments as $apartment) {
            $new_apartment_sponsorship = new ApartmentSponsorship();
            $new_apartment_sponsorship->apartment_id = $apartment->id;
            $new_apartment_sponsorship->sponsorship_id = $sponsorships->random()->id;
            // imposto una random end_date
            $new_apartment_sponsorship->end_date = Carbon::now()->addDays(rand(1, 30));

            // salvo la relazione del db

            $new_apartment_sponsorship->save();
        }
    }
}
