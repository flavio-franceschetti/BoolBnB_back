<?php

namespace App\Functions;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;



class Helper
{
    // funzione per creare lo slug
    public static function generateSlug($string, $model)
    {
        $slug = Str::slug($string, '-');
        $original_slug = $slug;

        $exists = $model::where('slug', $slug)->first();
        $c = 1;

        while ($exists) {
            $slug = $original_slug . '-' . $c;
            $exists = $model::where('slug', $slug)->first();
            $c++;
        }

        return $slug;
    }

    // funzione per recuperare la latitudine e la longitudine con l'api di tom tom
    public static function getLatLon($address, $apiKey, $param)
    {
        // il path base dell'api
        $api_path = 'https://api.tomtom.com/search/2/geocode/';
        // utilizzo la facade Http per poter fare richieste alle api e gli passo il path concatenato con l'indirzzo e la $apiKey generata dal sito tomtom
        $response = Http::withOptions(['verify' => false])->get($api_path . $address . '.json', [
            'key' => $apiKey,
        ])->json();
        // ritorno la latitudine presa dalla risposta dell'api
        return $response['results'][0]['position'][$param];
    }

   
}
