<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::post('/register',[UserController::class,"registration"]);
Route::get('/login',[UserController::class,"login"]);
Route::post('/login',[UserController::class,"login"]);

Route::middleware('auth:api')->group(function () { 
    Route::get('/getdata',[UserController::class,"getdata"]);
    Route::get('user-detail', [UserController::class, 'userDetail']);
    Route::get('logout', [UserController::class, 'logout']);
});
