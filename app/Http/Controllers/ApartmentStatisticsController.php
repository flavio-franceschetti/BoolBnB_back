<?php

namespace App\Http\Controllers;

use App\Models\Apartment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApartmentStatisticsController extends Controller
{
    /**
     * Mostra le statistiche di visualizzazione per gli appartamenti dell'utente.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $user = Auth::user();

        // Ottieni gli appartamenti dell'utente
        $apartments = $user->apartments;

        // Statistiche di visualizzazione per ogni appartamento
        $statistics = $apartments->map(function ($apartment) {
            return [
                'apartment_id' => $apartment->id,
                'title' => $apartment->title,
                'totalViews' => $apartment->getTotalViews(), // Totale visualizzazioni
                'dailyViews' => $apartment->getDailyViews(), // Visualizzazioni giornaliere
            ];
        });

        return view('admin.apartments.statistics.index', compact('statistics'));
    }

    /**
     * Mostra le statistiche di visualizzazione per un appartamento specifico.
     *
     * @param int $apartmentId
     * @return \Illuminate\View\View
     */
    public function show($apartmentId)
    {
        $user = Auth::user();

        // Assicurati che l'utente abbia la relazione 'apartments' definita nel modello User
        $apartment = $user->apartments()->findOrFail($apartmentId);

        // Statistiche di visualizzazione
        $totalViews = $apartment->getTotalViews();
        $dailyViews = $apartment->getDailyViews();

        // Prepara i dati per il grafico delle visualizzazioni
        $viewsData = [];
        $labels = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $labels[] = $date->format('d/m'); // Etichetta della data
            $viewsData[] = $apartment->views()->whereDate('created_at', $date)->count(); // Conteggio delle visualizzazioni per la data
        }

        return view('admin.apartments.statistics', compact('apartment', 'totalViews', 'dailyViews', 'labels', 'viewsData'));
    }

    /**
     * Restituisce le statistiche di visualizzazione in formato JSON.
     *
     * @param int $apartmentId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getStatistics($apartmentId)
    {
        // Ottieni l'appartamento specificato
        $apartment = Apartment::findOrFail($apartmentId);

        // Calcola il totale delle visualizzazioni
        $totalViews = $apartment->views()->count();

        // Calcola le visualizzazioni odierne
        $dailyViews = $apartment->views()->whereDate('created_at', now()->format('Y-m-d'))->count();

        return response()->json([
            'total_views' => $totalViews,
            'daily_views' => $dailyViews,
        ]);
    }
}
