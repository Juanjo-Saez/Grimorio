<?php

use Illuminate\Support\Facades\Route;
use App\Models\User;
use App\Http\Controllers\UserController;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\AuthController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::middleware(['logged'])->group(fn () => authRoutes());

Route::middleware(['auth'])->group(function (){
    Route::post('/logout', [AuthController::class,'logout'])->name('auth.logout');
    Route::post('/notes/{id}/translate', [NoteController::class, 'translate'])->name('notes.translate');
    Route::resource('notes', NoteController::class)->except(['show']);
});

function authRoutes() {
    loginRoutes();
    singupRoutes();
}

function loginRoutes() {
    Route::get('/', function () {
        return view('login');
    })->name('login');

    Route::post('/login', [AuthController::class, 'login'])->name('auth.login');
}

function singupRoutes() {
    Route::get('/signup', function () {
        return view('signup');
    })->name('signup');

    Route::post('/signup', [AuthController::class, 'signup'])->name('auth.signup');
}