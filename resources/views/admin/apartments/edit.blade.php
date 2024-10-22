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
        <div class="d-flex gap-3 mb-3">
            <div>
                <label for="rooms" class="form-label">N. Camere</label>
                <input type="number" class="form-control" id="rooms" name="rooms"
                    value="{{ old('rooms', $apartment->rooms) }}" required>
                <small class="text-danger" id="roomsError" style="display: none;"></small>
            </div>
            <div>
                <label for="beds" class="form-label">N. Letti</label>
                <input type="number" class="form-control" id="beds" name="beds"
                    value="{{ old('beds', $apartment->beds) }}" required>
                <small class="text-danger" id="bedsError" style="display: none;"></small>
            </div>
            <div>
                <label for="bathrooms" class="form-label">N. Bagni</label>
                <input type="number" class="form-control" id="bathrooms" name="bathrooms"
                    value="{{ old('bathrooms', $apartment->bathrooms) }}" required>
                <small class="text-danger" id="bathroomsError" style="display: none;"></small>
            </div>
            <div>
                <label for="mq" class="form-label">Metri Quadri</label>
                <input type="number" class="form-control" id="mq" name="mq"
                    value="{{ old('mq', $apartment->mq) }}" required>
                <small class="text-danger" id="mqError" style="display: none;"></small>
            </div>
        </div>

        {{-- searchbox --}}
        <div id="search-box-container" class="mb-3"></div>

        <!-- Servizi -->
        <div class="btn-group mb-3" role="group" aria-label="Basic checkbox toggle button group">
            @foreach ($services as $service)
                <input name="services[]" type="checkbox" value="{{ $service->id }}" class="btn-check"
                    id="service-{{ $service->id }}" autocomplete="off" @if (in_array($service->id, old('services', $apartment->services->pluck('id')->toArray()))) checked @endif>
                <label class="btn btn-outline-primary" for="service-{{ $service->id }}">{{ $service->name }}</label>
            @endforeach
            <small class="text-danger" id="servicesError" style="display: none;"></small>
        </div>

        <!-- Mostra immagini esistenti -->
        <div class="form-group mb-3">
            <label>Immagini attuali dell'appartamento:</label>
            <div class="row">
                @foreach ($apartment->images as $image)
                    <div class="col-md-2 mb-3">
                        <img src="{{ asset('storage/' . $image->img_path) }}" alt="{{ $image->img_name }}"
                            class="img-fluid">
                        <div class="form-check mt-2">
                            <input type="checkbox" name="delete_images[]" value="{{ $image->id }}"
                                class="form-check-input delete-checkbox" id="delete-image-{{ $image->id }}"
                                autocomplete="off">
                            <label class="form-check-label" for="delete-image-{{ $image->id }}">Elimina questa
                                immagine</label>
                        </div>
                        <input type="hidden" name="existing_images[]" value="{{ $image->id }}">
                    </div>
                @endforeach
            </div>
            <!-- Messaggio dinamico per immagini selezionate -->
            <small class="text-info" id="imagesCount" style="display: none;"></small>
        </div>

        <!-- Immagine -->
        <div class="mb-3">
            <label for="images" class="form-label">Modifica o aggiungi Immagini (opzionale)</label>
            <input class="form-control" type="file" id="images" name="images[]" multiple accept="image/*">
            <small class="text-danger" id="imagesError" style="display: none;"></small>
        </div>

        <!-- Visibilità -->
        <div class="is-visible-radios mb-3">
            <div>Visibile</div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="is_visible" value="1" id="flexRadioDefault1"
                    checked>
                <label class="form-check-label" for="flexRadioDefault1"> Si </label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="is_visible" value="0" id="flexRadioDefault2">
                <label class="form-check-label" for="flexRadioDefault2"> No </label>
            </div>
        </div>

        <!-- Pulsanti -->
        <div class="mb-3">
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

            // Validazione in tempo reale per il titolo
            document.getElementById('title').addEventListener('input', function() {
                const titleError = document.getElementById('titleError');
                if (this.value.trim() === '') {
                    titleError.textContent = 'Il titolo è obbligatorio.';
                    titleError.style.display = 'block';
                    this.classList.remove('is-valid');
                    this.classList.add('is-invalid');
                } else {
                    titleError.style.display = 'none';
                    this.classList.remove('is-invalid');
                    this.classList.add('is-valid');
                }
            });

            // Validazione in tempo reale per il numero di camere
            document.getElementById('rooms').addEventListener('input', function() {
                const roomsError = document.getElementById('roomsError');
                if (this.value <= 0) {
                    roomsError.textContent = 'Devi inserire un numero valido di camere.';
                    roomsError.style.display = 'block';
                    this.classList.remove('is-valid');
                    this.classList.add('is-invalid');
                } else {
                    roomsError.style.display = 'none';
                    this.classList.remove('is-invalid');
                    this.classList.add('is-valid');
                }
            });

            // Validazione in tempo reale per il numero di letti
            document.getElementById('beds').addEventListener('input', function() {
                const bedsError = document.getElementById('bedsError');
                if (this.value <= 0) {
                    bedsError.textContent = 'Devi inserire un numero valido di letti.';
                    bedsError.style.display = 'block';
                    this.classList.remove('is-valid');
                    this.classList.add('is-invalid');
                } else {
                    bedsError.style.display = 'none';
                    this.classList.remove('is-invalid');
                    this.classList.add('is-valid');
                }
            });

            // Validazione in tempo reale per il numero di bagni
            document.getElementById('bathrooms').addEventListener('input', function() {
                const bathroomsError = document.getElementById('bathroomsError');
                if (this.value <= 0) {
                    bathroomsError.textContent = 'Devi inserire un numero valido di bagni.';
                    bathroomsError.style.display = 'block';
                    this.classList.remove('is-valid');
                    this.classList.add('is-invalid');
                } else {
                    bathroomsError.style.display = 'none';
                    this.classList.remove('is-invalid');
                    this.classList.add('is-valid');
                }
            });

            // Validazione in tempo reale per i metri quadri
            document.getElementById('mq').addEventListener('input', function() {
                const mqError = document.getElementById('mqError');
                if (this.value <= 0) {
                    mqError.textContent = 'Devi inserire un valore valido per i metri quadri.';
                    mqError.style.display = 'block';
                    this.classList.remove('is-valid');
                    this.classList.add('is-invalid');
                } else {
                    mqError.style.display = 'none';
                    this.classList.remove('is-invalid');
                    this.classList.add('is-valid');
                }
            });

            // Funzione di validazione per le immagini
            function validateImages() {
                const imagesError = document.getElementById('imagesError');
                const deleteImageCheckboxes = document.querySelectorAll('input[name="delete_images[]"]');
                const checkedDeleteImagesCount = Array.from(deleteImageCheckboxes).filter(checkbox => checkbox
                    .checked).length;
                const imagesCount = document.getElementById('imagesCount');

                // Messaggio informativo per il numero di file selezionati
                if (checkedDeleteImagesCount > 0) {
                    imagesCount.style.display = 'block';
                    if (checkedDeleteImagesCount === 1) {
                        imagesCount.textContent = `Hai selezionato 1 immagine da eliminare.`;
                    } else {
                        imagesCount.textContent =
                            `Hai selezionato ${checkedDeleteImagesCount} immagini da eliminare.`;
                    }
                } else {
                    // Se non ci sono checkbox selezionate, nascondiamo il messaggio
                    imagesCount.style.display = 'none';
                }

                return true; // Non ci sono errori
            }

            // Validazione finale al submit
            form.addEventListener('submit', function(event) {
                let valid = true;

                // Altri controlli di validazione
                if (document.getElementById('title').classList.contains('is-invalid')) {
                    valid = false;
                }
                if (document.getElementById('rooms').classList.contains('is-invalid')) {
                    valid = false;
                }
                if (document.getElementById('beds').classList.contains('is-invalid')) {
                    valid = false;
                }
                if (document.getElementById('bathrooms').classList.contains('is-invalid')) {
                    valid = false;
                }
                if (document.getElementById('mq').classList.contains('is-invalid')) {
                    valid = false;
                }

                // Controllo finale per le immagini
                // Non è necessario validare se il campo immagini è vuoto
                validateImages();

                // Se non è valido, impediamo l'invio del modulo
                if (!valid) {
                    event.preventDefault();
                }
            });

            // Aggiungi evento di change per le checkbox di eliminazione immagini
            const deleteCheckboxes = document.querySelectorAll('.delete-checkbox');
            deleteCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', validateImages);
            });
        });
    </script>
@endsection
