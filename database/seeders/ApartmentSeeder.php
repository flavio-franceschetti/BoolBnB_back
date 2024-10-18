<?php

namespace Database\Seeders;

use App\Functions\Helper;
use App\Models\Apartment;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ApartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $apartments = config('apartment');
        foreach ($apartments as $apartment) {
            $new_apartment = new Apartment();
            // Assegna un utente casuale
            $new_apartment->user_id = User::inRandomOrder()->first()->id;
            $new_apartment->title = $apartment['title'];
            $new_apartment->slug = Helper::generateSlug($new_apartment->title, Apartment::class);
            $new_apartment->rooms = $apartment['rooms'];
            $new_apartment->beds = $apartment['beds'];
            $new_apartment->bathrooms = $apartment['bathrooms'];
            $new_apartment->mq = $apartment['mq'];
            $new_apartment->address = $apartment['address'];
            $new_apartment->latitude = $apartment['latitude'];
            $new_apartment->longitude = $apartment['longitude'];
            $new_apartment->is_visible = $apartment['is_visible'];
            // Salva il nuovo appartamento nel database
            $new_apartment->save();
        }
    }
}
