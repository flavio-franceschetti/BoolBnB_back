@extends('layouts.app')

@section('content')
    {{-- STATISTICHE DELLE VISUALIZZAZIONI --}}
    <h1>Statistiche per l'appartamento: {{ $apartment->title }}</h1>
    <p>Totale Visualizzazioni: {{ $totalViews }}</p>
    <p>Visualizzazioni Oggi: {{ $dailyViews }}</p>

    {{-- Card per il grafico --}}
    <div class="card mb-4">
        <div class="card-header bg-secondary text-white d-flex justify-content-between">
            <h4 id="chartTitle">Visualizzazioni negli ultimi 12 mesi</h4>
            <select id="timeframe" class="form-select" style="width: auto;">
                <option value="daily">Giornaliero</option>
                <option value="monthly" selected>Mensile</option>
                <option value="yearly">Annuale</option>
            </select>
        </div>
        <div class="card-body">
            <canvas id="viewsChart" width="300" height="100"></canvas>
        </div>
    </div>


    <script>
        // Dati di visualizzazione di default (mensile)
        const ctx = document.getElementById('viewsChart').getContext('2d');
        const titleElement = document.getElementById('chartTitle');

        // Dati di visualizzazione di default (mensile)
        const data = {
            labels: @json($monthlyLabels),
            datasets: [{
                label: 'Visualizzazioni negli ultimi 12 mesi',
                data: @json($monthlyViewsData),
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
                        suggestedMax: 10,
                        title: {
                            display: true,
                            text: 'Numero di Visualizzazioni'
                        },
                        ticks: {
                            stepSize: 1,
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Data'
                        }
                    }
                }
            }
        };

        const viewsChart = new Chart(ctx, config);

        // Funzione per aggiornare il grafico e il titolo in base alla selezione del periodo
        document.getElementById('timeframe').addEventListener('change', function() {
            const timeframe = this.value;
            let newLabels, newData;

            switch (timeframe) {
                case 'daily':
                    newLabels = @json($dailyLabels);
                    newData = @json($dailyViewsData);
                    titleElement.textContent = 'Visualizzazioni negli ultimi 7 giorni';
                    break;
                case 'monthly':
                    newLabels = @json($monthlyLabels);
                    newData = @json($monthlyViewsData);
                    titleElement.textContent = 'Visualizzazioni negli ultimi 12 mesi';
                    break;
                case 'yearly':
                    newLabels = @json($pastYearsLabels);
                    newData = @json($pastYearsData);
                    titleElement.textContent = 'Visualizzazioni per anno';
                    break;
            }

            // Aggiorna i dati del grafico
            viewsChart.data.labels = newLabels;
            viewsChart.data.datasets[0].data = newData;
            viewsChart.update();
        });
    </script>
@endsection
