@extends('layouts.app')

@section('content')
    <div class="container login-container px-4 px-md-0">
        <div class="row register-height justify-content-center">
            <div class="login-title col-12 col-md-5  ">Registrati per inserire il tuo appartamento <i
                    class="d-none d-md-inline fa-solid fa-arrow-right"></i><i
                    class="d-inline d-md-none fa-solid fa-arrow-down"></i></div>
            <div class="login-form-container d-flex align-items-center py-5 py-md-0 col-12 col-md-7 ">
                <form class="login-form" method="POST" action="{{ route('register') }}" id="registrationForm">
                    @csrf
                    <div class="form-floating mb-3">
                        {{-- input e label --}}
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                            placeholder="Nome" name="name" value="{{ old('name') }}" autocomplete="name" autofocus>
                        <label for="name">Nome</label>

                        {{-- controlli ed errori --}}
                        <div class="invalid-feedback" id="nameError">
                            Il nome deve contenere almeno 2 caratteri e solo lettere.
                        </div>
                    </div>

                    <div class="form-floating mb-3">
                        {{-- input and label --}}
                        <input type="text" class="form-control @error('surname') is-invalid @enderror" id="surname"
                            placeholder="Cognome" name="surname" autocomplete="surname" value="{{ old('surname') }}"
                            autofocus>
                        <label for="surname">Cognome</label>
                        {{-- errori --}}
                        <div class="invalid-feedback" id="surnameError">
                            Il cognome deve contenere almeno 2 caratteri e solo lettere.
                        </div>
                    </div>

                    <div class="form-floating mb-3">
                        {{-- input and label --}}
                        <input type="date" class="form-control @error('date_of_birth') is-invalid @enderror"
                            id="date_of_birth" name="date_of_birth" autocomplete="date_of_birth"
                            value="{{ old('date_of_birth', Carbon\Carbon::now()->subYears(18)->toDateString()) }}"
                            autocomplete="date_of_birth" autofocus>

                        <label for="date_of_birth">Data di nascita</label>

                        {{-- errori --}}
                        <div class="invalid-feedback" id="dobError">
                            Devi avere almeno 18 anni.
                        </div>
                    </div>

                    <div class="form-floating mb-3">
                        {{-- input and label --}}
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email"
                            placeholder="Email" name="email" autocomplete="email" value="{{ old('email') }}" autofocus
                            required>
                        <label for="email">Indirizzo email</label>

                        {{-- errori --}}
                        <div class="invalid-feedback" id="emailError">Inserisci un indirizzo email valido.</div>
                        <div class="invalid-feedback" id="emailErrorMissingAt" style="display:none;">Manca il
                            simbolo "@".</div>
                        <div class="invalid-feedback" id="emailErrorMissingDomain" style="display:none;">
                            L'indirizzo email deve includere un dominio (es. gmail.com).
                        </div>
                        <div class="invalid-feedback" id="emailErrorInvalidCharacters" style="display:none;">
                            L'indirizzo email contiene caratteri non validi.
                        </div>
                    </div>
                    <div class="form-floating mb-3">
                        {{-- input and label --}}
                        <input type="password" class="form-control @error('password') is-invalid @enderror" id="password"
                            name="password" autocomplete="new-password" placeholder="Password" autofocus required>
                        <label for="password">Password</label>

                        {{-- errori --}}
                        <div class="invalid-feedback" id="passwordError">
                            La password deve contenere almeno 8 caratteri.
                        </div>
                    </div>

                    <div class="form-floating mb-3">
                        {{-- input and label --}}
                        <input type="password" class="form-control" id="floatingPassword" placeholder="Password"
                            name="password_confirmation" autocomplete="new-password" autofocus required>
                        <label for="floatingPassword">Conferma Password *</label>

                        {{-- errori --}}
                        <div class="invalid-feedback" id="passwordConfirmError">
                            Le password non coincidono.
                        </div>
                    </div>


                    <div>
                        <button type="submit" class="btn login-btn">{{ __('Registrati') }}</button>
                    </div>

                </form>
            </div>


        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Aggiungi la validazione in tempo reale ai campi input
            document.getElementById('name').addEventListener('input', validateName);
            document.getElementById('surname').addEventListener('input', validateSurname);
            document.getElementById('date_of_birth').addEventListener('input', validateDOB);
            document.getElementById('email').addEventListener('input', validateEmail);
            document.getElementById('password').addEventListener('input', validatePassword);
            document.getElementById('password-confirm').addEventListener('input', validatePasswordConfirm);
        });

        // validazione campo Name

        function validateName() {
            const name = document.getElementById('name');
            const nameError = document.getElementById('nameError');
            const namePattern = /^[a-zA-Z]{2,}$/;

            if (!namePattern.test(name.value)) {
                name.classList.add('is-invalid');
                name.classList.remove('is-valid');
                nameError.style.display = 'block';
            } else {
                name.classList.remove('is-invalid');
                name.classList.add('is-valid');
                nameError.style.display = 'none';
            }
        }


        // Validazione Surname

        function validateSurname() {
            const surname = document.getElementById('surname');
            const surnameError = document.getElementById('surnameError');
            const surnamePattern = /^[a-zA-Z]{2,}$/;

            if (!surnamePattern.test(surname.value)) {
                surname.classList.add('is-invalid');
                surname.classList.remove('is-valid');
                surnameError.style.display = 'block';
            } else {
                surname.classList.remove('is-invalid');
                surname.classList.add('is-valid');
                surnameError.style.display = 'none';
            }
        }

        // Validazione data di nascita

        function validateDOB() {
            const dateOfBirth = document.getElementById('date_of_birth');
            const dobError = document.getElementById('dobError');
            const userAge = calculateAge(new Date(dateOfBirth.value));

            if (userAge < 18) {
                dateOfBirth.classList.add('is-invalid');
                dateOfBirth.classList.remove('is-valid');
                dobError.style.display = 'block';
            } else {
                dateOfBirth.classList.remove('is-invalid');
                dateOfBirth.classList.add('is-valid');
                dobError.style.display = 'none';
            }
        }

        // Validazione Email

        function validateEmail() {
            const email = document.getElementById('email');
            const emailError = document.getElementById('emailError');
            const emailErrorMissingAt = document.getElementById('emailErrorMissingAt');
            const emailErrorMissingDomain = document.getElementById('emailErrorMissingDomain');
            const emailErrorInvalidCharacters = document.getElementById('emailErrorInvalidCharacters');

            emailError.style.display = 'none';
            emailErrorMissingAt.style.display = 'none';
            emailErrorMissingDomain.style.display = 'none';
            emailErrorInvalidCharacters.style.display = 'none';

            const atSymbol = /@/;
            const domainPattern = /@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
            const invalidChars = /[^a-zA-Z0-9@._-]/;
            let isValid = true;

            if (invalidChars.test(email.value)) {
                emailErrorInvalidCharacters.style.display = 'block';
                email.classList.add('is-invalid');
                email.classList.remove('is-valid');
                isValid = false;
            }

            if (!atSymbol.test(email.value)) {
                emailErrorMissingAt.style.display = 'block';
                email.classList.add('is-invalid');
                email.classList.remove('is-valid');
                isValid = false;
            }

            if (atSymbol.test(email.value) && !domainPattern.test(email.value)) {
                emailErrorMissingDomain.style.display = 'block';
                email.classList.add('is-invalid');
                email.classList.remove('is-valid');
                isValid = false;
            }

            if (isValid) {
                email.classList.remove('is-invalid');
                email.classList.add('is-valid');
            }
        }


        // Validazione Password

        function validatePassword() {
            const password = document.getElementById('password');
            const passwordError = document.getElementById('passwordError');

            const minLength = 8;
            const requiresMixedCase = true;
            const requiresNumbers = true;

            let errorMessage = '';

            if (password.value.length < minLength) {
                errorMessage += 'La password deve avere almeno 8 caratteri. <br>';
            }

            if (requiresMixedCase && !/^(?=.*[a-z])(?=.*[A-Z])/.test(password.value)) {
                errorMessage += 'La password deve contenere almeno una lettera maiuscola e una minuscola. <br>';
            }

            if (requiresNumbers && !/\d/.test(password.value)) {
                errorMessage += 'La password deve contenere almeno un numero.<br> ';
            }

            if (errorMessage) {
                password.classList.add('is-invalid');
                password.classList.remove('is-valid');
                passwordError.innerHTML = errorMessage;
                passwordError.style.display = 'block';
            } else {
                password.classList.remove('is-invalid');
                password.classList.add('is-valid');
                passwordError.style.display = 'none';
            }

            validatePasswordConfirm();
        }

        // Validazione conferma password

        function validatePasswordConfirm() {
            const password = document.getElementById('password');
            const passwordConfirm = document.getElementById('password-confirm');
            const passwordConfirmError = document.getElementById('passwordConfirmError');

            if (password.value !== passwordConfirm.value) {
                passwordConfirm.classList.add('is-invalid');
                passwordConfirm.classList.remove('is-valid');
                passwordConfirmError.style.display = 'block';
            } else {
                passwordConfirm.classList.remove('is-invalid');
                passwordConfirm.classList.add('is-valid');
                passwordConfirmError.style.display = 'none';
            }
        }


        // Funzione per il calcolo dell'eta minorenne o non
        function calculateAge(birthDate) {
            const today = new Date();
            let age = today.getFullYear() - birthDate.getFullYear();
            const monthDifference = today.getMonth() - birthDate.getMonth();

            if (monthDifference < 0 || (monthDifference === 0 && today.getDate() < birthDate.getDate())) {
                age--;
            }
            return age;
        }
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
