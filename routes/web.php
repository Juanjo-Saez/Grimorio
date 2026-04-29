<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
| 
| Rutas para servir las vistas (templates Blade)
|
*/

// Página de inicio
Route::get('/', function () {
    return view('home');
})->name('home');

// Auth
Route::get('/login', function () {
    return view('login');
})->name('login');

Route::get('/register', function () {
    return view('register');
})->name('register');

// Notas (protegidas con autenticación de cliente)
Route::get('/notes', function () {
    return view('notes.index');
});

Route::get('/notes/create', function () {
    return view('notes.create');
});

Route::get('/notes/{id}', function ($id) {
    return view('notes.show');
});

Route::get('/notes/{id}/edit', function ($id) {
    return view('notes.edit');
});