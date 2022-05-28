<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use App\Services\PayUService\Exception;
use App\Models\Agent;
use App\Models\Client;
use Illuminate\Support\Facades\Session;
use Hash;
use Auth;

class ViewController extends Controller
{
    // Show Login Page
    public function showLogin()
    {
        return view("auth.login");
    }
 
    // Show Registration Page
    public function showRegister()
    {
        return view("auth.register");
    }

    // Show home
    public function showHome()
    {
        $user = Agent::where('email', '=', Session::get('user'))->first();
        return view('home', ['user' => $user]);
    }
}
