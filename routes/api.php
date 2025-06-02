<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\LinkController;
use App\Http\Controllers\API\AuthController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
/*
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
*/
Route::get('link/{note_id}', [LinkController::class, 'getLink']);
Route::get('shared/{note_id}', [LinkController::class, 'getLink']);

Route::post('login', [AuthController::class, 'login'])->middleware('web');
Route::post('logout', [AuthController::class, 'logout']);
Route::post('signup', [AuthController::class, 'signup']);
Route::apiResource('links', LinkController::class)->middleware('auth.jwt');

