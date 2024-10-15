<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use App\Models\View;

class ViewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $faker = Faker::create();

        // Ottiengo una lista di apartment_id esistenti
        $apartmentIds = DB::table('apartments')
            ->select('id', 'created_at')
            ->get();

        // Inserisco un numero di visualizzazioni fittizie
        for ($i = 0; $i < 30; $i++) {
            $new_view = new View();
            // Collegamento con apartment_id esistente
            $new_view->apartment_id = $faker->randomElement($apartmentIds);
            // Indirizzo IP fittizio
            $new_view->ip_address = $faker->ipv4;
            $new_view->created_at = now();
            $new_view->updated_at = now();

            // Salva  nel database
            $new_view->save();
        }
    }
}
