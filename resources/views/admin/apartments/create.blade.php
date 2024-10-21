@extends('layouts.app')

@section('content')
    <h1>Inserisci i dati per il nuovo appartamento</h1>

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

        {{-- <div class="mb-3">
            <label for="address" class="form-label">Indirizzo</label>
            <input type="text" class="form-control" id="address" name="address" value="{{ old('address') }}" required>
            <small class="text-danger" id="addressError" style="display: none;"></small>
        </div> --}}
        
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
            <label for="img" class="form-label">Immagine</label>
            <input class="form-control" type="file" id="img" name="img[]" multiple required>
            @error('img')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>
        <div class="is-visible-radios mb-3">
            <div>Visibile</div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="is_visible" value="1" id="flexRadioDefault1"
                    checked>
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


            <div class="mb-3">
                <input type="submit" class="btn btn-primary" value="Invia">
                <input type="reset" class="btn btn-danger" value="Annulla">
            </div>
    </form>

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
    // recuper la chiave dell'api inserita in config
    const apiKey = "{{ config('app.tomtomApiKey') }}";
     // queste sono le impostazioni della searchBox di tomtom
     let options = {
        searchOptions: {
          key: apiKey, // l' API key
          language: "it-IT", // linguaggio della ricerca
          limit: 10, // numero di risultati visualizzati nel dropdown dei risultati
          countrySet: ["IT"], // Limita la ricerca all'Italia
          //   entityType: "Address", // Mostra solo gli indirizzi
        },
        autocompleteOptions: {
          key: apiKey,
          language: "it-IT",
          countrySet: ["IT"], // Limita i suggerimenti all'Italia
          //   entityType: "Address", // Suggerisce solo indirizzi nel dropdown
        },
        labels: {
          suggestions: {
            brand: "Brand Suggerito",
            category: "Categoria Suggerita",
          },
          placeholder: "Inserisci l'indirizzo", // placeholder della barra di ricerca
          noResultsMessage: "Nessun risultato trovato", // messaggio quando si inserisce un valore che non viene trovato
        },
      };

      // Creo la serachbox con il costruttore di tomtom tt.plugins.SearchBox
      // tt.services è il servizio di ricerca che tomtom usa per gestire le richieste
      // option è la variabile creata con tutte le opzioni dentro
      let ttSearchBox = new tt.plugins.SearchBox(tt.services, options);

      // Con getSearchBoxHTML mi restituisce il blocco html dove è inserita la searchbox già creata
      let searchBoxHTML = ttSearchBox.getSearchBoxHTML();
      // inserisco la searchbox nel mio div html tramite l append
      document.getElementById("search-box-container").append(searchBoxHTML);

      // Seleziono l'input della search box e gli assegno un 'name'
      let searchInput = document.querySelector("#search-box-container input");
      searchInput.setAttribute("name", "address"); // aggiungo l'attributo name
      searchInput.setAttribute("id", "address");// aggiungo l'attributo id
      searchInput.setAttribute("required", true);// aggiungo l'attributo required
      // gestione errori laravel
        @if ($errors->has('address'))
            searchInput.classList.add("is-invalid");
        @endif  
        // imposto l'old sul valore dell'input
      searchInput.value = '{{old('address')}}';
    
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

            const services = document.querySelectorAll('input[name="services[]"]');
            services.forEach(service => {
                service.addEventListener('change', function() {
                    const checkedServices = Array.from(services).some(s => s.checked);
                    if (!checkedServices) {
                        showError('services', 'Devi selezionare almeno un servizio.');
                    } else {
                        hideError('services');
                    }
                });
            });

            document.getElementById('img').addEventListener('change', function() {
                if (this.files.length === 0) {
                    showError('img', 'Devi caricare almeno un\'immagine.');
                } else {
                    hideError('img');
                }
            });

            form.addEventListener('submit', function(event) {
                let hasErrors = false;

                // Controllo del titolo
                if (document.getElementById('title').value.trim() === '') {
                    showError('title', 'Il titolo è obbligatorio.');
                    hasErrors = true;
                }

                // Controllo del numero di camere
                if (document.getElementById('rooms').value <= 0) {
                    showError('rooms', 'Devi inserire un numero valido di camere.');
                    hasErrors = true;
                }

                // Controllo del numero di letti
                if (document.getElementById('beds').value <= 0) {
                    showError('beds', 'Devi inserire un numero valido di letti.');
                    hasErrors = true;
                }

                // Controllo del numero di bagni
                if (document.getElementById('bathrooms').value <= 0) {
                    showError('bathrooms', 'Devi inserire un numero valido di bagni.');
                    hasErrors = true;
                }

                // Controllo dei metri quadri
                if (document.getElementById('mq').value < 30) {
                    showError('mq', 'Devi inserire almeno 30 m²');
                    hasErrors = true;
                } else if (document.getElementById('mq').value <= 0) {
                    showError('mq', 'Devi inserire un numero valido di m²');
                    hasErrors = true;
                }

                // Controllo dell'indirizzo
                if (document.getElementById('address').value.trim() === '') {
                    showError('address', 'L\'indirizzo è obbligatorio.');
                    hasErrors = true;
                }

                // Controllo dei servizi
                const checkedServices = Array.from(services).some(s => s.checked);
                if (!checkedServices) {
                    showError('services', 'Devi selezionare almeno un servizio.');
                    hasErrors = true;
                }

                // Controllo dell'immagine
                if (document.getElementById('img').files.length === 0) {
                    showError('img', 'Devi caricare almeno un\'immagine.');
                    hasErrors = true;
                }

                if (hasErrors) {
                    event.preventDefault(); // Impedisce l'invio del modulo
                }
            });
        });
    </script>
@endsection
