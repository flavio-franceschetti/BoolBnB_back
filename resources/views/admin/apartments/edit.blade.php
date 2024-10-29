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
            <input class="form-control" type="file" id="images" name="images[]" multiple accept="image/*">
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


        <!-- Sezione Sponsorizzazione -->
        <div class="container text-center mb-4 p-4 sponsorship-container">
            <h3 class="sponsorship-title"> Potenzia la Visibilità del Tuo Appartamento! <i
                    class="fas fa-key text-success"></i></h3>
            <p class="sponsorship-subtitle">Scegli una sponsorizzazione e affitta più velocemente con la nostra
                piattaforma!</p>

            {{-- Card sponsorship --}}
            <div class="form-group mb-3">
                <label for="sponsorship" class="sponsorship-label">Seleziona una Sponsorizzazione:</label>
                <div class="d-flex flex-wrap justify-content-center">
                    @foreach ($sponsorships as $sponsorship)
                        <div class="card m-2 sponsorship-card" data-id="{{ $sponsorship->id }}"
                            style="cursor: pointer; width: 20rem;">
                            <div class="card-body">
                                <h5 class="card-title">{{ $sponsorship->name }}</h5>
                                <p class="card-text text-success">Prezzo: €{{ $sponsorship->price }}</p>
                                <p class="card-description text-primary">{{ $sponsorship->description }}</p>
                                <div class="card-slogans">
                                    @if (isset($sponsorship->slogans))
                                        @foreach (json_decode($sponsorship->slogans) as $slogan)
                                            <p class="card-slogan">{{ $slogan }}</p>
                                        @endforeach
                                    @else
                                        <p class="card-slogan">Nessuno slogan disponibile.</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Sezione di Guida e Invito alla Sponsorizzazione -->
                <div class="text-center mt-4">
                    <h5 class="learn-more-title">Seleziona una sponsorizzazione e procedi al pagamento <i
                            class="fas fa-star pulsate-star"></i> <br> oppure visita
                        la
                        nostra pagina dedicata per saperne di più!</h5>

                    <!-- Pulsante animato per saperne di più -->
                    <div class="mt-2">
                        <a href="{{ route('admin.sponsorships.index') }}" class="btn btn-primary animated-key-btn">
                            <i class="fas fa-key"></i> Vai alla Sponsorizzazione!
                        </a>
                    </div>
                </div>

                <!-- Pulsante di pagamento visibile alla selezione di una card -->
                <div class="mt-4">
                    <a href="#" class="btn btn-success payment-btn" id="paymentLink" style="display: none;">
                        <i class="fas fa-star pulsate-star"></i> Sponsorizza ora l'Appartamento!
                    </a>
                </div>

                <!-- Pulsanti per modifiche -->
                <div class="d-flex justify-content-between mt-4">
                    <input type="submit" class="btn btn-primary" value="Modifica">
                    <input type="reset" class="btn btn-danger" value="Annulla">
                </div>
            </div>


            <style>
                .is-valid {
                    border-color: green;
                }

                .is-invalid {
                    border-color: red;
                }

                /* Stile generale */
                .sponsorship-container {
                    background-color: #f9f9f9;
                    /* Lighter background */
                    border-radius: 10px;
                    padding: 20px;
                    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
                }

                .sponsorship-title,
                .sponsorship-subtitle {
                    color: #333;
                }

                /* Card stile */
                .sponsorship-card {
                    background-color: #ffffff;
                    color: #333;
                    border: 2px solid transparent;
                    border-radius: 10px;
                    padding: 20px;
                    margin: 15px;
                    width: 20rem;
                    transition: transform 0.2s, box-shadow 0.2s, border-color 0.2s;
                }

                .sponsorship-card:hover {
                    transform: translateY(-5px);
                    box-shadow: 0 4px 20px rgba(40, 167, 69, 0.2);
                }

                .selected {
                    border: 2px solid #28a745;
                    background-color: #e9f7ef;
                    box-shadow: 0 4px 20px rgba(40, 167, 69, 0.3);
                }

                /* sezione btn vai alla sponsorizzazione */
                /* Guida alla Sponsorizzazione */
                .learn-more-title {
                    color: #28a745;
                    font-size: 1.5em;
                    margin-bottom: 10px;
                    text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.2);
                    font-weight: bold;
                }

                /* Pulsante animato */
                .animated-key-btn {
                    background-color: #28a745;
                    color: #ffffff;
                    font-weight: bold;
                    font-size: 1.2rem;
                    padding: 10px 20px;
                    border-radius: 8px;
                    position: relative;
                    overflow: hidden;
                    transition: background-color 0.3s;
                }

                .animated-key-btn:hover {
                    background-color: #218838;
                }

                /* Animazione icona chiave */
                .animated-key-btn i {
                    animation: keyWave 1.5s ease-in-out infinite;
                    margin-right: 8px;
                }

                @keyframes keyWave {

                    0%,
                    100% {
                        transform: translateY(0);
                    }

                    50% {
                        transform: translateY(-5px);
                    }
                }

                /* pagamento */
                /* Stile per il pulsante */
                .payment-btn {
                    display: none;
                    background-color: #28a745;
                    color: white;
                    font-size: 1.1rem;
                    padding: 10px 20px;
                    border-radius: 8px;
                    transition: background-color 0.3s, transform 0.2s;
                    font-weight: bold;
                }

                /* Stile per l'icona stellina */
                .payment-btn i {
                    margin-right: 8px;
                    color: #ffe135;
                }

                /* Pulsazione animata per l'icona stellina */
                .pulsate-star {
                    animation: pulsate 1.2s ease-in-out infinite;
                }

                @keyframes pulsate {

                    0%,
                    100% {
                        transform: scale(1);
                    }

                    50% {
                        transform: scale(1.3);
                    }
                }

                /* Effetto hover per il pulsante */
                .payment-btn:hover {
                    background-color: #218838;
                    transform: translateY(-2px);
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


                    // SPONSORSHIPS

                    const apartmentId = "{{ $apartment->id }}";
                    const paymentLink = document.getElementById('paymentLink');

                    // Event click
                    document.querySelectorAll('.sponsorship-card').forEach(card => {
                        card.addEventListener('click', function() {
                            const isSelected = this.classList.contains('selected');

                            // Rimuovi la selezione da tutte le card
                            document.querySelectorAll('.sponsorship-card').forEach(c => {
                                c.classList.remove('selected');
                            });

                            // Se la card cliccata non era già selezionata, selezionala
                            if (!isSelected) {
                                this.classList.add('selected');

                                // A seconda della card, attribuisci data E ID
                                const sponsorshipId = this.getAttribute('data-id');

                                if (sponsorshipId) {
                                    // Update del pagamento in base alla card di sponsorizzazione
                                    paymentLink.href =
                                        "{{ route('admin.apartments.payment', ['apartmentId' => ':apartmentId', 'sponsorshipId' => ':sponsorshipId']) }}"
                                        .replace(':apartmentId', apartmentId)
                                        .replace(':sponsorshipId', sponsorshipId);
                                    paymentLink.style.display = 'inline';
                                } else {
                                    paymentLink.style.display = 'none';
                                }
                            } else {
                                // Se la card è già selezionata, nascondi il link di pagamento
                                paymentLink.style.display = 'none';
                            }
                        });
                    });
                });
            </script>
        @endsection
