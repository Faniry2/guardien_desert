<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Livewire\Dashboard;
use App\Livewire\Carnet\DayEntry;
use App\Livewire\Carnet\Index as CarnetIndex;
use App\Livewire\Carnet\Bilans;
use App\Livewire\Carnet\Setup as CarnetSetup;
use App\Livewire\Detente\Index as DetenteIndex;
use App\Livewire\Detente\Musique;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ── Authentification (Laravel Breeze) ──────────────────────────
require __DIR__.'/auth.php';

// ── Espace membre (auth + email vérifié) ───────────────────────
Route::middleware(['auth', 'verified'])
    ->prefix('espace')
    ->name('espace.')
    ->group(function () {

        Route::redirect('/', '/espace/dashboard');

        Route::get('/dashboard', Dashboard::class)
            ->name('dashboard');

        // Carnet
        Route::prefix('carnet')->name('carnet.')->group(function () {
            Route::get('/',            CarnetIndex::class)->name('index');
            Route::get('/setup',       CarnetSetup::class)->name('setup');
            Route::get('/bilans',      Bilans::class)->name('bilans');
            Route::get('/jour/{day}',  DayEntry::class)
                ->where('day', '[1-9][0-9]?')
                ->name('day');
            Route::get('/reset',  [\App\Http\Controllers\CarnetController::class, 'reset'])
                ->name('reset');
        });

        // Détente
        Route::prefix('detente')->name('detente.')->group(function () {
            Route::get('/',         DetenteIndex::class)->name('index');
            Route::get('/musique',  Musique::class)->name('musique');
        });

        // Profil (Breeze)
        Route::get('/profil', [\App\Http\Controllers\ProfileController::class, 'edit'])
            ->name('profile.edit');
        Route::patch('/profil', [\App\Http\Controllers\ProfileController::class, 'update'])
            ->name('profile.update');
        Route::delete('/profil', [\App\Http\Controllers\ProfileController::class, 'destroy'])
            ->name('profile.destroy');
    });

// Redirect root → dashboard (si connecté) ou login
Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('espace.dashboard')
        : redirect()->route('login');
});


require __DIR__.'/auth.php';
