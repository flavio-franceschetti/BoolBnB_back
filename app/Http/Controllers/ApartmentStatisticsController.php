<?php

namespace App\Http\Controllers;

use App\Models\Apartment;
use App\Models\User;
use App\Models\View;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

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

        // Ottieni gli appartamenti dell'utente\
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

        // Prepara i dati per il grafico delle visualizzazioni giornaliere
        $viewsData = [];
        $labels = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $labels[] = $date->format('d/m'); // Etichetta della data
            $viewsData[] = $apartment->views()->whereDate('created_at', $date)->count(); // Conteggio delle visualizzazioni per la data
        }

        // Prepara i dati per il grafico delle visualizzazioni mensili
        $monthlyViewsData = [];
        $monthlyLabels = [];
        $currentMonth = now()->month;
        $currentYear = now()->year;

        for ($i = 0; $i < 12; $i++) {
            $month = ($currentMonth - $i + 12) % 12; // Mese corretto (0-11)
            $year = $currentYear - floor(($currentMonth - $i) / 12); // Anno corretto

            $viewsCount = $apartment->views()->whereYear('created_at', $year)
                ->whereMonth('created_at', $month + 1) // Aggiungi 1 perché month in Carbon è 1-based
                ->count();

            $monthlyLabels[] = Carbon::create()->month($month + 1)->format('F'); // Nome del mese
            $monthlyViewsData[] = $viewsCount;
        }

        // Rovescia i dati per mostrare dall'ultimo mese al primo
        $monthlyViewsData = array_reverse($monthlyViewsData);
        $monthlyLabels = array_reverse($monthlyLabels);

        return view('admin.apartments.statistics', compact(
            'apartment',
            'totalViews',
            'dailyViews',
            'labels',
            'viewsData',
            'monthlyLabels',
            'monthlyViewsData'
        ));
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

    protected function recordView(Request $request)
    {

        $ViewsData = $request->all();

        // Log::info("Registrazione visualizzazione per l'appartamento ID {$apartment->id} dall'IP {$ipAddress}");

        if (!View::where('apartment_id', $ViewsData['apartment_id'])->where('ip_address', $ViewsData['ip_address'])->exists()) {
            View::create($ViewsData);
        } else {
            log::info("appartamento già esistente");
        }
    }
}
