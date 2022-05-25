<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use App\Services\PayUService\Exception;
use App\Models\Agent;
use App\Models\Client;
use Session;
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

     public function showHome()
    {
        $user = Agent::where('email', '=', Session::get('user'))->first();
        //$rooms = DB::select('select * from view_room_members where user_id='.$user->id.' GROUP BY room_id');
        return view('home', ['user' => $user]);
    }
}
