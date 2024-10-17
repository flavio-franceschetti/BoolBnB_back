@extends('layouts.app')

@section('content')
<h1>Modifica i dati dell'appartamento</h1>



<form action="{{ route('admin.apartments.update', $apartment->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <!-- Titolo annuncio -->
    <div class="mb-3">
        <label for="title" class="form-label">Titolo annuncio</label>
        <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title"
            value="{{ old('title', $apartment->title) }}">
        @error('title')
        <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>

    <!-- Proprietà dell'appartamento -->
    <div class="d-flex gap-3 mb-3">
        <div>
            <label for="rooms" class="form-label">N. Camere</label>
            <input type="number" class="form-control @error('rooms') is-invalid @enderror" id="rooms" name="rooms"
                value="{{ old('rooms', $apartment->rooms) }}">
            @error('rooms')
            <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>
        <div>
            <label for="beds" class="form-label">N. Letti</label>
            <input type="number" class="form-control @error('beds') is-invalid @enderror" id="beds" name="beds"
                value="{{ old('beds', $apartment->beds) }}">
            @error('beds')
            <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>
        <div>
            <label for="bathrooms" class="form-label">N. Bagni</label>
            <input type="number" class="form-control @error('bathrooms') is-invalid @enderror" id="bathrooms"
                name="bathrooms" value="{{ old('bathrooms', $apartment->bathrooms) }}">
            @error('bathrooms')
            <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div>
            <label for="mq" class="form-label">Metri Quadri</label>
            <input type="number" class="form-control @error('mq') is-invalid @enderror" id="mq" name="mq"
                value="{{ old('mq', $apartment->mq) }}">
            @error('mq')
            <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>
    </div>

    <!-- Indirizzo -->
    <div class="d-flex gap-3 mb-3">
        <div>
            <label for="address" class="form-label">Indirizzo</label>
            <input type="text" class="form-control @error('address') is-invalid @enderror" id="address" name="address"
                value="{{ old('address', $apartment->address) }}">
            @error('address')
            <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div>
            <label for="civic_number" class="form-label">N. Civico</label>
            <input type="number" class="form-control @error('civic_number') is-invalid @enderror" id="civic_number"
                name="civic_number" value="{{ old('civic_number', $apartment->civic_number) }}">
            @error('civic_number')
            <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div>
            <label for="city" class="form-label">Città</label>
            <input type="text" class="form-control @error('city') is-invalid @enderror" id="city" name="city"
                value="{{ old('city', $apartment->city) }}">
            @error('city')
            <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>
    </div>

    <!-- Servizi -->
    <div class="btn-group mb-3" role="group" aria-label="Basic checkbox toggle button group">
        @foreach ($services as $service)
        <input name="services[]" type="checkbox" value="{{ $service->id }}" class="btn-check"
            id="service-{{ $service->id }}" autocomplete="off" @if (in_array($service->id, old('services',
        $apartment->services->pluck('id')->toArray()))) checked @endif>
        <label class="btn btn-outline-primary" for="service-{{ $service->id }}">{{ $service->name }}</label>
        @endforeach
        @error('services')
        <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>

    <!-- Immagine -->
    <div class="mb-3">
        <label for="img" class="form-label">Immagine</label>
        <input class="form-control" type="file" id="img" name="img[]" multiple
            value="{{ old('city', $apartment->city) }}">
        @error('img')
        <small class="text-danger">{{ $message }}</small>
        @enderror
        <!-- Mostra immagini esistenti -->
        @if ($apartment->img)
        <div class="mt-3">
            <p>Immagini esistenti:</p>
            @foreach (explode(',', $apartment->img) as $image)
            <img src="{{ asset('storage/' . trim($image)) }}" alt="Immagine appartamento" style="width: 150px;">
            @endforeach
        </div>
        @endif
    </div>

    <!-- Visibilità -->
    <div class="is-visible-radios mb-3">
        <div>Visibile</div>
        <div class="form-check">
            <input class="form-check-input" type="radio" name="is_visible" value="1" id="flexRadioDefault1" checked>
            <label class="form-check-label" for="flexRadioDefault1">
                Si
            </label>
        </div>
        <div class="form-check">
            <input class="form-check-input" type="radio" name="is_visible" value="0" id="flexRadioDefault2">
            <label class="form-check-label" for="flexRadioDefault2">
                No
            </label>
        </div>
    </div>

    <!-- Pulsanti -->
    <div class="mb-3">
        <input type="submit" class="btn btn-primary" value="Modifica">
        <input type="reset" class="btn btn-danger" value="Annulla">
    </div>
</form>

<!-- Sezione Sponsorizzazioni e pagamenti-->
{{-- <div class="sponsorship-section mb-3">
    <h2>Acquista una sponsorizzazione</h2>
    <ul class="list-group">
        @foreach ($sponsorships as $sponsorship)
        <li class="list-group-item d-flex justify-content-between align-items-center">
            <span>{{ $sponsorship->name }} - €{{ $sponsorship->price }}</span>
            <a href="{{ route('admin.sponsorships.purchase', $sponsorship->id) }}" class="btn btn-success">Acquista</a>

            </a>
        </li>
        @endforeach
    </ul>
</div> --}}
@endsection