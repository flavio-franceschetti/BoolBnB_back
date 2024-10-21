<?php

namespace App\Http\Controllers\Api;

use App\Functions\Helper;
use App\Http\Controllers\Controller;
use App\Models\Apartment;
use Illuminate\Http\Request;

class PageController extends Controller
{
    // chiamata di tutti gli appartamenti visibili
    public function index()
    {
        $apartments = Apartment::where('is_visible', true)->with('services', 'images')->get();

        // Preparo la stringa per le immagini
        foreach ($apartments as $apartment) {
            foreach ($apartment->images as $img) {
                $img->img_path = asset('storage/' . $img->img_path);
            }
        }

        // validazione API
        $success = $apartments->isNotEmpty();
        return response()->json(compact('success', 'apartments'));
    }

    public function apartmentById($id)
    {
        $apartment = Apartment::where('id', $id)->where('is_visible', true)->with('services', 'images')->first();

        // Verifica se l'appartamento esiste
        if ($apartment) {
            // Preparo la stringa per il percorso delle immagini
            foreach ($apartment->images as $img) {
                $img->img_path = asset('storage/' . $img->img_path);
            }
            $success = true;
        } else {
            $success = false;
            $apartment = null; // Assicurarsi che l'appartamento sia null in caso di fallimento
        }
        return response()->json(compact('success', 'apartment'));
    }

    // chiamata di tutti gli appartamenti visibili
    public function apartmentsByAddress($address)
    {
        // Prendo la latitudine e longitudine dall'indirizzo inserito dall'utente
        // utilizzo le funzioni create nell'helper per prendermi la latitudine e la longitudine dall'api di tomtom
        $apiKey = env('TOMTOM_API_KEY');
        $latitude = Helper::getLatLon($address, $apiKey, 'lat');
        $longitude = Helper::getLatLon($address, $apiKey, 'lon');
        // aggiungo la distanza del raggio di ricerca
        $distance = 20;

        // Filtro gli appartamenti
        $apartments = Apartment::where('is_visible', true) //seleziono solo quelli visibili
            ->where('address', 'LIKE', "%{$address}%") // in address deve essere contenuto l'indirizzo immesso
            ->select('*') // seleziono tutto
            ->selectRaw("(
                6371 * acos(cos(radians(?)) * cos(radians(latitude))
                * cos(radians(longitude) - radians(?))
                + sin(radians(?)) * sin(radians(latitude)))
            ) AS distance", [$latitude, $longitude, $latitude]) // calcolo la distanza dall'indirizzo fornito basandomi sulla lat e lon
            ->having('distance', '<', $distance) // filtro tutti gli indirizzi che hanno una distanza minore di quella inserita nella variabile
            ->with('services', 'images') // importo anche i dati di servizi e immagini
            ->get();

        // Preparo la stringa per le immagini
        foreach ($apartments as $apartment) {
            foreach ($apartment->images as $img) {
                $img->img_path = asset('storage/' . $img->img_path);
            }
        }

        // validazione API
        $success = $apartments->isNotEmpty();
        return response()->json(compact('success', 'apartments'));
    }
}
