@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <h2>Dettagli appartamento</h2>
                    </div>

                    {{-- IMMAGINE APPARTAMENTO  --}}
                    {{-- @if ($apartment->img)
                    <div class="card">
                        <div class="card-header bg-secondary text-white">
                            <h4>Immagini dell'appartamento</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                @php
                                    $images = explode(', ', $apartment->img);
                                @endphp
                                @foreach ($images as $image)
                                    <div class="col-md-4 mb-3">
                                        <img src="{{ asset('storage/' . $image) }}" class="img-fluid rounded"
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
                @endif --}}


                    {{-- INIZIO CARD PER I DETTAGLI  --}}

                    <div class="card-body">
                        <h3 class="card-title"></h3>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <p><strong>Stanze:</strong> </p>
                                <p><strong>Letti:</strong> </p>
                                <p><strong>Bagni:</strong> </p>
                                <p><strong>Metri quadrati:</strong> m²</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Città:</strong> </p>
                                <p><strong>Indirizzo:</strong> via, codice postale,
                                    numero civico</p>
                                <p><strong>Latitudine:</strong></p>
                                <p><strong>Longitudine:</strong></p>
                                <p><strong>Visibile:</strong> SI/NO </p>
                            </div>
                        </div>

                        {{-- Sezione Servizi --}}
                        <div class="mb-3">
                            <h4>Servizi dell'appartamento</h4>
                            @if ($apartment->services->count() > 0)
                                @foreach ($apartment->services as $service)
                                    <span class="badge bg-warning">{{ $service->name }}</span>
                                @endforeach
                            @else
                                <p>Nessun servizio disponibile</p>
                            @endif
                        </div>

                        <div class="text-muted">
                            <p><strong>Data creazione:</strong> </p>
                            <p><strong>Ultima modifica:</strong> </p>
                        </div>
                    </div>
                </div>


                {{-- Sezione Sponsorships --}}
                <div class="mb-3">
                    <h4>Sponsorizzazioni attive</h4>
                    @if ($apartment->sponsorships)
                        @foreach ($apartment->sponsorships as $sponsorship)
                            <div class="card mb-2">
                                <div class="card-body">
                                    <h5 class="card-title">{{ $sponsorship->name }}</h5>
                                    <p><strong>Prezzo:</strong> €{{ number_format($sponsorship->price, 2) }}</p>
                                    <p><strong>Durata:</strong> {{ $sponsorship->duration }} ore</p>


                                </div>
                            </div>
                        @endforeach
                    @else
                        <p>Nessuna sponsorizzazione attiva</p>
                    @endif
                </div>

                {{-- BTN PER TORNARE ALL' ELENCO APPARTAMENTI --}}
                <div class="mt-4">
                    <a href="{{ route('admin.apartments.index') }}" class="btn btn-primary">Torna all'elenco</a>
                </div>
            </div>
        </div>
    </div>
@endsection
