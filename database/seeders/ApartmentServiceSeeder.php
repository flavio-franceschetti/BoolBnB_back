<?php

namespace Database\Seeders;

use App\Models\Apartment;
use App\Models\Service;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\ApartmentService;
use Illuminate\Database\Seeder;

class ApartmentServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // apartments e services disponibili

        $apartments = Apartment::all();
        $services = Service::all();

        foreach ($apartments as $apartment) {
            // assegno un servizio casuale x ogni apartment associando servizi casuale

            $randomServices = $services->random(rand(1, 3));

            foreach ($randomServices as $service) {
                $new_apartment_service = new ApartmentService();
                $new_apartment_service->apartment_id = $apartment->id;
                $new_apartment_service->service_id = $service->id;

                // salvo la relazione nel db

                $new_apartment_service->save();
            }
        }
    }
}
