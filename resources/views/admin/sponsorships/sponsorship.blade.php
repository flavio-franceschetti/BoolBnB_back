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
            <p class="section-description animate__animated animate__fadeInUp"
                style="font-family: 'Roboto', sans-serif; font-size: 1.2em; color: #333; line-height: 1.6; text-align: center; background-color: #f9f9f9; padding: 20px; border-radius: 8px;">
                <strong>Metti in luce il tuo appartamento!</strong><br>
                Vai alla sezione “Modifica”, scegli la sponsorizzazione perfetta e raggiungi più utenti. <br>
                <em>Semplice, veloce, e ti fa risaltare!</em>
            </p>
        </section>

        <!-- Sponsorship selezioni -->
        <section class="sponsorship-options my-5">
            <div class="option-list d-flex justify-content-around">
                @foreach ($sponsorships as $sponsorship)
                    <div class="sponsorship-option p-4 mx-2">
                        <h4 class="option-name">{{ $sponsorship['name'] }}</h4>
                        <p class="option-price">{{ number_format($sponsorship['price'], 2) }}€</p>
                        <p class="option-description">{{ $sponsorship['description'] }}</p>
                        <span class="option-duration">Durata: {{ $sponsorship['duration'] }} ore</span>

                        @if (isset($sponsorship['id']))
                            <a href="{{ route('admin.apartments.payment', ['apartmentId' => $apartmentId, 'sponsorshipId' => $sponsorship['id']]) }}"
                                class="btn futuristic-button mt-3">Scegli Sponsorizzazione</a>
                        @else
                            <p class="availability-note">Disponibile nella sezione di modifica dell'appartamento
                                selezionato.</p>
                        @endif
                    </div>
                @endforeach
            </div>
        </section>

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

            <!-- Button to redirect to "My Apartments" page -->
            <div class="text-center mt-4">
                <a href="{{ route('admin.apartments.index') }}" class="btn futuristic-button">
                    Vai alla sezione modifica!
                </a>
            </div>
        </section>
    </div>

    <style>
        body {
            background-color: #ffffff;
            color: #333;
            font-family: 'Arial', sans-serif;
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

        .section-description {
            color: #555;
            font-size: 1rem;
            margin-top: 1rem;
            line-height: 1.6;
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
    </style>
@endsection
