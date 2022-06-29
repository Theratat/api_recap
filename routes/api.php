<?php
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ReviewController;
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

Route::group(['middleware' => ['auth:sanctum']], function() {    
    Route::post('/logout',   [AuthController::class, 'logout']);
    Route::resource('/review', ReviewController::class);
  });

Route::post('/regis', [AuthController::class , 'regis']);
Route::post('/login', [AuthController::class , 'login']);



