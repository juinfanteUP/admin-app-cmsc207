<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WidgetController;
use App\Http\Controllers\MessageController;


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



// Widget API
Route::post('/validateClientAndGetWidget', [WidgetController::class, 'validateClientAndGetWidget']);

// Message API
Route::get('/getMessages', [MessageController::class, 'getMessagesByClientId']);
Route::post('/sendMessage', [MessageController::class, 'sendMessage']);