<?php

use App\Http\Controllers\RegisteredUserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/odyssee_du_desert', function () {
    return  view('odyssee_du_desert');
})->name('odyssee_du_desert');

Route::get('/rejoint-la-travserser', function () {
    return view('auth.login'); 
})->name('traverser');;

Route::get('/traversees', function () {
    return view('traversees');
})->name('traversees');


Route::post('register', [RegisteredUserController::class, 'store'])->name('register');