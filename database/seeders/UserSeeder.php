<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crea un'istanza di Faker
        $faker = Faker::create();

        // Crea 10 utenti con dati casuali
        for ($i = 0; $i < 10; $i++) {
            User::create([
                'name' => $faker->name, // Genera un nome casuale
                'surname' => $faker->lastName, // Genera un cognome casuale
                'email' => $faker->unique()->safeEmail, // Genera un'email unica
                'password' => Hash::make('Password123'), // Password fissa per tutti gli utenti
                'date_of_birth' => $faker->date(), // Genera una data di nascita casuale
            ]);
        }
    }
}
