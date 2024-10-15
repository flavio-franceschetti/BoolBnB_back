<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SponsorshipSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Dati per la tabella sponsorships
        $sponsorships = [
            [
                'name' => 'Bronze Package',
                'slug' => Str::slug('Bronze Package'),
                'price' => 2.99,
                'duration' => 24, // in ore
            ],
            [
                'name' => 'Silver Package',
                'slug' => Str::slug('Silver Package'),
                'price' => 5.99,
                'duration' => 72, // in ore
            ],
            [
                'name' => 'Gold Package',
                'slug' => Str::slug('Gold Package'),
                'price' => 9.99,
                'duration' => 144, // in ore
            ],
        ];

        // Inserimento dei dati nella tabella
        DB::table('sponsorships')->insert($sponsorships);
    }
}
