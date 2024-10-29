@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <h2>Dettagli appartamento</h2>
                    </div>

                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    {{-- IMMAGINE APPARTAMENTO --}}
                    @if ($apartment->images)
                        <div class="card">
                            <div class="card-header bg-secondary text-white">
                                <h4>Immagini dell'appartamento</h4>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    @foreach ($apartment->images as $image)
                                        <div class="col-md-4 mb-3">
                                            <img src="{{ asset('storage/' . $image->img_path) }}" class="img-fluid rounded"
                                                alt="Immagine appartamento">
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="card">
                            <div class="card-body text-center">
                                <img src="/img/no-image.jpg" class="img-fluid" alt="Immagine non disponibile">
                            </div>
                        </div>
                    @endif

                    {{-- INIZIO CARD PER I DETTAGLI --}}
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <p><strong>
                                        <i class="fas fa-door-open"></i>
                                        Stanze:
                                    </strong> {{ $apartment->rooms }}</p>
                                <p><strong>
                                        <li class="fas fa-bed"></li>
                                        Letti:
                                    </strong> {{ $apartment->beds }}</p>
                                <p><strong>
                                        <li class="fas fa-toilet"></li>
                                        Bagni:
                                    </strong>{{ $apartment->bathrooms }}</p>
                                <p><strong>
                                        Metri quadrati: </strong>{{ $apartment->mq }} <strong>m²</strong></p>
                            </div>
                            <div class="col-md-6">
                                <i class="fa-solid fa-street-view"></i><strong>
                                    Indirizzo: </strong> {{ $apartment->address }} {{ $apartment->civic_number }}</p>
                                <p><strong>Visibile:</strong>
                                    @if ($apartment->is_visible)
                                        <i class="fa-solid fa-eye"></i> Si
                                    @else
                                        <i class="fa-solid fa-eye-slash"></i> No
                                    @endif
                                </p>

                                {{-- Sezione Servizi --}}
                                <div class="mb-3">
                                    <h4>Servizi dell'appartamento</h4>
                                    @if ($apartment->services)
                                        @foreach ($apartment->services as $service)
                                            <span class="badge bg-warning">{{ $service->name }}</span>
                                        @endforeach
                                    @else
                                        <p>Nessun servizio disponibile</p>
                                    @endif
                                </div>

                                <div class="text-muted">
                                    <p><strong>Data Pubblicazione:</strong> {{ $apartment->created_at->format('d F Y') }}
                                    </p>
                                </div>
                                @if ($primarySponsorshipName)
                                    <div class="card mb-4">
                                        <div class="card-header bg-primary text-white">
                                            <h5 class="mb-0">Sponsorizzazione Attiva</h5>
                                        </div>
                                        <div class="card-body">
                                            <h6 class="card-title">Opzione Pacchetto: {{ $primarySponsorshipName }}</h6>
                                            <p class="card-text">
                                                <strong>Tempo Rimanente:</strong>
                                                {{ $remainingHours }} ore e {{ $remainingMinutes }} minuti
                                            </p>
                                            <hr>
                                            <p class="text-muted">
                                                Questa sponsorizzazione ti offre una maggiore visibilità e opportunità per
                                                il tuo appartamento!
                                            </p>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        {{-- prova mappa --}}
                        <div class="map-container">
                            <div style="width: 700px; height: 500px" id="map"></div>
                        </div>

                        {{-- STATISTICHE DELLE VISUALIZZAZIONI --}}
                        <h1>Statistiche per {{ $apartment->title }}</h1>
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
                                <canvas id="viewsChart" width="400" height="200"></canvas>
                            </div>
                        </div>

                        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
                        {{-- BTN PER TORNARE ALL' ELENCO APPARTAMENTI --}}
                        <div class="mt-4">
                            <a href="{{ route('admin.apartments.index') }}" class="btn btn-primary">Torna all'elenco</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- script per far funzionare la mappa di tom tom --}}
    <script>
        // creo una constante dove inserisco la apiKey che prendo dal file config
        const apiKey = "{{ config('app.tomtomapikey') }}";
        const latitude = "{{ $apartment->latitude }}";
        const longitude = "{{ $apartment->longitude }}";
        let center = [longitude, latitude];

        // Inizializza la mappa con tt.map che sono comandi della libreria tomtom
        var map = tt.map({
            key: apiKey, // Sostituisci con la tua chiave API
            container: 'map', // l'id del contenitore html in cui deve essere inserita la mappa
            center: center, // Coordinate iniziali del centro della visualizzazione della mappa
            zoom: 15, // livello di zoom iniziale della mappa, più è alto più è zoommato
        });

        map.on('load', () => {
            // Aggiungi un marker sulla mappa con tt.marker
            new tt.Marker().setLngLat(center).addTo(map);
        });

        const ctx = document.getElementById('viewsChart').getContext('2d');
        const titleElement = document.getElementById('chartTitle');

        // Dati di visualizzazione di default (mensile)
        const data = {
            labels: @json($labels),
            datasets: [{
                label: 'Visualizzazioni negli ultimi 12 mesi',
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
                        suggestedMax: 10, // Espande il range a 10 per maggiore chiarezza
                        title: {
                            display: true,
                            text: 'Numero di Visualizzazioni'
                        },
                        ticks: {
                            stepSize: 1, // Utilizza incrementi di 1
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
                    newLabels = @json($labels);
                    newData = @json($viewsData);
                    titleElement.textContent = 'Visualizzazioni negli ultimi 12 mesi';
                    break;
                case 'yearly':
                    newLabels = @json($pastYearsLabels); // Etichette con gli anni
                    newData = @json($pastYearsData); // Dati per gli anni passati
                    titleElement.textContent = 'Visualizzazioni per anno';
                    break;
            }

            // Aggiorna i dati del grafico
            viewsChart.data.labels = newLabels;
            viewsChart.data.datasets[0].data = newData;
            viewsChart.update();
        });
    </script>

    <style>
        .card {
            border: 1px solid #007bff;
            /* Colore del bordo */
            border-radius: 0.5rem;
            /* Raggio degli angoli */
        }

        .card-header {
            font-size: 1.25rem;
            /* Dimensione del font */
        }

        .card-title {
            font-weight: bold;
            /* Grassetto per il titolo */
        }

        .card-text {
            font-size: 1.1rem;
            /* Dimensione del testo */
        }
    </style>

@endsection
