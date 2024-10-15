<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['nullable', 'string', 'max:50', 'min:3'],
            'surname' => ['nullable', 'string', 'max:50', 'min:3'],
            'date_of_birth' => ['nullable', 'date', 'before:-18 years'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ], [
            'name.string' => 'Il nome deve essere una stringa.',
            'name.max' => 'Il nome non può superare i :max caratteri.',
            'name.min' => 'Il nome deve avere almeno :min caratteri',
            'surname.string' => 'Il cognome deve essere una stringa.',
            'surname.max' => 'Il cognome non può superare i :max caratteri.',
            'surname.min' => 'Il cognome deve avere almeno :min caratteri',
            'date_of_birth.date' => 'La data di nascita deve essere una data valida.',
            'date_of_birth.before' => 'Devi avere almeno 18 anni.',
            'email.required' => "L'email è obbligatoria.",
            'email.string' => "L'email deve essere una stringa.",
            'email.lowercase' => "L'email deve essere in minuscolo.",
            'email.email' => "Inserisci un indirizzo email valido.",
            'email.max' => "L'email non può superare i :max caratteri.",
            'email.unique' => "L'email è già in uso.",
            'password.required' => 'La password è obbligatoria.',
            'password.confirmed' => 'Le password non coincidono.',
        ]);
        $user = User::create([
            'name' => $request->name,
            'surname' => $request->surname,
            'date_of_birth' => $request->date_of_birth,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(RouteServiceProvider::HOME);
    }
}
