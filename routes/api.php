<?php

// ═══════════════════════════════════════════════════════════════
// routes/api.php
// ═══════════════════════════════════════════════════════════════

use App\Http\Controllers\Api\EncryptionKeyController;
use App\Http\Controllers\Api\CarnetEntryController;
use App\Http\Controllers\Api\AudioController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {

    // ── Clé de chiffrement ─────────────────────────────────────
    Route::get('/encryption-salt',    [EncryptionKeyController::class, 'getSalt']);
    Route::post('/encryption-setup',  [EncryptionKeyController::class, 'setup']);

    // ── Entrées du carnet ──────────────────────────────────────
    Route::prefix('carnet')->group(function () {
        Route::get('/entries/{day}',  [CarnetEntryController::class, 'show'])
            ->where('day', '[1-9][0-9]?');
        Route::put('/entries/{day}',  [CarnetEntryController::class, 'update'])
            ->where('day', '[1-9][0-9]?');
    });

    // ── Audio (stream protégé) ─────────────────────────────────
    Route::get('/audio/{slug}',  [AudioController::class, 'stream'])
        ->where('slug', '[a-z0-9\-]+');
});