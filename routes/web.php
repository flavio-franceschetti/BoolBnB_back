<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ApartmentController;
use App\Http\Controllers\Admin\MessageController;
use App\Http\Controllers\Admin\SponsorshipController;
use App\Http\Controllers\Guest\PageController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

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

    // Rotte per la gestione degli appartamenti
    Route::resource('/messages', MessageController::class);

    // Rotta reindirizzamento dettaglio appartamento dopo pagamento
    Route::get('/apartments/{apartment}', [ApartmentController::class, 'show'])->name('apartments.show');

    // Rotta per mostrare il modulo di pagamento
    Route::get('/apartments/payment/{apartmentId}/{sponsorshipId}', [PaymentController::class, 'showPaymentForm'])->name('apartments.payment');

    // Rotta per processare il pagamento
    Route::post('/payment/checkout', [PaymentController::class, 'processPayment'])->name('payment.checkout');


    // Rotta per generare il token (se necessario)
    Route::get('/apartments/generateToken', [PaymentController::class, 'generateToken'])->name('apartments.generateToken');
});

// Includi le rotte di autenticazione
require __DIR__ . '/auth.php';
