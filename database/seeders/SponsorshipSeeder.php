<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Sponsorship;
use App\Functions\Helper;

class SponsorshipSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sponsorships = config('sponsorships.sponsorships');

        foreach ($sponsorships as $sponsorship) {
            $new_sponsorship = new Sponsorship();
            $new_sponsorship->name = $sponsorship['name'];
            $new_sponsorship->slug = Helper::generateSlug($sponsorship['name'], Sponsorship::class);
            $new_sponsorship->price = $sponsorship['price'];
            $new_sponsorship->duration = $sponsorship['duration'];
            $new_sponsorship->description = $sponsorship['description'];
            $new_sponsorship->slogans = json_encode($sponsorship['slogans']);

            // Salva il nuovo pacchetto di sponsorizzazione nel database
            $new_sponsorship->save();
        }
    }
}
