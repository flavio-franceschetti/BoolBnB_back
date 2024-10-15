<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            // seeder for sponsors upgrate packages
            SponsorshipSeeder::class,
            // seeder for view
            ViewSeeder::class,
            // seeder for services
            ServiceSeeder::class,
            // seeder messages
            MessageSeeder::class,
            // seeder Apartment
            ApartmentSeeder::class,

        ]);
    }
}
