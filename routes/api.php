<?php

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\API\AutController;
use App\Http\Controllers\API\v1\GameController;
use App\Http\Controllers\API\v1\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::get('',[Controller::class,'index']);

Route::post('v1/game',[GameController::class,'indexpost']);

Route::get('v1/games',[GameController::class,'index']);

Route::get('v1/games/{id}',[GameController::class,'game']);
Route::delete('v1/games/{id}',[GameController::class,'destroy']);


Route::post('v1/games/{id}/update',[GameController::class,'update']);


Route::get('v1/games/{id}',[GameController::class,'show']);
Route::get('v1/games/{id}/version/',[GameController::class,'version']);
Route::post('v1/games/{id}/upload',[GameController::class,'upload']);
Route::post('v1/games/{id}/scores',[GameController::class,'score']);
Route::get('v1/games/{id}/scores',[GameController::class,'scores']);

Route::post('v1/games/create',[GameController::class,'createGame']);

Route::get('v1/users/{id}',[UserController::class,'show']);

Route::post('v1/auth/register',[UserController::class,'register']);
Route::post('v1/auth/logout',[UserController::class,'logout']);


Route::post('v1/auth/signout',[AutController::class,'signout']);
Route::get('v1/auth/signin',[AutController::class,'index']);
Route::post('v1/auth/signup',[AutController::class,'singup']);


//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});
