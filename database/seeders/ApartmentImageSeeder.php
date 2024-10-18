<?php

namespace Database\Seeders;

use App\Models\Apartment;
use App\Models\ApartmentImage;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class ApartmentImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        // ottengo la lista degli id di apartments
        $apartments = Apartment::all();
        // Crea un'istanza di Faker
        $faker = Faker::create();

        // Crea da 1 a 3 img random per apartmnet
        foreach ($apartments as $apartment) {
            // Generate a random number of images between 1 and 3
            $numberOfImgs = rand(1, 3); // Randomly choose between 1 and 3 images

            // Create the images for the apartment
            for ($i = 1; $i <= $numberOfImgs; $i++) {
                ApartmentImage::create([
                    'apartment_id' => $apartment->id,
                    'img_path' => $faker->imageUrl(),
                    'img_name' => $faker->word,
                ]);
            }
        }
    }
}
