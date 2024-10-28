@extends('layouts.app')

@section('content')
    <h1>Inserisci i dati per il nuovo appartamento</h1>

    @if ($errors->any())
        <div class="alert alert-danger" role="alert">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.apartments.store') }}" method="POST" enctype="multipart/form-data" id="apartmentForm">
        @csrf

        <div class="mb-3">
            <label for="title" class="form-label">Titolo annuncio</label>
            <input type="text" class="form-control" id="title" name="title" value="{{ old('title') }}" required>
            <small class="text-danger" id="titleError" style="display: none;"></small>
        </div>

        <div class="d-flex gap-3 mb-3">
            <div>
                <label for="rooms" class="form-label">N. Camere</label>
                <input type="number" class="form-control" id="rooms" name="rooms" value="{{ old('rooms') }}"
                    required>
                <small class="text-danger" id="roomsError" style="display: none;"></small>
            </div>
            <div>
                <label for="beds" class="form-label">N. Letti</label>
                <input type="number" class="form-control" id="beds" name="beds" value="{{ old('beds') }}"
                    required>
                <small class="text-danger" id="bedsError" style="display: none;"></small>
            </div>
            <div>
                <label for="bathrooms" class="form-label">N. Bagni</label>
                <input type="number" class="form-control" id="bathrooms" name="bathrooms" value="{{ old('bathrooms') }}"
                    required>
                <small class="text-danger" id="bathroomsError" style="display: none;"></small>
            </div>
            <div>
                <label for="mq" class="form-label">Metri Quadri</label>
                <input type="number" class="form-control" id="mq" name="mq" value="{{ old('mq') }}"
                    required>
                <small class="text-danger" id="mqError" style="display: none;"></small>
            </div>
        </div>

        {{-- searchbox --}}
        <div id="search-box-container" class="mb-3"></div>

        <div class="btn-group mb-3" role="group" aria-label="Basic checkbox toggle button group">
            @foreach ($services as $service)
                <input name="services[]" type="checkbox" value="{{ $service->id }}" class="btn-check"
                    id="service-{{ $service->id }}" autocomplete="off" @if (in_array($service->id, old('services', []))) checked @endif>
                <label class="btn btn-outline-primary" for="service-{{ $service->id }}">{{ $service->name }}</label>
            @endforeach
            <small class="text-danger" id="servicesError" style="display: none;"></small>
        </div>

        <div class="mb-3">
            <label for="images" class="form-label">Inserisci l'immagine (Puoi caricare solo file JPG, JPEG, PNG,
                WEBP)</label>
            <input class="form-control" type="file" id="images" name="images[]" multiple required accept="image/*">
            @error('images')
                <small class="text-danger">{{ $message }}</small>
            @enderror
            <small class="text-primary" id="imagesCount" style="display: block;">Inserisci da 1 a 3 file massimi.</small>
            <small class="text-danger" id="imagesError" style="display: none;"></small>
        </div>

        <div class="is-visible-radios mb-3">
            <div>Visibile</div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="is_visible" value="1" id="flexRadioDefault1"
                    checked>
                <label class="form-check-label" for="flexRadioDefault1">Si</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="is_visible" value="0" id="flexRadioDefault2">
                <label class="form-check-label" for="flexRadioDefault2">No</label>
            </div>
        </div>

        <div class="mb-3">
            <input type="submit" class="btn btn-primary" value="Invia">
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
        const apiKey = "{{ config('app.tomtomapikey') }}";
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

        searchInput.value = '{{ old('address') }}';

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

                let valid = true;
                for (let i = 0; i < files.length; i++) {
                    if (files[i].size > 2 * 1024 * 1024) {
                        showError('images',
                            'Il file che stai cercando di caricare è superiore a 2 MB e non è accettato.'
                        );
                        valid = false;
                        break;
                    }

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
            form.addEventListener('submit', function(event) {
                const isValid = [...form.elements].every(input => {
                    if (input.hasAttribute('required') && !input.value) {
                        showError(input.id,
                            `${input.previousElementSibling.innerText} è obbligatorio.`);
                        return false;
                    }
                    return true;
                });

                // Controllo se ci sono errori sui file
                if (fileCount > maxFiles) {
                    event.preventDefault();
                }

                if (!isValid) {
                    event.preventDefault();
                }
            });
        });
    </script>
@endsection
