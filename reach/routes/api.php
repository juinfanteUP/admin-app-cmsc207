<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WidgetController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\AgentController;
use App\Http\Controllers\ClientController;


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
Route::resource('widget', WidgetController::class)->only(['destroy', 'show', 'store', 'update']);

// Message API
Route::get('/getMessages/{clientId}', [MessageController::class, 'getMessagesByClientId']);
Route::post('/sendMessage', [MessageController::class, 'sendMessage']);

// Agent API
Route::resource('agent', AgentController::class)->only(['destroy', 'show', 'store', 'update']);
Route::get('/agentClients/{agentId}', [AgentController::class, 'getClientsByAgentId']);

// Client API
Route::resource('client', ClientController::class)->only(['destroy', 'show', 'store', 'update']);
// Route::get('/agentClients', [ClientController::class, 'getClientsByAgentId']);

