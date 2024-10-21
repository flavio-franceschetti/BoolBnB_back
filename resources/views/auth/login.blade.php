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
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('Indirizzo E-Mail')
                                }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                                    name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

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
                        </div>

                        <div class="mb-4 row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password')
                                }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password"
                                    class="form-control @error('password') is-invalid @enderror" name="password"
                                    required autocomplete="current-password">

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
                        </div>

                        <div class="mb-4 row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary">{{ __('Login') }}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
            const loginForm = document.getElementById('loginForm');
            const emailField = document.getElementById('email');
            const passwordField = document.getElementById('password');

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

            // function validatePassword() {
            //     const passwordErrorLength = document.getElementById('passwordErrorLength');
            //     const passwordErrorUppercase = document.getElementById('passwordErrorUppercase');
            //     const passwordErrorNumber = document.getElementById('passwordErrorNumber');

            //     passwordErrorLength.style.display = 'none';
            //     passwordErrorUppercase.style.display = 'none';
            //     passwordErrorNumber.style.display = 'none';
            //     passwordField.classList.remove('is-invalid');
            //     passwordField.classList.remove('is-valid');

            //     const passwordValue = passwordField.value.trim();

            //     // Controllo di validità
            //     let isValid = true;
            //     if (passwordValue.length < 8) {
            //         passwordErrorLength.style.display = 'block';
            //         passwordField.classList.add('is-invalid');
            //         isValid = false;
            //     }

            //     if (!/[A-Z]/.test(passwordValue)) {
            //         passwordErrorUppercase.style.display = 'block';
            //         passwordField.classList.add('is-invalid');
            //         isValid = false;
            //     }

            //     if (!/[0-9]/.test(passwordValue)) {
            //         passwordErrorNumber.style.display = 'block';
            //         passwordField.classList.add('is-invalid');
            //         isValid = false;
            //     }

            //     if (isValid) {
            //         passwordField.classList.add('is-valid');
            //     }

            //     return isValid;
            // }
        });
</script>

<style>
    .is-valid {
        border-color: #28a745;
        color: #28a745;
    }

    .is-invalid {
        border-color: #dc3545;
    }
</style>
@endsection