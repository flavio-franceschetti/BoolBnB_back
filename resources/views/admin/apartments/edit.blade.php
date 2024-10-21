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
        <input type="text" class="form-control" id="title" name="title" value="{{ old('title', $apartment->title) }}"
            required>
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
            <input type="number" class="form-control" id="beds" name="beds" value="{{ old('beds', $apartment->beds) }}"
                required>
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
            <input type="number" class="form-control" id="mq" name="mq" value="{{ old('mq', $apartment->mq) }}"
                required>
            <small class="text-danger" id="mqError" style="display: none;"></small>
        </div>
    </div>
    <!-- Indirizzo -->
    {{-- <div class="mb-3">
        <label for="address" class="form-label">Indirizzo</label>
        <input type="text" class="form-control" id="address" name="address"
            value="{{ old('address', $apartment->address) }}" required>

        <small class="text-danger" id="addressError" style="display: none;"></small>
    </div> --}}

    {{-- searchbox --}}
    <div id="search-box-container" class="mb-3"></div>

    <!-- Servizi -->
    <div class="btn-group mb-3" role="group" aria-label="Basic checkbox toggle button group">
        @foreach ($services as $service)
        <input name="services[]" type="checkbox" value="{{ $service->id }}" class="btn-check"
            id="service-{{ $service->id }}" autocomplete="off" @if (in_array($service->id, old('services',
        $apartment->services->pluck('id')->toArray()))) checked @endif>
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
        <small class="text-danger" id="imagesError" style="display: none;"></small>
    </div>

    <!-- Visibilità -->
    <div class="is-visible-radios mb-3">
        <div>Visibile</div>
        <div class="form-check">
            <input class="form-check-input" type="radio" name="is_visible" value="1" id="flexRadioDefault1" checked>
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

