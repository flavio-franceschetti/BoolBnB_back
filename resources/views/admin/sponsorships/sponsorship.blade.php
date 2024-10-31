@extends('layouts.app')

@section('content')
    <div class="container my-5">
        <header class="text-center mb-5">
            <h1 class="sponsorship-title">Promuovi il Tuo Appartamento su BoolBnB <i class="fas fa-key"></i></h1>
            <p class="sponsorship-subtitle">Scegli una sponsorizzazione per aumentare la tua visibilità e attirare più ospiti
            </p>
        </header>

        <section class="how-it-works mb-5 p-4 text-center">
            <h3 class="section-title">Come Funziona</h3>
            <p class="section-description animate__animated animate__fadeInUp">
                <strong>Metti in luce il tuo appartamento!</strong><br>
                Vai alla selezione del tuo appartamento qui in basso, <br>e scegli la sponsorizzazione perfetta con un
                click!
                <br>
                <em>Semplice, veloce, e ti fa risaltare!</em>
            </p>
        </section>

        <!-- Selezione appartamento -->
        <div class="mb-4 text-center">
            <h3 class="section-title mb-3">Seleziona il Tuo Appartamento per Sponsorizzare</h3>
            <select id="apartmentSelector" class="form-control w-50 mx-auto">
                <option value="" selected disabled>clicca qui per selezionare il tuo appartamento!</option>
                <option value="none">Nessuno</option> <!-- Opzione per nessuna selezione -->
                @foreach ($apartments as $apartment)
                    <option value="{{ $apartment->id }}">{{ $apartment->title }}</option>
                @endforeach
            </select>
        </div>

        <!-- Sezione Sponsorizzazioni -->
        <section class="sponsorship-options my-5 text-center">
            <h3 class="section-title">Sponsorizzazioni Disponibili</h3>
            <div class="option-list d-flex justify-content-center flex-wrap">
                @foreach ($sponsorships as $sponsorship)
                    <div class="sponsorship-option m-2" data-sponsorship-id="{{ $sponsorship['id'] }}"
                        data-sponsorship-name="{{ $sponsorship['name'] }}">
                        <h4 class="option-name">{{ $sponsorship['name'] }}</h4>
                        <p class="option-price">{{ number_format($sponsorship['price'], 2) }}€</p>
                        <p class="option-description">{{ $sponsorship['description'] }}</p>
                        <span class="option-duration">Durata: {{ $sponsorship['duration'] }} ore</span>

                        <!-- Visualizza gli slogan -->
                        <div class="slogan-list mt-2">
                            @foreach ($sponsorship['slogans'] as $slogan)
                                <p class="slogan">{{ $slogan }}</p>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </section>

        <div class="text-center">
            <button id="paymentButton" class="futuristic-button" style="display:none;" onclick="redirectToPayment()">Vai al
                Pagamento <i class="fas fa-key"></i></button>
        </div>

        <!-- Plus Sponsorizzazioni -->
        <section class="benefits my-5 text-center p-4">
            <h3 class="section-title">Perché Sponsorizzare?</h3>
            <div class="benefit-cards d-flex justify-content-around mt-4">
                <div class="benefit-card">
                    <h4 class="benefit-title">🔍 Visibilità</h4>
                    <p class="benefit-description">Fatti trovare facilmente apparendo in cima alle ricerche.</p>
                </div>
                <div class="benefit-card">
                    <h4 class="benefit-title">⭐ Attrattiva</h4>
                    <p class="benefit-description">Il tuo annuncio si distingue con un badge di qualità.</p>
                </div>
                <div class="benefit-card">
                    <h4 class="benefit-title">🚀 Maggiori Prenotazioni</h4>
                    <p class="benefit-description">Più visualizzazioni significano più prenotazioni.</p>
                </div>
            </div>
            <div class="text-center mt-4">
                <a href="{{ route('admin.apartments.index') }}" class="btn futuristic-button">Torna ai tuoi appartamenti</a>
            </div>
        </section>
    </div>
    <style>
        body {
            background-color: #ffffff;
            color: #333;
            font-family: 'Arial', sans-serif;
        }

        .selected-apartment {
            border: 2px solid #28a745;
            /* Colore verde */
            box-shadow: 0 0 5px rgba(40, 167, 69, 0.5);
        }

        .sponsorship-option.selected {
            border: 2px solid #28a745;
            box-shadow: 0 0 10px rgba(40, 167, 69, 0.5);
            background-color: rgba(40, 167, 69, 0.1);
        }

        .sponsorship-title {
            font-size: 2.2rem;
            color: #28a745;
            font-weight: 700;
        }

        .sponsorship-subtitle {
            font-size: 1.2rem;
            color: #666;
            font-weight: 400;
            margin-bottom: 2rem;
        }

        .section-description {
            background-color: #f9f9f9;
            font-family: 'Roboto', sans-serif;
            font-size: 1.2rem;
            color: #333;
            line-height: 1.6;
            text-align: center;
            padding: 20px;
            border-radius: 8px;
        }

        .how-it-works {
            background: #ffffff;
            border-radius: 8px;
            color: #333;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
        }

        .section-title {
            font-size: 1.6rem;
            color: #28a745;
            font-weight: 600;
        }

        .option-list {
            display: flex;
            gap: 20px;
        }

        .sponsorship-option {
            background: #ffffff;
            border-radius: 8px;
            padding: 20px;
            border: 1px solid #28a745;
            box-shadow: 0px 6px 10px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
            text-align: center;
            flex: 1;
            max-width: 300px;
            cursor: pointer;
        }

        .sponsorship-option:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 14px rgba(0, 0, 0, 0.15);
        }

        .option-name {
            font-size: 1.3rem;
            color: #333;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .option-price {
            font-size: 1.2rem;
            color: #28a745;
            font-weight: 500;
            margin-bottom: 0.5rem;
        }

        .option-description {
            color: #666;
            font-size: 0.95rem;
            margin-bottom: 0.5rem;
        }

        .option-duration {
            font-size: 0.85rem;
            color: #28a745;
            font-weight: 500;
        }

        /* Button Styling */
        .futuristic-button {
            background: #28a745;
            color: #ffffff;
            padding: 0.6rem 1.2rem;
            border-radius: 6px;
            font-size: 1rem;
            text-transform: uppercase;
            transition: background 0.3s ease;
            display: inline-block;
        }

        .futuristic-button:hover {
            background-color: #218838;
        }

        /* Benefits Section Styling */
        .benefits {
            background: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
            padding: 2rem;
        }

        .benefit-cards {
            display: flex;
            gap: 20px;
        }

        .benefit-card {
            flex: 1;
            background: #f8f8f8;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
            color: #333;
            transition: all 0.3s ease;
        }

        .benefit-card:hover {
            background: #f1f1f1;
            box-shadow: 0 6px 12px rgba(40, 167, 69, 0.1);
            transform: translateY(-3px);
        }

        .benefit-title {
            font-size: 1.1rem;
            color: #28a745;
            font-weight: 600;
        }

        .benefit-description {
            font-size: 0.95rem;
            color: #666;
            margin-top: 0.5rem;
        }

        /* Media Query for Responsiveness */
        @media (max-width: 768px) {
            .sponsorship-option {
                max-width: 100%;
            }
        }

        .slogan-list {
            margin-top: 10px;
        }

        .slogan {
            font-style: italic;
            color: #007bff;
            /* Colore blu per gli slogan */
        }
    </style>

    <script>
        let selectedSponsorshipId = null;
        let selectedApartmentId = null;

        // Aggiorna apartmentId in base alla selezione dell'appartamento
        document.getElementById('apartmentSelector').addEventListener('change', function() {
            selectedApartmentId = this.value;
            // console.log("Appartamento selezionato ID:", selectedApartmentId); // Log dell'appartamento selezionato
            checkButtonVisibility();

            // Cambia il bordo dell'appartamento selezionato
            const apartmentSelector = document.getElementById('apartmentSelector');
            if (apartmentSelector.value) {
                apartmentSelector.classList.add('selected-apartment');
            } else {
                apartmentSelector.classList.remove('selected-apartment');
            }
        });

        // Seleziona la sponsorizzazione e aggiorna l'ID di sponsorship
        document.querySelectorAll('.sponsorship-option').forEach(option => {
            option.addEventListener('click', function() {
                const isSelected = this.classList.contains('selected');

                // Rimuovi la selezione da tutte le opzioni
                document.querySelectorAll('.sponsorship-option').forEach(opt => opt.classList.remove(
                    'selected'));

                if (!isSelected) {
                    this.classList.add('selected');
                    selectedSponsorshipId = this.getAttribute('data-sponsorship-id');
                    // console.log("Sponsorizzazione selezionata ID:", selectedSponsorshipId);
                } else {
                    selectedSponsorshipId = null;
                }
                checkButtonVisibility();
            });
        });

        function checkButtonVisibility() {
            // Mostra il pulsante di pagamento solo se l'appartamento selezionato non è "Nessuno"
            if (selectedApartmentId !== 'none' && selectedSponsorshipId) {
                document.getElementById('paymentButton').style.display = 'inline';
            } else {
                document.getElementById('paymentButton').style.display = 'none';
            }
        }

        function redirectToPayment() {
            // Controlla se l'appartamento selezionato non è "Nessuno" e se una sponsorizzazione è selezionata
            if (selectedApartmentId && selectedSponsorshipId && selectedApartmentId !== 'none') {
                const url =
                    "{{ route('admin.apartments.payment', ['apartmentId' => ':apartmentId', 'sponsorshipId' => ':sponsorshipId']) }}"
                    .replace(':apartmentId', selectedApartmentId)
                    .replace(':sponsorshipId', selectedSponsorshipId);

                console.log("Reindirizzamento a:", url);
                window.location.href = url;
            } else {
                console.warn("Errore: ID appartamento o ID sponsorizzazione non validi", selectedApartmentId,
                    selectedSponsorshipId);
            }
        }
    </script>
@endsection
