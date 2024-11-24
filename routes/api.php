<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\MatchController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

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
Route::middleware('auth:sanctum')->get( '/event/getUserSubscribedEvents', [\App\Http\Controllers\EventController::class, 'getUserSubscribedEvents'])->name("getUserSubscribedEvents");
Route::middleware('auth:sanctum')->post('/event/createEvent', [\App\Http\Controllers\EventController::class, 'createEvent'])->name("createEvent");
Route::middleware('auth:sanctum')->post('/event/changeJoinedStatus/{event}', [\App\Http\Controllers\EventController::class, 'changeJoinedStatus'])->name("changeJoinedStatus");

/* === FORUMS === */
Route::middleware('auth:sanctum')->get('/forums/allForums', [\App\Http\Controllers\ForumController::class, 'getAllForums'])->name("getAllForums");
Route::middleware('auth:sanctum')->post('/forums/createForum', [\App\Http\Controllers\ForumController::class, 'createForum'])->name("createForum");
Route::middleware('auth:sanctum')->post('/forums/editForum/{forumId}', [\App\Http\Controllers\ForumController::class, 'editForum'])->name("editForum");
Route::middleware('auth:sanctum')->post('/forums/removeForum/{forumId}', [\App\Http\Controllers\ForumController::class, 'removeForum'])->name("removeForum");
Route::middleware('auth:sanctum')->get('/forums/getForum/{forumId}', [\App\Http\Controllers\ForumController::class, 'getForum'])->name("getForum");
Route::middleware('auth:sanctum')->get('/forums/getTopic/{topicId}', [\App\Http\Controllers\ForumController::class, 'getTopic'])->name("getTopic");
Route::middleware('auth:sanctum')->post('/forums/createTopic', [\App\Http\Controllers\ForumController::class, 'createTopic'])->name("createTopic");
Route::middleware('auth:sanctum')->post('/forums/editTopic/{topicId}', [\App\Http\Controllers\ForumController::class, 'editTopic'])->name("editTopic");
Route::middleware('auth:sanctum')->post('/forums/removeTopic/{topicId}', [\App\Http\Controllers\ForumController::class, 'removeTopic'])->name("removeTopic");
Route::middleware('auth:sanctum')->post('/forums/createPost/{topicId}', [\App\Http\Controllers\ForumController::class, 'createPost'])->name("createPost");
Route::middleware('auth:sanctum')->post('/forums/editPost/{postId}', [\App\Http\Controllers\ForumController::class, 'editPost'])->name("editPost");
Route::middleware('auth:sanctum')->post('/forums/removePost/{postId}', [\App\Http\Controllers\ForumController::class, 'removePost'])->name("removePost");
Route::middleware('auth:sanctum')->post('/forums/search', [\App\Http\Controllers\ForumController::class, 'searchInForums'])->name("searchInForums");
/* Reaction */
Route::middleware('auth:sanctum')->post('/forums/react/{post}', [\App\Http\Controllers\ReactionController::class, 'reactToPost'])->name("reactToPost");
Route::middleware('auth:sanctum')->get('/forums/getAllReactionTypes', [\App\Http\Controllers\ReactionController::class, 'getAllReactionTypes'])->name("getAllReactionTypes");

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


/* === FALLBACK === */
Route::fallback(function(){
    return response()->json(['message' => 'Route not found.'], 404);
})->name('api.fallback.404');
