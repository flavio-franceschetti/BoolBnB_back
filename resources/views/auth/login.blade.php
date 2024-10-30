@extends('layouts.app')

@section('content')
    <div class="container login-container px-4 px-md-0">
        <div class="row login-height justify-content-center">
            <div class="login-title col-12 col-md-5">
                <h1>ACCEDI AL TUO ACCOUNT</h1>
                <span>
                    <i class="d-none d-md-inline fa-solid fa-arrow-right"></i><i
                        class="d-inline d-md-none fa-solid fa-arrow-down"></i>
                </span>
            </div>
            <div class="login-form-container d-flex align-items-center py-5 py-md-0 col-12 col-md-7 ">
                <form id="loginForm" class='login-form' method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="form-floating mb-3">
                        {{-- input e label --}}
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="floatingInput"
                            placeholder="nome@example.com" name="email" value="{{ old('email') }}" required
                            autocomplete="email" autofocus>
                        <label for="floatingInput">Email</label>

                        {{-- controlli ed errori --}}
                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror

                        <div class="invalid-feedback" id="emailError">Inserisci un indirizzo email valido.</div>
                        <div class="invalid-feedback" id="emailErrorMissingAt" style="display:none;">Manca il
                            simbolo "@".</div>
                        <div class="invalid-feedback" id="emailErrorMissingDomain" style="display:none;">
                            L'indirizzo email deve includere un dominio (es. gmail.com).</div>
                        <div class="invalid-feedback" id="emailErrorInvalidCharacters" style="display:none;">
                            L'indirizzo email contiene caratteri non validi.</div>
                    </div>

                    <div class="form-floating mb-3">
                        {{-- input and label --}}
                        <input type="password" class="form-control @error('password') is-invalid @enderror"
                            id="floatingPassword" placeholder="Password" name="password" required
                            autocomplete="current-password">
                        <label for="floatingPassword">Password</label>
                        {{-- errori --}}
                        @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror

                        <div class="invalid-feedback" id="passwordErrorLength" style="display:none;">
                            La password deve contenere almeno 8 caratteri.</div>
                        <div class="invalid-feedback" id="passwordErrorUppercase" style="display:none;">
                            La password deve contenere almeno una lettera maiuscola.</div>
                        <div class="invalid-feedback" id="passwordErrorNumber" style="display:none;">
                            La password deve contenere almeno un numero.</div>
                    </div>


                    <div>
                        <button type="submit" class="btn login-btn">{{ __('Accedi') }}</button>
                    </div>

                </form>
            </div>


        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const loginForm = document.getElementById('loginForm');
            const emailField = document.getElementById('floatingInput');
            const passwordField = document.getElementById('floatingPassword');

            // Aggiungi la validazione in tempo reale
            emailField.addEventListener('input', validateEmail);
            passwordField.addEventListener('input', validatePassword);

            loginForm.addEventListener('submit', function(e) {
                let hasError = false;

                // Validazione finale prima di inviare il form
                hasError = !validateEmail() || !validatePassword();

                // Se ci sono errori, blocca l'invio del form
                if (hasError) {
                    e.preventDefault();
                }
            });

            function validateEmail() {
                const emailErrorMissingAt = document.getElementById('emailErrorMissingAt');
                const emailErrorMissingDomain = document.getElementById('emailErrorMissingDomain');
                const emailErrorInvalidCharacters = document.getElementById('emailErrorInvalidCharacters');

                const emailValue = emailField.value.trim();
                const atSymbol = /@/;
                const domainPattern = /@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
                const invalidChars = /[^a-zA-Z0-9@._-]/;

                // Nascondi tutti gli errori prima della validazione
                emailErrorMissingAt.style.display = 'none';
                emailErrorMissingDomain.style.display = 'none';
                emailErrorInvalidCharacters.style.display = 'none';
                emailField.classList.remove('is-invalid');

                // Controllo di validità
                if (invalidChars.test(emailValue)) {
                    emailErrorInvalidCharacters.style.display = 'block';
                    emailField.classList.add('is-invalid');
                    return false;
                } else if (!atSymbol.test(emailValue)) {
                    emailErrorMissingAt.style.display = 'block';
                    emailField.classList.add('is-invalid');
                    return false;
                } else if (atSymbol.test(emailValue) && !domainPattern.test(emailValue)) {
                    emailErrorMissingDomain.style.display = 'block';
                    emailField.classList.add('is-invalid');
                    return false;
                }

                emailField.classList.add('is-valid');
                return true;
            }

            function validatePassword() {
                const passwordErrorLength = document.getElementById('passwordErrorLength');
                const passwordErrorUppercase = document.getElementById('passwordErrorUppercase');
                const passwordErrorNumber = document.getElementById('passwordErrorNumber');

                passwordErrorLength.style.display = 'none';
                passwordErrorUppercase.style.display = 'none';
                passwordErrorNumber.style.display = 'none';
                passwordField.classList.remove('is-invalid');
                passwordField.classList.remove('is-valid');

                const passwordValue = passwordField.value.trim();

                // Controllo di validità
                let isValid = true;
                if (passwordValue.length < 8) {
                    passwordErrorLength.style.display = 'block';
                    passwordField.classList.add('is-invalid');
                    isValid = false;
                }

                if (!/[A-Z]/.test(passwordValue)) {
                    passwordErrorUppercase.style.display = 'block';
                    passwordField.classList.add('is-invalid');
                    isValid = false;
                }

                if (!/[0-9]/.test(passwordValue)) {
                    passwordErrorNumber.style.display = 'block';
                    passwordField.classList.add('is-invalid');
                    isValid = false;
                }

                if (isValid) {
                    passwordField.classList.add('is-valid');
                }

                return isValid;
            }
        });
    </script>

    <style>
        .is-valid {
            border-color: #28a745;
            background-color: #d4edda;
            color: #28a745;
        }

        .is-invalid {
            border-color: #dc3545;
            background-color: #f8d7da;
        }
    </style>
@endsection
