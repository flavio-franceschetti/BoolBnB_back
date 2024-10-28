<?php

namespace App\Http\Controllers\Api;

use App\Functions\Helper;
use App\Http\Controllers\Controller;
use App\Models\Apartment;
use App\Models\Service;
use Illuminate\Http\Request;

class PageController extends Controller
{
    // chiamata di tutti gli appartamenti visibili
    public function index()
    {
        $apartments = Apartment::whereNotNull('sponsorship_price')->with('services', 'images')->get();

        // Preparo la stringa per le immagini
        foreach ($apartments as $apartment) {
            foreach ($apartment->images as $img) {
                $img->img_path = url('storage/' . $img->img_path);
            }
        }

        // validazione API
        $success = $apartments->isNotEmpty();
        return response()->json(compact('success', 'apartments'));
    }

    public function services()
    {
        $services = Service::all();

        $success = $services->isNotEmpty();
        return response()->json(compact('success', 'services'));
    }

    public function apartmentById($id)
    {
        $apartment = Apartment::where('id', $id)->where('is_visible', true)->with('services', 'images')->first();

        // Verifica se l'appartamento esiste
        if ($apartment) {
            // Preparo la stringa per il percorso delle immagini
            foreach ($apartment->images as $img) {
                $img->img_path = url('storage/' . $img->img_path);
            }
            $success = true;
        } else {
            $success = false;
            $apartment = null; // Assicurarsi che l'appartamento sia null in caso di fallimento
        }
        return response()->json(compact('success', 'apartment'));
    }

    public function apartmentBySlug($slug)
    {
        $apartment = Apartment::where('slug', $slug)->where('is_visible', true)->with('services', 'images')->first();

        // Verifica se l'appartamento esiste
        if ($apartment) {
            // Preparo la stringa per il percorso delle immagini
            foreach ($apartment->images as $img) {
                $img->img_path = url('storage/' . $img->img_path);
            }
            $success = true;
        } else {
            $success = false;
            $apartment = null; // Assicurarsi che l'appartamento sia null in caso di fallimento
        }
        return response()->json(compact('success', 'apartment'));
    }
    // chiamata di tutti gli appartamenti visibili
    public function apartmentsByAddress(Request $request)
    {
        // Recupero la chiave API e l'indirizzo dall'input della richiesta
        $apiKey = config('app.tomtomapikey');
        $address = $request->query('address');

        // Rimuovo le virgole e caratteri speciali dall'indirizzo
        $cleanAddress = preg_replace('/[^\w\s]/', '', $address); // rimuovo i caratteri speciali
        $cleanAddress = str_replace(',', '', $cleanAddress); // rimuovo le virgole

        // Ottengo latitudine e longitudine dall'API TomTom in base all'indirizzo
        $latitude = Helper::getLatLon($cleanAddress, $apiKey, 'lat');
        $longitude = Helper::getLatLon($cleanAddress, $apiKey, 'lon');

        // Parametri regolabili dalla query con valori predefiniti
        $distance = $request->query('distance');
        $rooms = $request->query('rooms');
        $beds = $request->query('beds');
        $services = $request->query('services');  // Nessun default, filtro se fornito (lista separata da virgole)

        // Suddivido l'indirizzo pulito in parole per una ricerca flessibile
        $addressWords = explode(' ', $cleanAddress);  // Divido l'input in parole

        // Query di base per gli appartamenti
        $apartments = Apartment::where('is_visible', true) // Solo appartamenti visibili

            // Filtro per ogni parola nell'indirizzo (in qualsiasi ordine)
            // ->where(function ($query) use ($addressWords) {
            //     foreach ($addressWords as $word) {
            //         $query->orWhere('address', 'LIKE', "%{$word}%");  // Aggiungo un 'where' per ogni parola
            //     }
            // })

            // Filtro per numero minimo di stanze e letti
            ->where('rooms', '>=', $rooms)  // Almeno `rooms` numero di stanze
            ->where('beds', '>=', $beds)  // Almeno `beds` numero di letti

            // Filtro per distanza utilizzando la formula di Haversine
            ->select('*')
            ->selectRaw("(
            6371 * acos(cos(radians(?)) * cos(radians(latitude))
            * cos(radians(longitude) - radians(?))
            + sin(radians(?)) * sin(radians(latitude)))
        ) AS distance", [$latitude, $longitude, $latitude])
            ->having('distance', '<', $distance)  // Filtro per distanza dalla posizione fornita

            // Filtro opzionale per servizi (se fornito)
            ->when($services, function ($query) use ($services) {
                $serviceList = explode(',', $services);  // Divido la lista di servizi separati da virgole
                return $query->whereHas('services', function ($query) use ($serviceList) {
                    $query->whereIn('name', $serviceList);  // Cerco tra i servizi forniti
                });
            })
            // ->orderByDesc('sponsorship_price')
            ->orderBy('distance') // Ordina per distanza calcolata
            // Carico le relazioni (servizi, immagini)
            ->with('services', 'images')  // Includo servizi e immagini collegati
            ->get();

        // Preparo la stringa per le immagini
        foreach ($apartments as $apartment) {
            foreach ($apartment->images as $img) {
                $img->img_path = url('storage/' . $img->img_path);
            }
        }

        // validazione API
        $success = $apartments->isNotEmpty();
        return response()->json(compact('success', 'apartments'));
    }
}
