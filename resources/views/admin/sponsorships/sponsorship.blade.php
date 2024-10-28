@extends('layouts.app')

@section('content')
    <div class="container text-center mb-4 p-4">
        <h2 class="sponsorship-title">📢 Scegli la Tua Sponsorizzazione su BoolBnB!</h2>
        <h4 class="sponsorship-subtitle">Fai brillare i tuoi appartamenti con le nostre sponsorizzazioni esclusive!</h4>

        <!-- Contenitore descrittivo -->
        <div class="description-container animate__animated animate__fadeInDown mb-4">
            <h5>Come Acquistare la Tua Sponsorizzazione</h5>
            <p>
                Crea il tuo appartamento su BoolBnB e aggiungi la sponsorizzazione per renderlo visibile a migliaia di
                utenti! <br>
                Dopo aver creato il tuo appartamento, puoi facilmente selezionare una delle nostre sponsorizzazioni
                esclusive e
                far brillare il tuo annuncio. Non perdere tempo, inizia ora!
            </p>
        </div>

        <div class="row mt-4">
            @foreach ($sponsorships as $sponsorship)
                <div class="col-md-4 mb-3">
                    <div class="card sponsorship-card animate__animated animate__fadeIn">
                        <div class="card-body">
                            <h5 class="card-title">{{ $sponsorship['name'] }}</h5>
                            <p class="card-text">Prezzo: €{{ number_format($sponsorship['price'], 2) }}</p>
                            <p class="card-description">{{ $sponsorship['description'] }}</p>
                            <p><strong>Durata: {{ $sponsorship['duration'] }} ore</strong></p>
                            <h6 class="slogans-title">Slogan:</h6>
                            <div class="slogans">
                                @foreach ($sponsorship['slogans'] as $slogan)
                                    <p class="slogan-item">{{ $slogan }}</p>
                                @endforeach
                            </div>
                            <!-- Pulsante di pagamento -->
                            @if (isset($sponsorship['id']))
                                <a href="{{ route('admin.apartments.payment', ['apartmentId' => $apartmentId, 'sponsorshipId' => $sponsorship['id']]) }}"
                                    class="btn btn-primary mt-3">Acquista Sponsorizzazione</a>
                            @else
                                <p>Questa sponsorizzazione è disponibile per l'acquisto nel modifica dell'appartamento
                                    selezionato!</p>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="row mt-4">
            <!-- Prima card -->
            <div class="col-md-4 mb-3">
                <div class="card sponsorship-card animate__animated animate__fadeIn">
                    <div class="card-body text-center">
                        <h5 class="card-title">Metti il Tuo Appartamento in Evidenza!</h5>
                        <p class="card-description">Ogni giorno, migliaia di utenti cercano appartamenti. Non perdere
                            l'occasione di far brillare il tuo!</p>
                    </div>
                </div>
            </div>

            <!-- Seconda card -->
            <div class="col-md-4 mb-3">
                <div class="card sponsorship-card animate__animated animate__fadeIn">
                    <div class="card-body text-center">
                        <h5 class="card-title">Mantieni il Tuo Appartamento in Cima!</h5>
                        <p class="card-description">Essere visibili è fondamentale. Sponsorizza e rimani sempre nella parte
                            alta delle ricerche!</p>
                    </div>
                </div>
            </div>

            <!-- Terza card -->
            <div class="col-md-4 mb-3">
                <div class="card sponsorship-card animate__animated animate__fadeIn">
                    <div class="card-body text-center">
                        <h5 class="card-title">Non Aspettare, Agisci Ora!</h5>
                        <p class="card-description">Le opportunità non bussano due volte! Sponsorizza il tuo appartamento e
                            attirali subito!</p>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <style>
        /* Sfondo della pagina */
        body {
            background: linear-gradient(to bottom, #000000, #2E7D32);
            color: #ffffff;

        }

        /* Stile per il titolo delle sponsorizzazioni */
        .sponsorship-title {
            font-weight: bold;
            font-size: 2rem;
            color: #f1f3f1;
            margin-bottom: 0.5rem;

        }

        /* Stile per il sottotitolo delle sponsorizzazioni */
        .sponsorship-subtitle {
            font-size: 1.2rem;
            color: #A5D6A7;
            margin-bottom: 1.5rem;
        }

        /* Stile per le card delle sponsorizzazioni */
        .sponsorship-card {
            border: 1px solid #4CAF50;
            border-radius: 8px;
            transition: transform 0.2s;
            background-color: #343a40;
            color: #ffffff;

        }

        /* Effetto hover per le card delle sponsorizzazioni */
        .sponsorship-card:hover {
            transform: scale(1.05);

        }

        /* Stile per il titolo degli slogan */
        .slogans-title {
            margin-top: 1rem;
            font-weight: bold;
            color: #4CAF50;

        }

        /* Stile per gli slogan */
        .slogan-item {
            font-style: italic;

            color: #C8E6C9;

        }

        /* Pulsante di acquisto */
        .btn-primary {
            background-color: #4CAF50;
            border-color: #4CAF50;

        }

        /* Hover per il pulsante */
        .btn-primary:hover {
            background-color: #388E3C;
            border-color: #388E3C;

        }
    </style>
@endsection
