<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\SharedLinkController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => view('home'))->name('home');

// Guest only
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// Auth required
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Notas
    Route::get('/notes', [NoteController::class, 'index'])->name('notes.index');
    Route::get('/notes/create', [NoteController::class, 'create'])->name('notes.create');
    Route::post('/notes', [NoteController::class, 'store'])->name('notes.store');
    Route::get('/notes/{note}', [NoteController::class, 'show'])->name('notes.show');
    Route::get('/notes/{note}/edit', [NoteController::class, 'edit'])->name('notes.edit');
    Route::put('/notes/{note}', [NoteController::class, 'update'])->name('notes.update');
    Route::delete('/notes/{note}', [NoteController::class, 'destroy'])->name('notes.destroy');

    // AJAX endpoints (en web para usar la sesión)
    Route::get('/api/user/tags', [NoteController::class, 'userTags'])->name('api.user.tags');
    Route::get('/api/notes/by-tag/{tag}', [NoteController::class, 'notesByTag'])->name('api.notes.byTag');

    // Compartir
    Route::post('/notes/{note}/share', [SharedLinkController::class, 'store'])->name('shared.store');
    Route::delete('/shared/{sharedLink}', [SharedLinkController::class, 'destroy'])->name('shared.destroy');
    Route::get('/shared', [SharedLinkController::class, 'sharedWithMe'])->name('shared.index');
    Route::get('/shared/{token}', [SharedLinkController::class, 'viewShared'])->name('shared.show');
    Route::put('/shared/{token}', [SharedLinkController::class, 'updateShared'])->name('shared.update');
});
