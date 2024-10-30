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
use App\Models\View;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
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
        $apiKey = config('app.tomtomapikey');
        // utilizzo le funzioni create nell'helper per prendermi la latitudine e la longitudine dall'api di tomtom
        $apartmentData['latitude'] = Helper::getLatLon($address, $apiKey, 'lat');
        $apartmentData['longitude'] = Helper::getLatLon($address, $apiKey, 'lon');

        // creo un nuovo appartamento
        $new_apartment = Apartment::create($apartmentData);

        //gestisco i servizi
        if (isset($data['services'])) {
            $new_apartment->services()->attach($data['services']);
        }

        // gestisco la tabella apartment_images
        foreach ($images as $img) {
            // salvo in path nello store
            $img_path = Storage::put('uploads', $img);
            // salvo il nome del file
            $img_name = $img->getClientOriginalName();
            // creo le nuove immagini in relazione all'appartamento
            ApartmentImage::create([
                'apartment_id' => $new_apartment->id,
                'img_path' => $img_path,
                'img_name' => $img_name,
            ]);
        }


        return redirect()->route('admin.apartments.show', $new_apartment);
    }



    /**
     * Display the specified resource.
     */
    public function show(Apartment $apartment)
    {

        // Verifica che l'appartamento appartenga all'utente loggato
        if ($apartment->user_id !== Auth::id()) {
            return abort(404);
        }

        // Statistiche di visualizzazione
        $totalViews = $apartment->getTotalViews();
        $dailyViews = $apartment->getDailyViews();

        // Prepara i dati per il grafico delle visualizzazioni mensili
        $viewsData = [];
        $labels = [];

        // Ottieni il mese corrente
        $currentMonth = now()->month;
        $currentYear = now()->year;

        // Riempi l'array per i 12 mesi dell'anno
        for ($i = 0; $i < 12; $i++) {
            // Calcola il mese corrente
            $month = ($currentMonth - $i + 12) % 12; // Mese corretto (0-11)
            $year = $currentYear - floor(($currentMonth - $i) / 12); // Anno corretto

            // Ottieni il conteggio delle visualizzazioni per il mese corrente
            $viewsCount = $apartment->views()->whereYear('created_at', $year)
                ->whereMonth('created_at', $month + 1) // Aggiungi 1 perché month in Carbon è 1-based
                ->count();

            // Prepara le etichette per i mesi
            $labels[] = Carbon::create()->month($month + 1)->format('F'); // Nome del mese
            $viewsData[] = $viewsCount;
        }

        // Rovescia i dati per mostrare dall'ultimo mese al primo
        $viewsData = array_reverse($viewsData);
        $labels = array_reverse($labels);

        // Statistiche giornaliere per gli ultimi 7 giorni
        $dailyViewsData = [];
        $dailyLabels = [];
        for ($i = 0; $i < 7; $i++) {
            $date = now()->subDays($i);
            $dailyLabels[] = $date->format('d-m'); // Formato giorno-mese
            $dailyViewsData[] = $apartment->views()->whereDate('created_at', $date)->count();
        }
        $dailyLabels = array_reverse($dailyLabels);
        $dailyViewsData = array_reverse($dailyViewsData);

        // Statistiche annuali per l'anno corrente
        $yearlyViewsData = [];
        $yearlyLabels = [];
        for ($i = 1; $i <= 12; $i++) {
            $yearlyLabels[] = Carbon::create()->month($i)->format('F'); // Nome del mese
            $yearlyViewsData[] = $apartment->views()->whereYear('created_at', $currentYear)
                ->whereMonth('created_at', $i)
                ->count();
        }

        // Statistiche annuali per gli ultimi 5 anni
        $pastYearsData = [];
        $pastYearsLabels = [];
        $yearsBack = 5; // Numero di anni passati da considerare
        $currentYear = now()->year;

        for ($i = 0; $i < $yearsBack; $i++) {
            $year = $currentYear - $i;
            $pastYearsLabels[] = (string)$year; // Aggiungi l'anno come etichetta
            $pastYearsData[] = $apartment->views()->whereYear('created_at', $year)->count();
        }

        // Recupera tutte le sponsorizzazioni attive
        $activeSponsorships = $apartment->sponsorships()
            ->where('end_date', '>', now())
            ->get();

        // Inizializza le variabili per il nome, le ore e i minuti rimanenti
        $primarySponsorshipName = null;
        $remainingHours = 0;
        $remainingMinutes = 0;

        // Se ci sono sponsorizzazioni attive, calcola le ore e i minuti rimanenti
        foreach ($activeSponsorships as $activeSponsorship) {
            // Ottiene il nome della sponsorizzazione principale (se non già impostato)
            if (!$primarySponsorshipName) {
                $primarySponsorshipName = $activeSponsorship->name;
            }

            // Calcola il tempo rimanente per questa sponsorizzazione
            $now = now();
            $endDate = Carbon::parse($activeSponsorship->pivot->end_date);

            // Calcola la differenza in secondi
            $remainingSeconds = $now->diffInSeconds($endDate, false);

            // Se il tempo è passato, ignora
            if ($remainingSeconds > 0) {
                // Somma le ore e i minuti
                $remainingHours += floor($remainingSeconds / 3600);
                $remainingMinutes += floor(($remainingSeconds % 3600) / 60);
            }
        }

        // Corregge eventuali overflow di minuti in ore
        $remainingHours += floor($remainingMinutes / 60);
        $remainingMinutes = $remainingMinutes % 60;

        // Log dei risultati
        Log::info('Ore rimanenti:', ['remainingHours' => $remainingHours, 'remainingMinutes' => $remainingMinutes]);

        return view('admin.apartments.show', compact(
            'apartment',
            'primarySponsorshipName',
            'remainingHours',
            'remainingMinutes',
            'totalViews',
            'dailyViews',
            'labels',
            'viewsData',
            'dailyLabels',
            'dailyViewsData',
            'yearlyLabels',
            'yearlyViewsData',
            'pastYearsLabels',
            'pastYearsData'


        ));
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Apartment $apartment)
    {
        // Controllo opzionale per verificare se l'appartamento appartiene all'utente loggato
        if ($apartment->user_id !== Auth::id()) {
            return abort(404);
        }

        $services = Service::all();
        $sponsorships = Sponsorship::all();
        $sponsorship = $sponsorships->first();

        // Passa l'appartamento e le sponsorizzazioni alla vista
        return view('admin.apartments.edit', compact('apartment', 'services', 'sponsorships', 'sponsorship'));
    }

    /**
     * Update the specified resource in storage.
     */

    public function update(ApartmentRequest $request, Apartment $apartment)
    {
        // Ottieni i dati dalla richiesta validata
        $data = $request->validated();

        // dd($request->all());

        // Creo un variabile per i dati dell'appartamento
        $apartmentData = [
            'title' => $data['title'],
            'rooms' => $data['rooms'],
            'beds' => $data['beds'],
            'bathrooms' => $data['bathrooms'],
            'mq' => $data['mq'],
            'address' => $data['address'],
            'is_visible' => $data['is_visible'],
        ];

        // Controllo se ci sono immagini da eliminare
        $deletedImages = false;
        if ($request->has('delete_images')) {
            foreach ($request->delete_images as $imgId) {
                $db_img = ApartmentImage::find($imgId);
                if ($db_img) {
                    // Elimina il file dell'immagine dallo storage
                    Storage::delete($db_img->img_path);

                    $db_img->delete();
                    $deletedImages = true;
                }
            }
        }

        // Verifica se ci sono ancora immagini associate all'appartamento dopo l'eliminazione
        $remainingImages = $apartment->images()->count();

        // Se non ci sono immagini rimanenti e non vengono caricate nuove immagini, ritorna con errore
        if ($remainingImages == 0 && !$request->hasFile('images')) {
            return redirect()->back()->withErrors(['images' => 'Devi caricare almeno un\'immagine se elimini tutte le immagini esistenti.']);
        }

        // Genera un nuovo slug solo se il titolo è stato modificato
        if ($apartmentData['title'] !== $apartment->title) {
            $apartmentData['slug'] = Helper::generateSlug($apartmentData['title'], Apartment::class);
        }

        // Aggiorna latitudine e longitudine solo se l'indirizzo è stato modificato
        if ($apartmentData['address'] !== $apartment->address) {
            $address = $apartmentData['address'];
            $apiKey = config('app.tomtomapikey');
            $apartmentData['latitude'] = Helper::getLatLon($address, $apiKey, 'lat');
            $apartmentData['longitude'] = Helper::getLatLon($address, $apiKey, 'lon');
        }

        // Aggiorna i dettagli dell'appartamento
        $apartment->update($apartmentData);



        if ($request->has('sponsorship_id')) {
            $sponsorshipId = $request->input('sponsorship_id');

            if ($request->input('is_paid') === 'true') {
                $sponsorship = Sponsorship::find($sponsorshipId);
                if ($sponsorship) {
                    // Recupera le ore di sponsorizzazione
                    $sponsorshipHours = $sponsorship->duration;

                    // Controlla se c'è una sponsorizzazione attiva esistente
                    $currentSponsorship = $apartment->sponsorships()
                        ->where('sponsorship_id', $sponsorshipId)
                        ->where('end_date', '>', now())
                        ->first();

                    if ($currentSponsorship) {
                        // Se esiste una sponsorizzazione attiva, estendi la durata
                        $newEndDate = Carbon::parse($currentSponsorship->pivot->end_date)->addSeconds($sponsorshipHours);
                        $totalSponsorshipHours = $currentSponsorship->pivot->sponsorship_hours + $sponsorshipHours;

                        // Aggiorna la relazione pivot
                        $currentSponsorship->pivot->sponsorship_hours = $totalSponsorshipHours;
                        $currentSponsorship->pivot->end_date = $newEndDate;
                        $currentSponsorship->pivot->save();
                    } else {
                        // Se non esiste una sponsorizzazione attiva, creane una nuova
                        $apartment->sponsorships()->attach($sponsorship->id, [
                            'end_date' => now()->addSeconds($sponsorshipHours),
                            'sponsorship_hours' => $sponsorshipHours,
                        ]);
                    }

                    // Aggiorna il campo sponsorship_hours dell'appartamento
                    $apartment->sponsorship_hours = $apartment->sponsorships()->sum('sponsorship_hours'); // Somma le ore
                    $apartment->save(); // Salva le modifiche

                }
            }
        }

        // Se l'utente ha caricato nuove immagini, gestisco il caricamento
        if ($request->hasFile('images')) {
            $images = $request->file('images');
            foreach ($images as $img) {
                // Salva l'immagine nella directory 'uploads'
                $img_path = Storage::put('uploads', $img);
                // Ottieni il nome originale del file
                $img_name = $img->getClientOriginalName();
                // Crea nuovi record immagine collegati all'appartamento
                ApartmentImage::create([
                    'apartment_id' => $apartment->id,
                    'img_path' => $img_path,
                    'img_name' => $img_name,
                ]);
            }
        }

        // Gestisco i servizi
        if (isset($data['services'])) {
            $apartment->services()->sync($data['services']);
        }

        // Ritorna alla vista dell'appartamento con un messaggio di successo
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
