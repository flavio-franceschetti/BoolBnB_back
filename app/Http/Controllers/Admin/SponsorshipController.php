<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SponsorshipController extends Controller
{
    /**
     * Mostra la lista delle sponsorizzazioni.
     *
     * @return \Illuminate\View\View
     */
    public function index($apartmentId = null)
    {
        // Ottieni le sponsorizzazioni dalla configurazione
        $sponsorships = config('sponsorships.sponsorships');

        // Restituisci la vista con i dati delle sponsorizzazioni
        return view('admin.sponsorships.sponsorship', compact('sponsorships', 'apartmentId'));
    }

    /**
     * Mostra i dettagli di una sponsorizzazione specifica.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        // Ottieni le sponsorizzazioni dalla configurazione
        $sponsorships = config('sponsorships.sponsorships');
        $sponsorship = collect($sponsorships)->firstWhere('id', $id);

        if (!$sponsorship) {
            abort(404); // Se la sponsorizzazione non esiste, mostra errore 404
        }

        return view('admin.sponsorships.show', compact('sponsorship'));
    }
}
