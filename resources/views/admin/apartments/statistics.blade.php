@extends('layouts.app')

@section('content')
    <h1>Statistiche per {{ $apartment->title }}</h1>

    <p>Totale Visualizzazioni: {{ $totalViews }}</p>
    <p>Visualizzazioni Oggi: {{ $dailyViews }}</p>

    <canvas id="viewsChart" width="400" height="200"></canvas>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('viewsChart').getContext('2d');
        const data = {
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

        const config = {
            type: 'line',
            data: data,
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

        const viewsChart = new Chart(ctx, config);
    </script>
@endsection
