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


// NOTE: Put session-checking middleware soon to all API's except [validateClient, send, getByClientId, login, register, logout]


// Widget API
Route::get('/widget/getSettings', [WidgetController::class, 'getSettings']);
Route::put('/widget/update', [WidgetController::class, 'update']);


// Message API
Route::post('/message/send', [MessageController::class, 'send']);
Route::get('/message/getByClientId', [MessageController::class, 'getByClientId']);
Route::get('/message/getReport', [MessageController::class, 'getReport']);


// Client API
Route::post('/client/validate', [ClientController::class, 'validateClient']);
Route::get('/client/getClients', [ClientController::class, 'getClients']);
Route::get('/client/getIP', [ClientController::class, 'getIP']);


// Agent API
Route::post('/agent/login', [AgentController::class, 'login'])->name('login');
Route::post('/agent/register', [AgentController::class, 'register'])->name('register');
Route::get('/agent/logout', [AgentController::class, 'logout']);
Route::get('/agent/getAgents', [AgentController::class, 'getAgents']);


