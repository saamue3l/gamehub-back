<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\ConversationController;
use App\Http\Controllers\MatchController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\RegisterController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

/* === USERS === */
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::middleware('auth:sanctum')->post('/user/searchUsers', [\App\Http\Controllers\UserController::class, 'searchUsers']);


Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);
Route::post('/register', [RegisterController::class, 'register']);
//Route::post('/match', [MatchController::class, 'match']);
Route::middleware('auth:sanctum')->post('/matchmaking', [MatchController::class, 'match']);

/* === GAMES === */
Route::middleware('auth:sanctum')->post('/game/searchGamesWithoutFavorites', [\App\Http\Controllers\GameController::class, 'searchGamesWithoutFavorites'])->name("searchGamesWithoutFavorites");
Route::middleware('auth:sanctum')->post('/game/searchGames', [\App\Http\Controllers\GameController::class, 'searchGame'])->name("searchGames");

/* === EVENTS === */
Route::middleware('auth:sanctum')->match(['GET', 'POST'], '/event/allEvents', [\App\Http\Controllers\EventController::class, 'getAllEvents'])->name("getAllEventsWFilters");
Route::middleware('auth:sanctum')->post('/event/createEvent', [\App\Http\Controllers\EventController::class, 'createEvent'])->name("createEvent");
Route::middleware('auth:sanctum')->post('/event/changeJoinedStatus/{event}', [\App\Http\Controllers\EventController::class, 'changeJoinedStatus'])->name("changeJoinedStatus");

/* === PROFILE === */
Route::middleware('auth:sanctum')->get('/profile/{username}/favoriteGames', [\App\Http\Controllers\ProfileControllers\ProfileController::class, 'getFavoriteGames'])->name("getFavoriteGames");
Route::middleware('auth:sanctum')->get('/profile/{username}/userInfo', [\App\Http\Controllers\ProfileControllers\ProfileController::class, 'getUserInfo'])->name("getUserInfo");
Route::middleware('auth:sanctum')->get('/profile/{username}/userAlias', [\App\Http\Controllers\ProfileControllers\ProfileController::class, 'getUserAlias'])->name("getUserAlias");
Route::middleware('auth:sanctum')->get('/profile/{username}/userAvailability', [\App\Http\Controllers\ProfileControllers\ProfileController::class, 'getUserAvailability'])->name("getUserAvailability");

/* === FAVORITE GAMES === */
Route::middleware('auth:sanctum')->post('/profile/addFavoriteGame', [\App\Http\Controllers\ProfileControllers\FavoriteGamesController::class, 'addFavoriteGame'])->name("addFavoriteGame");
Route::middleware('auth:sanctum')->put('/profile/updateFavoriteGame', [\App\Http\Controllers\ProfileControllers\FavoriteGamesController::class, 'updateFavoriteGame'])->name("updateFavoriteGame");
Route::middleware('auth:sanctum')->delete('/profile/deleteFavoriteGame', [\App\Http\Controllers\ProfileControllers\FavoriteGamesController::class, 'deleteFavoriteGame'])->name("deleteFavoriteGame");

/* === USERNAME === */
Route::middleware('auth:sanctum')->put('/profile/updateAlias', [\App\Http\Controllers\ProfileControllers\AliasController::class, 'updateAlias'])->name("updateAlias");

/* === AVAILABILITY === */
Route::middleware('auth:sanctum')->put('/profile/updateAvailability', [\App\Http\Controllers\ProfileControllers\AvailabilityController::class, 'updateAvailability'])->name("updateAvailability");

/* === UTILS === */
Route::middleware('auth:sanctum')->get('utils/allSkills', [\App\Http\Controllers\UtilsController::class, 'getAllSkills'])->name("getAllSkills");
Route::middleware('auth:sanctum')->get('utils/allPlatforms', [\App\Http\Controllers\UtilsController::class, 'getAllPlatforms'])->name("getAllPlatforms");

/* === LIVECHAT === */
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/conversations/createConversation', [ConversationController::class, 'createConversation']);
    Route::get('/conversations/getConversations', [ConversationController::class, 'getUserConversations']);
    Route::get('/conversations/{conversationId}/getMessages', [ConversationController::class, 'getConversationMessages']);
    Route::post('/conversations/{conversationId}/sendMessage', [ConversationController::class, 'sendMessage']);
    Route::get('/messages/{userId}', [ChatController::class, 'getMessagesWithUser']);
    Route::get('/userConversations', [ChatController::class, 'getConversationUsers']);
    Route::get('currentUser', [ChatController::class, 'getCurrentUser'] );
    Route::post('/sendMessage', [ChatController::class, 'sendMessage']);
});

/* === NOTIFICATIONS === */
Route::middleware('auth:sanctum')->get('/notifications', [NotificationController::class, 'getNotifications']);
Route::middleware('auth:sanctum')->post('/notifications/{id}/read', [NotificationController::class, 'markAsRead']);
