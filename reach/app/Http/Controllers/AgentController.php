<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use App\Services\PayUService\Exception;
use App\Models\Agent;
use App\Models\User;
use App\Models\Client;
use Hash;
use Session;
use Auth;


class AgentController extends Controller
{
    
    // Login a User
    public function login(Request $req) 
    {
        $agent = Agent::where('email', $req->email)->first();

        if($agent)
        {
            if(Hash::check($req->password, $agent->password))
            {
                unset($agent->password);
                Session::put('user', $agent->email);
                return response()->json("Login successful", 200);
            }
        }   
    
        return response()->json("Username or password is incorrect", 401);
    }


    // Register a new agent
    public function register(Request $req)
    {
        $agent = Agent::where('email', $req->email)->first();

        if(!is_null($agent)) {
            return response()->json("Email already exists", 400);
        }

        $agent = new Agent;
        $agent->agentId = substr(md5(uniqid(rand(), true)), 16);
        $agent->email = $req->email;
        $agent->firstname = $req->firstname;
        $agent->lastname = $req->lastname;
        $agent->nickname = $req->nickname;
        $agent->password = Hash::make($req->password);
        $res = $agent->save();

        if($res)
        {
            return response()->json("Registration successful", 200);
        }
        
        return response()->json("An error has occurred during saving", 400);
    }


    // Clear session and redirect to login page
    public function logout(Request $req)
    {
        Session::forget('user');
        $req->session()->flush();
        return redirect('login');
    }


    // Get all existing agents
    public function getAgents()
    {
        $agents = Agent::get();
        return response()->json($agents, 200);
    }


    // Get all existing agents
    public function getProfile(Request $req)
    {
        $email = Session::get('user');
        $agent = Agent::where('email', $email)->first();

        if(is_null($agent))
        {
            return response()->json(null, 401);
        }

        return response()->json($agent, 200);
    }
}
