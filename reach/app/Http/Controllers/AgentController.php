<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use App\Services\PayUService\Exception;
use App\Models\Agent;
use App\Models\Client;
use Session;


// TODO: PLEASE REFINE AND TEST...
class AgentController extends Controller
{
    // Login a User
    public function login(Request $req) 
    {
        $req->validate([
            'email'=>'required|email|max:100',
            'password'=>'required|max:100'
        ]);

        $agent = Agent::where('agentId', $req->email)->first();

        if($agent)
        {
            if(Hash::check($req->password, $agent->password))
            {
                unset($agent->password);
                $req->session()->put('user', $agent->email);
                return redirect('/');
            }
        }   
    
        return back()->with('failed', 'Email or password is invalid');
    }


    // Register a new agent
    public function register(Request $req)
    {
        $req->validate([
            'email'=>'required|max:100|email', 
            'password'=>'required|min:8|max:100|confirmed',
            'firstname'=>'required|max:50',
            'lastname'=>'required|max:50',
            'nickname'=>'required|max:50'
        ]);

        $agent = Agent::where('email',$req->email)->first();

        if($agent>isEmpty()) {
            return back()->with('failed', 'Email already exists');
        }

        $agent = new Agent;
        $agent->email = $req->email;
        $agent->firstname = $req->firstname;
        $agent->lastname = $req->lastname;
        $agent->nickname = $req->nickname;
        $agent->password = Hash::make($req->password);
        $res = $agent->save();

        if($res)
        {
            return redirect('login')->with('success', 'Agent has been registered successfully!');
        }
        
        return back()->with('failed', 'An error has occurred');
    }


    // Clear session and redirect to login page
    public function logout(Request $req)
    {
        $req->session()->forget('user');
        $req->session()->flush();
        return redirect('login');
    }


    // Get all existing agents
    public function getAgents()
    {
        $agents = Agent::get();
        return response()->json(['agents' =>  $agents], 200);
    }


    // Get all existing agents
    public function getProfile(Request $req)
    {
        $email = Session::get('user');
        $agent = Agent::where('email',$email)->first();

        if($agent>isEmpty())
        {
            return response()->json(null, 401);
        }

        return response()->json($agent, 200);
    }
}