<style>
    .is-valid {
        border-color: green;
        /* Cambia il colore del bordo in verde */
    }

    .is-invalid {
        border-color: red;
        /* Cambia il colore del bordo in rosso */
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

    @if ($errors->has('address'))
        searchInput.classList.add("is-invalid");
    @endif

    searchInput.value = '{{ old('address', $apartment->address) }}';

    // SEZIONE DEI CONTROLLI
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('apartmentForm')});

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
            if (this.value < 30) {
                mqError.textContent = 'Devi inserire almeno 30 m²';
                mqError.style.display = 'block';
                this.classList.remove('is-valid');
                this.classList.add('is-invalid');
            } else if (this.value <= 0) {
                mqError.textContent = 'Devi inserire un numero valido di m²';
                mqError.style.display = 'block';
                this.classList.remove('is-valid');
                this.classList.add('is-invalid');
            } else {
                mqError.style.display = 'none';
                this.classList.remove('is-invalid');
                this.classList.add('is-valid');
            }
        });

        // Validazione in tempo reale per l'indirizzo
        document.getElementById('address').addEventListener('input', function() {
            const addressError = document.getElementById('addressError');
            if (this.value.trim() === '') {
                addressError.textContent = 'L\'indirizzo è obbligatorio.';
                addressError.style.display = 'block';
                this.classList.remove('is-valid');
                this.classList.add('is-invalid');
            } else {
                addressError.style.display = 'none';
                this.classList.remove('is-invalid');
                this.classList.add('is-valid');
            }
        });

        // Validazione in tempo reale per i servizi
        const services = document.querySelectorAll('input[name="services[]"]');
        services.forEach(service => {
            service.addEventListener('change', function() {
                const servicesError = document.getElementById('servicesError');
                const checkedServices = Array.from(services).some(s => s.checked);
                if (!checkedServices) {
                    servicesError.textContent = 'Devi selezionare almeno un servizio.';
                    servicesError.style.display = 'block';
                } else {
                    servicesError.style.display = 'none';
                }
            });
        });

        // Event listener for delete image checkboxes
        const deleteImageCheckboxes = document.querySelectorAll('input[name="delete_images[]"]');
        deleteImageCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                if (this.checked) {
                    deletedImagesCount++;
                } else {
                    deletedImagesCount--;
                }
            });
        });

        // Funzione di validazione per le immagini
        function validateImages() {
            const imagesError = document.getElementById('imagesError');
            const files = imageInput.files;
            let valid = true;

            // Validation logic
            if ((deletedImagesCount === existingImagesCount) || (existingImagesCount === 0)) {
                if (files.length === 0) {
                    valid = false;
                    imagesError.textContent = 'Le immagini sono obbligatorie.';
                    imagesError.style.display = 'block';
                    imageInput.classList.remove('is-valid');
                    imageInput.classList.add('is-invalid');
                } else if (files.length > maxNewImages) {
                    valid = false;
                    imagesError.textContent = `Puoi caricare al massimo ${maxNewImages} immagini.`;
                    imagesError.style.display = 'block';
                    imageInput.classList.remove('is-valid');
                    imageInput.classList.add('is-invalid');
                } else {
                    for (let file of files) {
                        if (!['image/jpeg', 'image/png', 'image/webp'].includes(file.type)) {
                            valid = false;
                            imagesError.textContent = 'Formato non supportato. Usa jpg, jpeg, png o webp.';
                            imagesError.style.display = 'block';
                            imageInput.classList.remove('is-valid');
                            imageInput.classList.add('is-invalid');
                            break;
                        } else if (file.size > 10240 * 1024) {
                            valid = false;
                            imagesError.textContent = 'Le immagini devono essere max 10 MB.';
                            imagesError.style.display = 'block';
                            imageInput.classList.remove('is-valid');
                            imageInput.classList.add('is-invalid');
                            break;
                        }
                    }
                }
            } else {
                if (files.length > maxNewImages) {
                    valid = false;
                    imagesError.textContent = `Puoi caricare al massimo ${maxNewImages} immagini.`;
                    imagesError.style.display = 'block';
                    imageInput.classList.remove('is-valid');
                    imageInput.classList.add('is-invalid');
                }
            }

            if (valid) {
                imagesError.style.display = 'none';
                imageInput.classList.remove('is-invalid');
                imageInput.classList.add('is-valid');
            }

            return valid;
        }

        // Validazione finale al submit
        form.addEventListener('submit', function(event) {
            let valid = true;

            // Controllo per il titolo
            if (document.getElementById('title').value.trim() === '') {
                valid = false;
                document.getElementById('titleError').textContent = 'Il titolo è obbligatorio.';
                document.getElementById('titleError').style.display = 'block';
                document.getElementById('title').classList.remove('is-valid');
                document.getElementById('title').classList.add('is-invalid');
            }

            // Controllo per il numero di camere
            if (document.getElementById('rooms').value <= 0) {
                valid = false;
                document.getElementById('roomsError').textContent = 'Devi inserire un numero valido di camere.';
                document.getElementById('roomsError').style.display = 'block';
                document.getElementById('rooms').classList.remove('is-valid');
                document.getElementById('rooms').classList.add('is-invalid');
            }

            // Controllo per il numero di letti
            if (document.getElementById('beds').value <= 0) {
                valid = false;
                document.getElementById('bedsError').textContent = 'Devi inserire un numero valido di letti.';
                document.getElementById('bedsError').style.display = 'block';
                document.getElementById('beds').classList.remove('is-valid');
                document.getElementById('beds').classList.add('is-invalid');
            }

            // Controllo per il numero di bagni
            if (document.getElementById('bathrooms').value <= 0) {
                valid = false;
                document.getElementById('bathroomsError').textContent = 'Devi inserire un numero valido di bagni.';
                document.getElementById('bathroomsError').style.display = 'block';
                document.getElementById('bathrooms').classList.remove('is-valid');
                document.getElementById('bathrooms').classList.add('is-invalid');
            }

            // Controllo per i metri quadri
            if (document.getElementById('mq').value < 30) {
                valid = false;
                document.getElementById('mqError').textContent = 'Devi inserire almeno 30 metri quadri.';
                document.getElementById('mqError').style.display = 'block';
                document.getElementById('mq').classList.remove('is-valid');
                document.getElementById('mq').classList.add('is-invalid');
            } else if (document.getElementById('mq').value <= 0) {
                valid = false;
                document.getElementById('mqError').textContent = 'Devi inserire un numero valido di metri quadri.';
                document.getElementById('mqError').style.display = 'block';
                document.getElementById('mq').classList.remove('is-valid');
                document.getElementById('mq').classList.add('is-invalid');
            }

            // Controllo per l'indirizzo
            if (document.getElementById('address').value.trim() === '') {
                valid = false;
                document.getElementById('addressError').textContent = 'L\'indirizzo è obbligatorio.';
                document.getElementById('addressError').style.display = 'block';
                document.getElementById('address').classList.remove('is-valid');
                document.getElementById('address').classList.add('is-invalid');
            }

            // Controllo per i servizi
            const checkedServices = Array.from(services).some(s => s.checked);
            if (!checkedServices) {
                valid = false;
                document.getElementById('servicesError').textContent = 'Devi selezionare almeno un servizio.';
                document.getElementById('servicesError').style.display = 'block';
            }

            // Add image validation
            if (!validateImages()) {
                valid = false;
            }

            // Other validations for title, rooms, beds, bathrooms, mq, address, and services...

            // Se non è valido, impediamo l'invio del modulo
            if (!valid) {
                event.preventDefault();
            };
    });
</script>
@endsection