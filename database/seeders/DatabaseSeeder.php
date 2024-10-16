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
            // seeder User
            UserSeeder::class,
            // seeder Apartment
            ApartmentSeeder::class,
            // seeder for sponsors upgrade packages
            SponsorshipSeeder::class,
            // seeder tabella pivot apartment_sponsorship
            ApartmentSponsorshipSeeder::class,
            // seeder for services
            ServiceSeeder::class,
            // seeder tabella pivot apartment_service
            ApartmentServiceSeeder::class,
            // seeder for view
            ViewSeeder::class,
            // seeder for messages
            MessageSeeder::class,
        ]);
    }
}
