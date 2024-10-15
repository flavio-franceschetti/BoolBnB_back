<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Message;
use Faker\Factory as Faker;

class MessageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        // Ottieni una lista di apartment_id esistenti
        $apartmentIds = DB::table('apartments')->pluck('id')->toArray();

        // recupero i messaggi random dal file config

        $messages = config('messages.messages');

        for ($i = 0; $i < 30; $i++) {
            $new_message = new Message();
            $new_message->apartment_id = $faker->randomElement($apartmentIds);
            $new_message->name = $faker->firstName;
            $new_message->surname = $faker->lastName;
            $new_message->email = $faker->unique()->safeEmail;
            // prende randomicamente un messaggio dal file data config messages.php
            $new_message->content = $faker->randomElement($messages);

            // Salva il nuovo messaggio nel database
            $new_message->save();
        }
    }
}
