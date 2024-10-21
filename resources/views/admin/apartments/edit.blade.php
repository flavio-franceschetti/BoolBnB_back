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
        {{-- <div class="mb-3">
            <label for="address" class="form-label">Indirizzo</label>
            <input type="text" class="form-control @error('address') is-invalid @enderror" id="address" name="address"
                value="{{ old('address', $apartment->address) }}" required>
            @error('address')
            <small class="text-danger">{{ $message }}</small>
            @enderror
        </div> --}}

        <div id="search-box-container" class="mb-3"></div>


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
            value="{{ old('city', $apartment->city) }}" required>
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


<script>
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
      //aggiungo la classe form-control all input così prende il css di bootstrap
    //   searchInput.classList.add("form-control");
      // Aggiungo la per la validazione Laravel
        @if ($errors->has('address'))
            searchInput.classList.add("is-invalid");
        @endif
        // imposto l'old sul valore dell'input
      searchInput.value = '{{ old('address', $apartment->address) }}';
</script>
@endsection