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
        $dailyViewsData = [];
        $dailyLabels = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $dailyLabels[] = $date->format('d/m');
            $dailyViewsData[] = $apartment->views()->whereDate('created_at', $date)->count();
        }

        // Prepara i dati per il grafico delle visualizzazioni mensili
        $monthlyViewsData = [];
        $monthlyLabels = [];
        $currentMonth = now()->month;
        $currentYear = now()->year;

        for ($i = 0; $i < 12; $i++) {
            $month = ($currentMonth - $i + 12) % 12;
            $year = $currentYear - floor(($currentMonth - $i) / 12);

            $viewsCount = $apartment->views()->whereYear('created_at', $year)
                ->whereMonth('created_at', $month + 1)
                ->count();

            $monthlyLabels[] = Carbon::create()->month($month + 1)->format('F');
            $monthlyViewsData[] = $viewsCount;
        }

        // Rovescia i dati per mostrare dall'ultimo mese al primo
        $monthlyViewsData = array_reverse($monthlyViewsData);
        $monthlyLabels = array_reverse($monthlyLabels);

        // Prepara i dati per il grafico annuale
        $pastYearsData = [];
        $pastYearsLabels = [];
        $numberOfYears = 5;

        for ($i = 0; $i < $numberOfYears; $i++) {
            $year = $currentYear - $i;
            $viewsCount = $apartment->views()->whereYear('created_at', $year)->count();

            $pastYearsLabels[] = $year;
            $pastYearsData[] = $viewsCount;
        }

        // Rovescia i dati per mostrare dall'anno più recente al più vecchio
        $pastYearsData = array_reverse($pastYearsData);
        $pastYearsLabels = array_reverse($pastYearsLabels);

        // Passa tutte le variabili necessarie alla vista
        return view('admin.apartments.statistics', compact(
            'apartment',
            'totalViews',
            'dailyViews',
            'dailyLabels',
            'dailyViewsData',
            'monthlyLabels',
            'monthlyViewsData',
            'pastYearsLabels',
            'pastYearsData'
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
