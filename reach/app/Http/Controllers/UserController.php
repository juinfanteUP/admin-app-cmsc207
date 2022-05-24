<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Client;
use Hash;
use Session;

class UserController extends Controller
{
    public function registerUser(Request $req)
    {
        $req->validate([
            'email'=>'required|max:100|email|unique:User',
            'password'=>'required|min:6|max:100',
            'first_name'=>'required|max:100',
            'nick_name'=>'required|max:50',
            'last_name'=>'required|max:100',
        ]);

        $user = new User();
        $user->email = $req->email;
        $user->first_name = $req->first_name;
        $user->nick_name = $req->nick_name;
        $user->middle_name = $req->middle_name ?? '';
        $user->last_name = $req->last_name;
        $user->password = Hash::make($req->password);
        $res = $user->save();

        if($res)
        {
            //return redirect('login')->with('success', 'User has been registered successfully!');
            return response()->json(["success" => "User has been registered successfully!"], 201);
            //return redirect('login')->json(["success" => "User has been registered successfully!"], 201);
        }
        
        //return back()->with('failed', 'An error has occurred');
        return response()->json("An error has occurred during saving", 400);
    }
    public function loginUser(Request $req)
    {
        $req->validate([
            'email'=>'required|max:100',
            'password'=>'required|max:100'
        ]);

        $user = User::where('email', '=', $req->email)->first();

        if($user)
        {
            if(Hash::check($req->password, $user->password))
            {
                //$req->session()->put('loginId', $user->id);
                //$req->session()->put('user', $user);
                //return redirect('/');
                return response()->json(["success" => "You are log-in!"], 201);
            }
        }
        
        //return back()->with('failed', 'Email or password is invalid');
        return response()->json("Email or password is invalid", 400);
    }
}
