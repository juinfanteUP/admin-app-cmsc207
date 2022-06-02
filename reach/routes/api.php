<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WidgetController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\AgentController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ReportController;

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
Route::get('/widget/settings', [WidgetController::class, 'getSettings']);
Route::put('/widget/update', [WidgetController::class, 'update']);


// Message API
Route::post('/message/send', [MessageController::class, 'send']);
Route::get('/message/list', [MessageController::class, 'getMessages']);
Route::get('/message/report', [MessageController::class, 'getReport']);
Route::get('/message/download', [AgentController::class, 'download']);


// Client API
Route::post('/client/validate', [ClientController::class, 'validateClient']);
Route::get('/client/list', [ClientController::class, 'getClients']);
Route::get('/client/getIP', [ClientController::class, 'getIP']);


// Agent API
Route::post('/agent/login', [AgentController::class, 'login']);
Route::post('/agent/register', [AgentController::class, 'register']);
Route::get('/agent/logout', [AgentController::class, 'logout']);
Route::get('/agent/list', [AgentController::class, 'getAgents']);
Route::get('/agent/profile', [AgentController::class, 'getProfile']);

// Report API
Route::get('/report/client-list', [ReportController::class, 'getClientList']);
Route::get('/report/chat-volume', [ReportController::class, 'getChatVolumeByDateRange']);
