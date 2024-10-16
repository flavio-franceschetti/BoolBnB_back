<?php

namespace App\Http\Controllers\Admin;

use App\Functions\Helper;
use App\Models\Apartment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
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

        $apartments = Apartment::all();

        return view('admin.apartments.index', compact('apartments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.apartments.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // inserisco tutti i dati della richiesta dentro la variabile $data
        $data = $request->all();
        // creo un nuovo appartamento
        $new_apartment = new Apartment();
        // genero lo slug prendendo il titolo dell appartamento con l'helper inserito in funcion
        $data['slug'] = Helper::generateSlug($data['title'], Apartment::class);
        // assegno all'user id l'id dell'utente che sta creando il nuovo annuncio
        $data['user_id'] = Auth::id();

        // controllo le immagini se esiste la key img in $data
        if(array_key_exists('img', $data)){
            // con storahe::put salvo il percorso dell immagine nella variabile $img_path
            $img_path = Storage::put('uploads', $data['img']);
            // salvo il path generato nella key img dell'array $data
            $data['img'] = $img_path;
        }


        // Prendo la latitudine e longitudine dall'indirizzo inserito dall'utente
        $address = $data['address'];
        $apiKey = env('TOMTOM_API_KEY');
        // utilizzo le funzioni create nell'helper per prendermi la latitudine e la longitudine dall'api di tomtom
        $data['latitude'] = Helper::getLatitude($address, $apiKey);
        $data['longitude'] = Helper::getLatitude($address, $apiKey);
        



        // fillo $new_apartment con $data grazie al fillable che è nel model
        $new_apartment->fill($data);
        // salvo il nuovo appartamento
        $new_apartment->save();

        return redirect()->route('admin.apartments.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Apartment $apartment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Apartment $apartment)
    {
        //
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
        //
    }
}
