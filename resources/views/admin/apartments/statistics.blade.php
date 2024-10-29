@extends('layouts.app')

@section('content')
    <h1>Statistiche per {{ $apartment->title }}</h1>

    <p>Totale Visualizzazioni: {{ $totalViews }}</p>
    <p>Visualizzazioni Oggi: {{ $dailyViews }}</p>

    <h2>Visualizzazioni negli Ultimi 7 Giorni</h2>
    <canvas id="dailyViewsChart" width="400" height="200"></canvas>

    <h2>Visualizzazioni negli Ultimi 12 Mesi</h2>
    <canvas id="monthlyViewsChart" width="400" height="200"></canvas>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Grafico delle visualizzazioni giornaliere
        const dailyCtx = document.getElementById('dailyViewsChart').getContext('2d');
        const dailyData = {
            labels: @json($labels),
            datasets: [{
                label: 'Visualizzazioni negli ultimi 7 giorni',
                data: @json($viewsData),
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1,
                fill: true
            }]
        };

        const dailyConfig = {
            type: 'line',
            data: dailyData,
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Numero di Visualizzazioni'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Giorni'
                        }
                    }
                }
            }
        };

        const dailyViewsChart = new Chart(dailyCtx, dailyConfig);

        // Grafico delle visualizzazioni mensili
        const monthlyCtx = document.getElementById('monthlyViewsChart').getContext('2d');
        const monthlyData = {
            labels: @json($monthlyLabels),
            datasets: [{
                label: 'Visualizzazioni negli ultimi 12 mesi',
                data: @json($monthlyViewsData),
                backgroundColor: 'rgba(153, 102, 255, 0.2)',
                borderColor: 'rgba(153, 102, 255, 1)',
                borderWidth: 1,
                fill: true
            }]
        };

        const monthlyConfig = {
            type: 'line',
            data: monthlyData,
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Numero di Visualizzazioni'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Mesi'
                        }
                    }
                }
            }
        };

        const monthlyViewsChart = new Chart(monthlyCtx, monthlyConfig);
    </script>
@endsection
