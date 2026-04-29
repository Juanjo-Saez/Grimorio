<?php

use Illuminate\Support\Facades\Route;
use App\Features\Note\Controllers\NoteController;
use App\Features\Auth\Controllers\AuthController;
use App\Features\SharedLink\Controllers\SharedLinkController;
use App\Features\SharedLink\Controllers\SharedNoteController;

// Rutas públicas
Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);

// Rutas de nota compartida (sin autenticación)
Route::get('/shared/{token}', [SharedNoteController::class, 'show']);
Route::put('/shared/{token}', [SharedNoteController::class, 'update']);

// Rutas de API v1 (protegidas con JWT)
Route::prefix('v1')->middleware('auth.jwt')->group(function () {
    // Notas — search debe ir ANTES de /notes/{id}
    Route::get('/notes/search', [NoteController::class, 'search']);
    Route::get('/notes', [NoteController::class, 'index']);
    Route::post('/notes', [NoteController::class, 'store']);
    Route::get('/notes/{id}', [NoteController::class, 'show']);
    Route::put('/notes/{id}', [NoteController::class, 'update']);
    Route::delete('/notes/{id}', [NoteController::class, 'destroy']);

    // Auth
    Route::post('/auth/logout', [AuthController::class, 'logout']);

    // Shared Links
    Route::post('/notes/{noteId}/share', [SharedLinkController::class, 'store']);
    Route::get('/notes/{noteId}/shared', [SharedLinkController::class, 'listShared']);
    Route::get('/shared', [SharedLinkController::class, 'listReceived']);
    Route::delete('/shared/{id}', [SharedLinkController::class, 'revoke']);
});

