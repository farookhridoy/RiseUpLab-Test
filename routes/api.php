<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\V1\AuthenticatedController;
use App\Http\Controllers\API\V1\PostsController;
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


//Register & Login Route For API/V1
Route::post('login', [AuthenticatedController::class, 'login']);
Route::post('register', [AuthenticatedController::class, 'register']);

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

//After Login
Route::group(['middleware' => 'auth:api'], function(){

	//Logout
	Route::post('logout',[AuthenticatedController::class, 'logout']);

	//Posts Resource Route [Store, Show, Update, Delete]
 	Route::apiResource('posts', PostsController::class);
});
