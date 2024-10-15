<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Service;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // servizi degli appartamenti
        $services = config('apartment_services');

        foreach ($services as $service) {
            $new_service = new Service();
            $new_service->name = $service['name'];

            // Salva il nuovo servizio nel database
            $new_service->save();
        }
    }
}
