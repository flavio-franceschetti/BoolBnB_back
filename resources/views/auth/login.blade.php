@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Login') }}</div>

                    <div class="card-body">
                        <form id="loginForm" method="POST" action="{{ route('login') }}">
                            @csrf

                            <div class="mb-4 row">
                                <label for="email"
                                    class="col-md-4 col-form-label text-md-right">{{ __('Indirizzo E-Mail') }}</label>

                                <div class="col-md-6">
                                    <input id="email" type="email"
                                        class="form-control @error('email') is-invalid @enderror" name="email"
                                        value="{{ old('email') }}" required autocomplete="email" autofocus>

                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>Le credenziali sono errate</strong>
                                        </span>
                                    @enderror
                                    <!-- Aggiungi un div per mostrare gli errori lato client -->
                                    <div id="emailError" class="text-danger"></div>
                                </div>
                            </div>

                            <div class="mb-4 row">
                                <label for="password"
                                    class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                                <div class="col-md-6">
                                    <input id="password" type="password"
                                        class="form-control @error('password') is-invalid @enderror" name="password"
                                        required autocomplete="current-password">

                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>Le credenziali sono errate</strong>
                                        </span>
                                    @enderror
                                    <!-- Aggiungi un div per mostrare gli errori lato client -->
                                    <div id="passwordError" class="text-danger"></div>
                                </div>
                            </div>

                            <div class="mb-4 row mb-0">
                                <div class="col-md-8 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('Login') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Funzione per validare l'email
        function validateEmail(email) {
            const re = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
            return re.test(email);
        }

        // Funzione per validare il form
        document.getElementById('loginForm').addEventListener('submit', function(event) {
            let isValid = true;

            // Ottieni i valori dei campi
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;

            // Pulire eventuali messaggi di errore precedenti
            document.getElementById('emailError').textContent = '';
            document.getElementById('passwordError').textContent = '';

            // Validazione dell'email
            if (!validateEmail(email)) {
                document.getElementById('emailError').textContent = 'Inserisci un indirizzo email valido.';
                isValid = false;
            }

            // Validazione della password (ad esempio minimo 6 caratteri)
            if (password.length < 6) {
                document.getElementById('passwordError').textContent =
                    'La password deve essere di almeno 6 caratteri.';
                isValid = false;
            }

            // Se il form non è valido, prevenire l'invio
            if (!isValid) {
                event.preventDefault();
            }
        });
    </script>
@endsection
