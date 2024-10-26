@extends('layouts.app')

@section('content')
    <h1>Modifica i dati dell'appartamento</h1>

    @if ($errors->any())
        <div class="alert alert-danger" role="alert">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.apartments.update', $apartment->id) }}" method="POST" enctype="multipart/form-data"
        id="apartmentForm">
        @csrf
        @method('PUT')

        <!-- Titolo annuncio -->
        <div class="mb-3">
            <label for="title" class="form-label">Titolo annuncio</label>
            <input type="text" class="form-control" id="title" name="title"
                value="{{ old('title', $apartment->title) }}" required>
            <small class="text-danger" id="titleError" style="display: none;"></small>
        </div>

        <!-- Proprietà dell'appartamento -->
        <div class="row mb-3">
            <div class="col-md-3">
                <label for="rooms" class="form-label">N. Camere</label>
                <input type="number" class="form-control" id="rooms" name="rooms"
                    value="{{ old('rooms', $apartment->rooms) }}" required min="1">
                <small class="text-danger" id="roomsError" style="display: none;"></small>
            </div>
            <div class="col-md-3">
                <label for="beds" class="form-label">N. Letti</label>
                <input type="number" class="form-control" id="beds" name="beds"
                    value="{{ old('beds', $apartment->beds) }}" required min="1">
                <small class="text-danger" id="bedsError" style="display: none;"></small>
            </div>
            <div class="col-md-3">
                <label for="bathrooms" class="form-label">N. Bagni</label>
                <input type="number" class="form-control" id="bathrooms" name="bathrooms"
                    value="{{ old('bathrooms', $apartment->bathrooms) }}" required min="1">
                <small class="text-danger" id="bathroomsError" style="display: none;"></small>
            </div>
            <div class="col-md-3">
                <label for="mq" class="form-label">Metri Quadri</label>
                <input type="number" class="form-control" id="mq" name="mq"
                    value="{{ old('mq', $apartment->mq) }}" required min="1">
                <small class="text-danger" id="mqError" style="display: none;"></small>
            </div>
        </div>

        <!-- Searchbox -->
        <div id="search-box-container" class="mb-3"></div>

        <!-- Servizi -->
        <fieldset class="mb-3">
            <legend>Servizi</legend>
            <div class="d-flex flex-wrap gap-2">
                @foreach ($services as $service)
                    <div>
                        <input name="services[]" type="checkbox" value="{{ $service->id }}" class="btn-check"
                            id="service-{{ $service->id }}" autocomplete="off"
                            @if (in_array($service->id, old('services', $apartment->services->pluck('id')->toArray()))) checked @endif>
                        <label class="btn btn-outline-primary"
                            for="service-{{ $service->id }}">{{ $service->name }}</label>
                    </div>
                @endforeach
            </div>
            <small class="text-danger" id="servicesError" style="display: none;"></small>
        </fieldset>

        <!-- Mostra immagini esistenti -->
        <div class="form-group mb-3">
            <label>Immagini attuali dell'appartamento:</label>
            <div class="row">
                @foreach ($apartment->images as $image)
                    <div class="col-md-2 mb-3 text-center">
                        <img src="{{ asset('storage/' . $image->img_path) }}" alt="{{ $image->img_name }}"
                            class="img-fluid">
                        <div class="form-check mt-2">
                            <input type="checkbox" name="delete_images[]" value="{{ $image->id }}"
                                class="form-check-input delete-checkbox" id="delete-image-{{ $image->id }}"
                                autocomplete="off">
                            <label class="form-check-label" for="delete-image-{{ $image->id }}">seleziona per modificare
                                o eliminare</label>
                        </div>
                        <input type="hidden" name="existing_images[]" value="{{ $image->id }}">
                    </div>
                @endforeach
            </div>
            <!-- Messaggio dinamico per immagini selezionate -->
            <small class="text-info" id="imagesCount" style="display: none;"></small>
        </div>

        <!-- Aggiungi immagini -->
        <div class="mb-3">
            <label for="images" class="form-label text-primary">modifica l'immagine selezionando il file già inserito.
                <br><span class="text-danger"> oppure eleziona
                    l'immagine per eliminarla</span>
                <br></label>
            <input class="form-control" type="file" id="images" name="images[]" multiple required
                accept="image/*">
            @error('images')
                <small class="text-danger">{{ $message }}</small>
            @enderror
            <small class="text-primary" id="imagesCount" style="display: block;">Inserisci da 1 a 3 file massimi.
                <br>(Puoi
                caricare
                solo file JPG, JPEG, PNG,
                WEBP massimo 10MB)</small>
            <small class="text-danger" id="imagesError" style="display: none;"></small>
        </div>

        <!-- Visibilità -->
        <fieldset class="mb-3">
            <legend>Visibilità</legend>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="is_visible" value="1" id="flexRadioDefault1"
                    checked>
                <label class="form-check-label" for="flexRadioDefault1">Si</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="is_visible" value="0" id="flexRadioDefault2">
                <label class="form-check-label" for="flexRadioDefault2">No</label>
            </div>
        </fieldset>

        <!-- Sponsorship Selection -->
        <div class="form-group mb-3">
            <label for="sponsorship">Seleziona una Sponsorizzazione:</label>
            <select name="sponsorship_id" class="form-control" id="sponsorshipSelect">
                <option value="">seleziona la sponsorizzazione</option>
                @foreach ($sponsorships as $sponsorship)
                    <option value="{{ $sponsorship->id }}">{{ $sponsorship->name }} - €{{ $sponsorship->price }}
                    </option>
                @endforeach
            </select>
            <div class="mt-3">
                <a href="#" class="btn btn-success" id="paymentLink">Sponsorizza Appartamento</a>
            </div>
        </div>

        <!-- Pulsanti -->
        <div class="d-flex justify-content-between mt-4">
            <input type="submit" class="btn btn-primary" value="Modifica">
            <input type="reset" class="btn btn-danger" value="Annulla">
        </div>
    </form>

    <style>
        .is-valid {
            border-color: green;
        }

        .is-invalid {
            border-color: red;
        }
    </style>

    <script>
        // SEZIONE DELLA SEARCHBOX
        const apiKey = "{{ config('app.tomtomApiKey') }}";
        let options = {
            searchOptions: {
                key: apiKey,
                language: "it-IT",
                limit: 10,
                countrySet: ["IT"],
            },
            autocompleteOptions: {
                key: apiKey,
                language: "it-IT",
                countrySet: ["IT"],
            },
            labels: {
                suggestions: {
                    brand: "Brand Suggerito",
                    category: "Categoria Suggerita",
                },
                placeholder: "Inserisci l'indirizzo",
                noResultsMessage: "Nessun risultato trovato",
            },
        };

        let ttSearchBox = new tt.plugins.SearchBox(tt.services, options);
        let searchBoxHTML = ttSearchBox.getSearchBoxHTML();
        document.getElementById("search-box-container").append(searchBoxHTML);

        let searchInput = document.querySelector("#search-box-container input");
        searchInput.setAttribute("name", "address");
        searchInput.setAttribute("id", "address");
        searchInput.setAttribute("required", true);
        searchInput.setAttribute("autocomplete", "off");

        @if ($errors->has('address'))
            searchInput.classList.add("is-invalid");
        @endif

        searchInput.value = '{{ old('address', $apartment->address) }}';

        // SEZIONE DEI CONTROLLI
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('apartmentForm');

            function showError(fieldId, message) {
                const errorElement = document.getElementById(fieldId + 'Error');
                errorElement.textContent = message;
                errorElement.style.display = 'block';
                const inputField = document.getElementById(fieldId);
                inputField.classList.remove('is-valid');
                inputField.classList.add('is-invalid');
            }

            function hideError(fieldId) {
                const errorElement = document.getElementById(fieldId + 'Error');
                errorElement.style.display = 'none';
                const inputField = document.getElementById(fieldId);
                inputField.classList.remove('is-invalid');
                inputField.classList.add('is-valid');
            }

            // Gestione degli eventi di input per ciascun campo
            document.getElementById('title').addEventListener('input', function() {
                if (this.value.trim() === '') {
                    showError('title', 'Il titolo è obbligatorio.');
                } else {
                    hideError('title');
                }
            });

            document.getElementById('rooms').addEventListener('input', function() {
                if (this.value <= 0) {
                    showError('rooms', 'Devi inserire un numero valido di camere.');
                } else {
                    hideError('rooms');
                }
            });

            document.getElementById('beds').addEventListener('input', function() {
                if (this.value <= 0) {
                    showError('beds', 'Devi inserire un numero valido di letti.');
                } else {
                    hideError('beds');
                }
            });

            document.getElementById('bathrooms').addEventListener('input', function() {
                if (this.value <= 0) {
                    showError('bathrooms', 'Devi inserire un numero valido di bagni.');
                } else {
                    hideError('bathrooms');
                }
            });

            document.getElementById('mq').addEventListener('input', function() {
                if (this.value < 30) {
                    showError('mq', 'Devi inserire almeno 30 metri quadri.');
                } else if (this.value <= 0) {
                    showError('mq', 'Devi inserire un numero valido di metri quadri.');
                } else {
                    hideError('mq');
                }
            });

            document.getElementById('address').addEventListener('input', function() {
                if (this.value.trim() === '') {
                    showError('address', 'L\'indirizzo è obbligatorio.');
                } else {
                    hideError('address');
                }
            });

            const imagesInput = document.getElementById('images');
            imagesInput.addEventListener('change', function() {
                const files = this.files;
                const maxFiles = 3;

                // Controllo numero file
                const fileCount = files.length;

                if (fileCount === 0) {
                    document.getElementById('imagesCount').textContent = "Inserisci da 1 a 3 file massimi.";
                } else if (fileCount > maxFiles) {
                    showError('images', `Puoi caricare al massimo ${maxFiles} immagini.`);
                } else if (fileCount === maxFiles) {
                    document.getElementById('imagesCount').textContent =
                        `Hai inserito ${fileCount}/${maxFiles} file.`;
                    hideError('images');
                } else {
                    const remainingFiles = maxFiles - fileCount;
                    document.getElementById('imagesCount').textContent =
                        `Mancano ancora ${remainingFiles} file da inserire.`;
                    hideError('images');
                }

                // Controllo dimensione file
                let valid = true;
                for (let i = 0; i < files.length; i++) {
                    if (files[i].size > 2 * 1024 * 1024) { // Cambiato a 2 MB
                        showError('images',
                            'Il file che stai cercando di caricare è superiore a 2 MB e non è accettato.'
                        );
                        valid = false;
                        break;
                    }

                    // Controllo tipo di file
                    const fileType = files[i].type;
                    const validTypes = ['image/jpeg', 'image/png', 'image/webp'];

                    if (!validTypes.includes(fileType)) {
                        showError('images', 'Puoi caricare solo file JPG, JPEG, PNG, WEBP.');
                        valid = false;
                        break;
                    }
                }

                if (valid) {
                    hideError('images');
                }
            });

            // Validazione in tempo reale per il titolo
            document.getElementById('title').addEventListener('input', function() {
                const titleError = document.getElementById('titleError');
                if (this.value.trim() === '') {
                    titleError.innerText = 'Il titolo è obbligatorio.';
                    titleError.style.display = 'block';
                    this.classList.add('is-invalid');
                } else {
                    titleError.style.display = 'none';
                    this.classList.remove('is-invalid');
                    this.classList.add('is-valid');
                }
            });

            document.getElementById('sponsorshipSelect').addEventListener('change', function() {
                const sponsorshipId = this.value;
                const apartmentId = "{{ $apartment->id }}";
                const paymentLink = document.getElementById('paymentLink');

                if (sponsorshipId) {
                    paymentLink.href =
                        "{{ route('admin.apartments.payment', ['apartmentId' => ':apartmentId', 'sponsorshipId' => ':sponsorshipId']) }}"
                        .replace(':apartmentId', apartmentId)
                        .replace(':sponsorshipId', sponsorshipId);
                    paymentLink.style.display = 'inline'; // Mostra il link se non è visibile
                } else {
                    paymentLink.style.display =
                        'none'; // Nascondi se nessuna sponsorizzazione è selezionata
                }
            });
        });
    </script>
@endsection
