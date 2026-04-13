<?php
// routes/web.php — Version propre sans Livewire

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RegisteredUserController;
use App\Http\Controllers\Espace\DashboardController;
use App\Http\Controllers\Espace\CarnetController;
use App\Http\Controllers\Espace\DetenteController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InscriptionController;
// ── Pages publiques ────────────────────────────────────────────
Route::get('/', fn() => view('carnet.index'))->name('home');
Route::get('/renait-sens', fn() => view('renaitsens.index'))->name('renait_sens');
Route::get('/traversees', fn() => view('traversee.index'))->name('traversees');
Route::get('/djanet', fn() => view('djanet'))->name('djanet');

// Route::post('register', [RegisteredUserController::class, 'store'])->name('register');


// ── Auth (Breeze) ──────────────────────────────────────────────
require __DIR__.'/auth.php';

// ── Profil ─────────────────────────────────────────────────────
Route::middleware('auth')->group(function () {
    Route::get('/profile',    [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile',  [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ── Espace membre ──────────────────────────────────────────────
Route::middleware(['auth', 'verified'])
    ->prefix('espace')
    ->group(function () {

        Route::redirect('/', '/espace/dashboard');

        // Dashboard
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('espace.dashboard');

        // Carnet
        Route::get('/carnet',           [CarnetController::class, 'index'])->name('carnet.index');
        Route::get('/carnet/setup',     [CarnetController::class, 'setup'])->name('carnet.setup');
        Route::get('/carnet/bilans',    [CarnetController::class, 'bilans'])->name('carnet.bilans');
        Route::get('/carnet/jour/{day}',[CarnetController::class, 'day'])->whereNumber('day')->name('carnet.day');
        Route::post('/carnet/jour/{day}/complete', [CarnetController::class, 'complete'])->whereNumber('day')->name('carnet.complete');
        Route::get('/carnet/reset',     [CarnetController::class, 'reset'])->name('carnet.reset');

        // Détente
        Route::get('/detente',         [DetenteController::class, 'index'])->name('detente.index');
        Route::get('/detente/musique', [DetenteController::class, 'musique'])->name('detente.musique');
        Route::get('/detente/audio/{slug}', [DetenteController::class, 'stream'])
            ->where('slug', '[a-z0-9\-]+')
            ->name('detente.stream');

        Route::get('/detente/meditations', [DetenteController::class, 'meditations'])->name('detente.meditations');
    });


// ══ INSCRIPTION + PAIEMENT ══
Route::prefix('inscription')->name('inscription.')->group(function () {

    // Formulaire
    Route::get('/',        [InscriptionController::class, 'show'])    ->name('form');

    // Checkout (POST depuis le form)
    Route::post('/checkout', [InscriptionController::class, 'checkout'])->name('checkout');

    // Succès Stripe
    Route::get('/success',   [InscriptionController::class, 'success']) ->name('success');

    // Succès PayPal
    Route::get('/paypal/success', [InscriptionController::class, 'paypalSuccess'])->name('paypal.success');

    // Annulation
    Route::get('/cancel',    [InscriptionController::class, 'cancel'])  ->name('cancel');
});

// Webhook Stripe (hors CSRF)
Route::post('/webhook/stripe', [InscriptionController::class, 'webhook'])
    ->name('stripe.webhook');