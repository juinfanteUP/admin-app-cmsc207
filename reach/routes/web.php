<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ViewController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Home page
// Route::view('/', 'home')->middleware('redirectIfAnonymous'); //Commented this to allow user access without Auth
Route::get('/', [ViewController::class, 'showHome'])->middleware('redirectIfAnonymous');

// Authentication page
// Route::view('/login', 'auth.login')->middleware('redirectIfAuthenticated');
// Route::view('/register', 'auth.register')->middleware('redirectIfAuthenticated');
Route::get('/login', [ViewController::class, 'showLogin'])->middleware('redirectIfAuthenticated');
Route::get('/register', [ViewController::class, 'showRegister'])->middleware('redirectIfAuthenticated');


