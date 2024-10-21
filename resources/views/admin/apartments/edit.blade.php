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

            <div>
                <label for="mq" class="form-label">Metri Quadri</label>
                <input type="number" class="form-control" id="mq" name="mq"
                    value="{{ old('mq', $apartment->mq) }}" required>
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
      searchInput.value = '{{ old('address', $apartment->address) }}';

    //   SEZIONE DEI CONTROLLI
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('apartmentForm');

            // Validazione in tempo reale per il titolo
            document.getElementById('title').addEventListener('input', function() {
                const titleError = document.getElementById('titleError');
                if (this.value.trim() === '') {
                    titleError.textContent = 'Il titolo è obbligatorio.';
                    titleError.style.display = 'block';
                } else {
                    titleError.style.display = 'none';
                }
            });

            // Validazione in tempo reale per il numero di camere
            document.getElementById('rooms').addEventListener('input', function() {
                const roomsError = document.getElementById('roomsError');
                if (this.value <= 0) {
                    roomsError.textContent = 'Devi inserire un numero valido di camere.';
                    roomsError.style.display = 'block';
                } else {
                    roomsError.style.display = 'none';
                }
            });

            // Validazione in tempo reale per il numero di letti
            document.getElementById('beds').addEventListener('input', function() {
                const bedsError = document.getElementById('bedsError');
                if (this.value <= 0) {
                    bedsError.textContent = 'Devi inserire un numero valido di letti.';
                    bedsError.style.display = 'block';
                } else {
                    bedsError.style.display = 'none';
                }
            });

            // Validazione in tempo reale per il numero di bagni
            document.getElementById('bathrooms').addEventListener('input', function() {
                const bathroomsError = document.getElementById('bathroomsError');
                if (this.value <= 0) {
                    bathroomsError.textContent = 'Devi inserire un numero valido di bagni.';
                    bathroomsError.style.display = 'block';
                } else {
                    bathroomsError.style.display = 'none';
                }
            });

            // Validazione in tempo reale per i metri quadri
            document.getElementById('mq').addEventListener('input', function() {
                const mqError = document.getElementById('mqError');
                if (this.value < 30) {
                    mqError.textContent = 'Devi inserire almeno 30 m²';
                    mqError.style.display = 'block';
                } else if (this.value <= 0) {
                    mqError.textContent = 'Devi inserire un numero valido di m²';
                    mqError.style.display = 'block';
                } else {
                    mqError.style.display = 'none';
                }
            });

            // Validazione in tempo reale per l'indirizzo
            document.getElementById('address').addEventListener('input', function() {
                const addressError = document.getElementById('addressError');
                if (this.value.trim() === '') {
                    addressError.textContent = 'L\'indirizzo è obbligatorio.';
                    addressError.style.display = 'block';
                } else {
                    addressError.style.display = 'none';
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

            // Validazione in tempo reale per le immagini
            document.getElementById('img').addEventListener('change', function() {
                const imgError = document.getElementById('imgError');
                if (this.files.length === 0) {
                    imgError.textContent = 'Devi caricare almeno un\'immagine.';
                    imgError.style.display = 'block';
                } else {
                    imgError.style.display = 'none';
                }
            });

            // Validazione finale al submit
            form.addEventListener('submit', function(event) {
                let valid = true;

                // Controllo per il titolo
                if (document.getElementById('title').value.trim() === '') {
                    valid = false;
                    document.getElementById('titleError').textContent = 'Il titolo è obbligatorio.';
                    document.getElementById('titleError').style.display = 'block';
                }

                // Controllo per il numero di camere
                if (document.getElementById('rooms').value <= 0) {
                    valid = false;
                    document.getElementById('roomsError').textContent =
                        'Devi inserire un numero valido di camere.';
                    document.getElementById('roomsError').style.display = 'block';
                }

                // Controllo per il numero di letti
                if (document.getElementById('beds').value <= 0) {
                    valid = false;
                    document.getElementById('bedsError').textContent =
                        'Devi inserire un numero valido di letti.';
                    document.getElementById('bedsError').style.display = 'block';
                }

                // Controllo per il numero di bagni
                if (document.getElementById('bathrooms').value <= 0) {
                    valid = false;
                    document.getElementById('bathroomsError').textContent =
                        'Devi inserire un numero valido di bagni.';
                    document.getElementById('bathroomsError').style.display = 'block';
                }

                // Controllo per i metri quadri
                if (document.getElementById('mq').value < 30) {
                    valid = false;
                    document.getElementById('mqError').textContent =
                        'Devi inserire almeno 30 metri quadri.';
                    document.getElementById('mqError').style.display = 'block';
                } else if (document.getElementById('mq').value <= 0) {
                    valid = false;
                    document.getElementById('mqError').textContent =
                        'Devi inserire un numero valido di metri quadri.';
                    document.getElementById('mqError').style.display = 'block';
                }

                // Controllo per l'indirizzo
                if (document.getElementById('address').value.trim() === '') {
                    valid = false;
                    document.getElementById('addressError').textContent = 'L\'indirizzo è obbligatorio.';
                    document.getElementById('addressError').style.display = 'block';
                }

                // Controllo per i servizi
                const checkedServices = Array.from(services).some(s => s.checked);
                if (!checkedServices) {
                    valid = false;
                    document.getElementById('servicesError').textContent =
                        'Devi selezionare almeno un servizio.';
                    document.getElementById('servicesError').style.display = 'block';
                }

                // Controllo per le immagini
                if (document.getElementById('img').files.length === 0) {
                    valid = false;
                    document.getElementById('imgError').textContent = 'Devi caricare almeno un\'immagine.';
                    document.getElementById('imgError').style.display = 'block';
                }

                // Se non è valido, impediamo l'invio del modulo
                if (!valid) {
                    event.preventDefault();
                }
            });
        });
    </script>
@endsection
