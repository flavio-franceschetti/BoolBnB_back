@extends('layouts.app')

@section('content')
<h1>Modifica i dati dell'appartamento</h1>

@if($errors->any())
<div class="alert alert-danger" role="alert">
    <ul>
        @foreach($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<form action="{{ route('admin.apartments.update', $apartment->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <!-- Titolo annuncio -->
    <div class="mb-3">
        <label for="title" class="form-label">Titolo annuncio</label>
        <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title"
            value="{{ old('title', $apartment->title) }}" required>
        @error('title')
        <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>

    <!-- Proprietà dell'appartamento -->
    <div class="d-flex gap-3 mb-3">
        <div>
            <label for="rooms" class="form-label">N. Camere</label>
            <input type="number" class="form-control @error('rooms') is-invalid @enderror" id="rooms" name="rooms"
                value="{{ old('rooms', $apartment->rooms) }}" required>
            @error('rooms')
            <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>
        <div>
            <label for="beds" class="form-label">N. Letti</label>
            <input type="number" class="form-control @error('beds') is-invalid @enderror" id="beds" name="beds"
                value="{{ old('beds', $apartment->beds) }}" required>
            @error('beds')
            <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>
        <div>
            <label for="bathrooms" class="form-label">N. Bagni</label>
            <input type="number" class="form-control @error('bathrooms') is-invalid @enderror" id="bathrooms"
                name="bathrooms" value="{{ old('bathrooms', $apartment->bathrooms) }}" required>
            @error('bathrooms')
            <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div>
            <label for="mq" class="form-label">Metri Quadri</label>
            <input type="number" class="form-control @error('mq') is-invalid @enderror" id="mq" name="mq"
                value="{{ old('mq', $apartment->mq) }}" required>
            @error('mq')
            <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>
    </div>

    <!-- Indirizzo -->
    <div class="mb-3">
        <label for="address" class="form-label">Indirizzo</label>
        <input type="text" class="form-control @error('address') is-invalid @enderror" id="address" name="address"
            value="{{ old('address', $apartment->address) }}" required>
        @error('address')
        <small class="text-danger">{{ $message }}</small>
        @enderror
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

    <!-- Mostra immagini esistenti -->
    <div class="form-group mb-3">
        <label>Immagini attuali dell'appartamento:</label>
        <div class="row">
            @foreach ($apartment->images as $image)
            <div class="col-md-2 mb-3">
                <img src="{{ asset('storage/' . $image->img_path) }}" alt="{{ $image->img_name }}" class="img-fluid">
                <div class="form-check mt-2">
                    <input type="checkbox" name="delete_images[]" value="{{ $image->id }}" class="form-check-input">
                    <label class="form-check-label">Elimina questa immagine</label>
                </div>
                <!-- Hidden input to retain non-deleted images -->
                <input type="hidden" name="existing_images[]" value="{{ $image->id }}">
            </div>
            @endforeach
        </div>
    </div>
    <!-- Immagine -->
    <div class="mb-3">
        <label for="" class="form-label">Aggiungi immagini</label>
        <input class="form-control" type="file" id="images" name="images[]" multiple accept="image/*">
        @error('images')
        <small class="text-danger">{{ $message }}</small>
        @enderror
        @error('images.*')
        <small class="text-danger">{{ $message }}</small>
        @enderror

        {{-- @if($errors->any())
        <small class="text-danger">Inserisci nuovamente le immagini</small>
        @endif --}}
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