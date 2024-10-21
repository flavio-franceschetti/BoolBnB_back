<?php

namespace Database\Seeders;

use App\Models\Apartment;
use App\Models\ApartmentImage;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Config; // Import Config facade

class ApartmentImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Load images from the config
        $images = collect(config('apartment_images')); // Wrap the config array into a collection

        // Get the list of all apartments
        $apartments = Apartment::all();

        foreach ($apartments as $apartment) {
            // Get a random number of images (between 1 and 3)
            $randomImages = $images->random(rand(1, 3));

            foreach ($randomImages as $image) {
                // Create a new ApartmentImage instance
                $new_apartmentImage = new ApartmentImage();
                $new_apartmentImage->apartment_id = $apartment->id;
                $new_apartmentImage->img_path = $image['img_path']; // Use array notation
                $new_apartmentImage->img_name = $image['img_name']; // Use array notation

                // Save the relationship in the database
                $new_apartmentImage->save();
            }
        }
    }
}
