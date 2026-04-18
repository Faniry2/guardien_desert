<?php
// routes/api.php

use App\Http\Controllers\Api\EncryptionKeyController;
use App\Http\Controllers\Api\CarnetEntryController;
use App\Http\Controllers\Api\AudioController;
use Illuminate\Support\Facades\Route;

// Route::middleware('auth:sanctum')->group(function () {

//     // Chiffrement
//     Route::get('/encryption-salt',   [EncryptionKeyController::class, 'getSalt']);
//     Route::post('/encryption-setup', [EncryptionKeyController::class, 'setup']);

//     // Entrées carnet (blobs chiffrés)
//     Route::get('/carnet/entries/{day}', [CarnetEntryController::class, 'show'])->whereNumber('day');
//     Route::put('/carnet/entries/{day}', [CarnetEntryController::class, 'update'])->whereNumber('day');

//     // Audio
//     Route::get('/audio/{slug}', [AudioController::class, 'stream'])->where('slug', '[a-z0-9\-]+');
// });
