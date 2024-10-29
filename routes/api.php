<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\MatchController;
use App\Http\Controllers\RegisterController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Game;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout']);
Route::post('/register', [RegisterController::class, 'register']);
//Route::post('/match', [MatchController::class, 'match']);
Route::middleware('auth:sanctum')->post('/matchmaking', [MatchController::class, 'match']);

/* === GAMES === */
Route::post('/game/searchGames', [\App\Http\Controllers\GameController::class, 'searchGame'])->name("searchGames");

/* === EVENTS === */
Route::get('/event/allEvents', [\App\Http\Controllers\EventController::class, 'getAllEvents'])->name("getAllEvents");


