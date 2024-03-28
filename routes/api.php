<?php

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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


Route::post('v1/games/{id}/scores',[GameController::class,'score']);
Route::get('v1/games/{id}/scores',[GameController::class,'scores']);
Route::post('v1/games',[GameController::class,'indexpost']);
Route::get('v1/games',[GameController::class,'indexget']);
Route::get('v1/games/{id}',[GameController::class,'game']);
Route::delete('v1/games/{id}',[GameController::class,'destroy']);
Route::put('v1/games/{id}',[GameController::class,'put']);
Route::get('v1/games/{id}/:version/',[GameController::class,'version']);
Route::post('v1/games/{id}/upload',[GameController::class,'upload']);

Route::get('v1/users/{id}',[UserController::class,'index']);
Route::post('v1/auth/signout',[AutController::class,'signout']);
Route::post('v1/auth/signin',[AutController::class,'signin']);
Route::post('v1/auth/signup',[AutController::class,'singup']);

Route::get('',[Controller::class,'index']);

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});
