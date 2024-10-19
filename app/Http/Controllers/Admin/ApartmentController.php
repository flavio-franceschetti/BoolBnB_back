<?php

namespace App\Http\Controllers\Admin;

use App\Functions\Helper;
use App\Models\Apartment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\ApartmentRequest;
use App\Models\ApartmentImage;
use App\Models\ApartmentService;
use App\Models\Service;
use App\Models\Sponsorship;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class ApartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // totale progetti presenti per utente
        $apartments = Apartment::orderBy('id', 'desc')->where('user_id', Auth::id())->get();

        // $apartments = Apartment::orderBy('id', 'desc')->get();

        return view('admin.apartments.index', compact('apartments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $services = Service::all();
        $sponsorships = Sponsorship::all();
        return view('admin.apartments.create', compact('services', 'sponsorships'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ApartmentRequest $request)
    {
        // inserisco tutti i dati della richiesta dentro la variabile $data
        $data = $request->validated();

        // creo un variabile per i dati dell'appartamento
        $apartmentData = [
            'title' => $data['title'],
            'rooms' => $data['rooms'],
            'beds' => $data['beds'],
            'bathrooms' => $data['bathrooms'],
            'mq' => $data['mq'],
            'address' => $data['address'],
            'is_visible' => $data['is_visible'],
        ];

        // creo una variabile per i dati delle immagini
        $images = $data['images'];

        // genero lo slug prendendo il titolo dell appartamento con l'helper inserito in funcion
        $apartmentData['slug'] = Helper::generateSlug($apartmentData['title'], Apartment::class);
        // assegno all'user id l'id dell'utente che sta creando il nuovo annuncio
        $apartmentData['user_id'] = Auth::id();

        // Prendo la latitudine e longitudine dall'indirizzo inserito dall'utente
        $address = $apartmentData['address'];
        $apiKey = env('TOMTOM_API_KEY');
        // utilizzo le funzioni create nell'helper per prendermi la latitudine e la longitudine dall'api di tomtom
        $apartmentData['latitude'] = Helper::getLatLon($address, $apiKey, 'lat');
        $apartmentData['longitude'] = Helper::getLatLon($address, $apiKey, 'lon');

        // creo un nuovo appartamento
        $new_apartment = Apartment::create($apartmentData);

        //gestisco i servizi
        if (array_key_exists('services', $apartmentData)) {
            $new_apartment->services()->attach($apartmentData['services']);
        }

        // gestisco la tabella apartment_images
        foreach ($images as $img) {
            // salvo in path nello store
            $img_path = Storage::put('uploads', $img);
            // salvo il nome del file
            $img_name = $img->getClientOriginalName();
            // creo le nuove immagini in relazione all'appartamento
            ApartmentImage::create([
                'apartment_id' => $new_apartment->id, // Set the apartment ID
                'img_path' => $img_path, // Store the image path
                'img_name' => $img_name, // salva nel db il nome dell'immagine
            ]);
        }


        return redirect()->route('admin.apartments.show', $new_apartment);
    }

    /**
     * Display the specified resource.
     */
    public function show(Apartment $apartment)
    {
        // condizione per far vedere all'utente solo i propri appartamenti

        if ($apartment->user_id !== Auth::id()) {
            return abort('404');
        }

        return view('admin.apartments.show', compact('apartment'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Apartment $apartment)
    {

        // Controllo opzionale per verificare se l'appartamento appartiene all'utente loggato


        if ($apartment->user_id !== Auth::id()) {
            return abort('404');
        }

        $services = Service::all();
        $sponsorships = Sponsorship::all();

        // Passa l'appartamento alla view insieme agli altri dati
        return view('admin.apartments.edit', compact('apartment', 'services', 'sponsorships'));
    }

    /**
     * Update the specified resource in storage.
     */

    public function update(ApartmentRequest $request, Apartment $apartment)
    {
        // Get the data from the request
        $data = $request->all();

        // Generate a new slug if the title has changed
        if ($data['title'] !== $apartment->title) {
            $data['slug'] = Helper::generateSlug($data['title'], Apartment::class);
        }

        // Handle the images
        if ($request->hasFile('img')) {
            // Check if there are existing images
            if ($apartment->img) {
                // Delete existing images from storage
                $images = explode(',', $apartment->img);
                foreach ($images as $image) {
                    $image = trim($image);
                    Storage::delete($image);
                }
            }

            // Create a new array for image paths
            $images_path = [];
            foreach ($request->file('img') as $img) {
                $img_path = Storage::put('uploads', $img);
                array_push($images_path, $img_path);
            }
            $data['img'] = implode(', ', $images_path);
        }

        // Get the updated latitude and longitude based on the address
        $address = $data['address'];
        $apiKey = env('TOMTOM_API_KEY');
        $data['latitude'] = Helper::getLatLon($address, $apiKey, 'lat');
        $data['longitude'] = Helper::getLatLon($address, $apiKey, 'lon');

        // Update the apartment details
        $apartment->update($data);

        // Handle services
        if (array_key_exists('services', $data)) {
            $apartment->services()->sync($data['services']);
        }

        return redirect()->route('admin.apartments.show', $apartment)->with('success', 'Appartamento aggiornato con successo!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Apartment $apartment)
    {
        // faccio un ciclo di tutte le immagini relazionate all'apartment
        foreach ($apartment->images as $img) {

            // Elimina l'immagine dal storage
            Storage::delete($img->img_path);
        }
        $apartment->delete();

        return redirect()->route('admin.apartments.index');
    }
}
