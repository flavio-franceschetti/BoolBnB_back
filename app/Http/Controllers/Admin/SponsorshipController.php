<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Apartment;
use App\Models\Sponsorship;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class SponsorshipController extends Controller
{
    /**
     * Mostra la lista delle sponsorizzazioni.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Recupera l'ID dell'utente autenticato
        $userId = Auth::id();

        // Ottieni gli appartamenti dell'utente
        $apartments = Apartment::where('user_id', $userId)->get();

        // Log per il debug: controlla se gli appartamenti sono stati recuperati
        Log::info('Appartamenti trovati per l\'utente ID ' . $userId, $apartments->toArray());

        // Ottieni le sponsorizzazioni dalla configurazione
        $sponsorships = config('sponsorships.sponsorships');

        // Restituisci la vista con i dati delle sponsorizzazioni e degli appartamenti
        return view('admin.sponsorships.sponsorship', compact('sponsorships', 'apartments'));
    }

    /**
     * Mostra i dettagli di una sponsorizzazione specifica.
     *
     * @param  string  $name
     * @return \Illuminate\View\View
     */
    public function show($name)
    {
        // Ottieni le sponsorizzazioni dalla configurazione
        $sponsorships = config('sponsorships.sponsorships');
        $sponsorship = collect($sponsorships)->firstWhere('name', $name); // Cerca per nome

        if (!$sponsorship) {
            abort(404); // Se la sponsorizzazione non esiste, mostra errore 404
        }

        return view('admin.sponsorships.show', compact('sponsorship'));
    }

    public function processPayment(Request $request, $apartmentId, $sponsorshipName)
    {
        $userId = Auth::id();
        $apartment = Apartment::where('id', $apartmentId)->where('user_id', $userId)->first();

        if (!$apartment) {
            return redirect()->back()->with('error', 'Appartamento non trovato o non autorizzato.');
        }

        // Ottieni le sponsorizzazioni dalla configurazione
        $sponsorships = config('sponsorships.sponsorships');
        $sponsorship = collect($sponsorships)->firstWhere('name', $sponsorshipName); // Cerca per nome

        if (!$sponsorship) {
            return redirect()->back()->with('error', 'Sponsorizzazione non trovata.');
        }



        // Se il pagamento ha successo, aggiungi la sponsorizzazione all'appartamento
        $apartment->sponsorships()->attach($sponsorship['name'], [
            'sponsorship_hours' => $sponsorship['duration'],
            'end_date' => now()->addHours($sponsorship['duration']) // o la logica per calcolare l'end_date
        ]);

        return redirect()->route('admin.apartments.index')->with('success', 'Sponsorizzazione acquistata con successo!');
    }
}
