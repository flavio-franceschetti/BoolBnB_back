<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ApartmentController;
use App\Http\Controllers\Admin\SponsorshipController;
use App\Http\Controllers\Guest\PageController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|---------------------------------------------------------------------------
| Web Routes
|---------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Rotta per la homepage
Route::get('/', [PageController::class, 'index'])->name('home');

// Rotte protette da autenticazione
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Rotte per l'amministrazione
Route::middleware(['auth', 'verified'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('home');

    // Rotte per la gestione degli appartamenti
    Route::resource('/apartments', ApartmentController::class);

    // // Rotta per visualizzare la pagina di acquisto delle sponsorizzazioni
    // Route::get('/sponsorships/purchase/{id}', [SponsorshipController::class, 'purchase'])->name('sponsorships.purchase');

    // // Rotta per elaborare il pagamento delle sponsorizzazioni
    // Route::post('/sponsorships/process', [SponsorshipController::class, 'processPayment'])->name('sponsorships.process');
});

// Includi le rotte di autenticazione
require __DIR__ . '/auth.php';
