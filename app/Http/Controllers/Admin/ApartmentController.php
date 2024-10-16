<?php

namespace App\Http\Controllers\Admin;

use App\Functions\Helper;
use App\Models\Apartment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\ApartmentRequest;
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
        // $apartments = Apartment::orderBy('id', 'desc')->where('user_id', Auth::id())->get();

        $apartments = Apartment::orderBy('id', 'desc')->get();

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
        $data = $request->all();

        // genero lo slug prendendo il titolo dell appartamento con l'helper inserito in funcion
        $data['slug'] = Helper::generateSlug($data['title'], Apartment::class);
        // assegno all'user id l'id dell'utente che sta creando il nuovo annuncio
        $data['user_id'] = Auth::id();

        // controllo le immagini se esiste la key img in $data
        if (array_key_exists('img', $data)) {
            // creiamo un nuovo array per i percorsi delle immagini
            $images_path = [];
            // cicliamo l'array che arriva dal form con tutte le immagini
            foreach ($data['img'] as $img) {
                // con storage::put salvo il percorso dell immagine nella variabile $img_path
                $img_path = Storage::put('uploads', $img);
                // pushiamo i nuovi percorsi dentro $images_path
                array_push($images_path, $img_path);
            }
            // sostituiamo l'array originale con quello dei path aggiornati riconvertito in stringa con implode
            $data['img'] = implode(', ', $images_path);
        }
        // Prendo la latitudine e longitudine dall'indirizzo inserito dall'utente
        $address = "{$data['address']} {$data['civic_number']} {$data['city']}";
        $apiKey = env('TOMTOM_API_KEY');
        // utilizzo le funzioni create nell'helper per prendermi la latitudine e la longitudine dall'api di tomtom
        $data['latitude'] = Helper::getLatLon($address, $apiKey, 'lat');

        $data['longitude'] = Helper::getLatLon($address, $apiKey, 'lon');

        // creo un nuovo appartamento
        $new_apartment = Apartment::create($data);

        //gestisco i servizi
        if (array_key_exists('services', $data)) {
            $new_apartment->services()->attach($data['services']);
        }


        return redirect()->route('admin.apartments.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Apartment $apartment)
    {
        // condizione per far vedere all'utente solo i propri appartamenti

        // if($apartment->user_id !== Auth::id()){
        //     return abort('404');
        // }

        return view('admin.apartments.show', compact('apartment'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Apartment $apartment)
    {
        // if($apartment->user_id !== Auth::id()){
        //     return abort('404');
        // }

        $services = Service::all();
        $sponsorships = Sponsorship::all();
        return view('admin.apartments.edit', compact('sponsorships', 'services'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Apartment $apartment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Apartment $apartment)
    {
        // Verifica se ci sono immagini collegate
        if ($apartment->img) {
            // Estrai il nome dell'immagine e rimuovi eventuali spazi
            $images = explode(',', $apartment->img);
            foreach ($images as $image) {
                // Rimuovi eventuali spazi extra
                $image = trim($image);
                // Elimina l'immagine dal storage
                Storage::delete($image);
            }
        }
        $apartment->delete();

        return redirect()->route('admin.apartments.index');
    }
}
