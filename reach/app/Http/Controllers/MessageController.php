<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use App\Services\PayUService\Exception;
use App\Models\Message;
use Session;


class MessageController extends Controller
{
    
    // Send message
    public function send(Request $req)
    {
        // Send message with attachment - TODO
        if($req->file()) {
            return null;
        }

        // Send plain message
        $message = new Message;
        $message->clientId =  $req->clientId;
        $message->senderId = $req->senderId;
        $message->body = $req->body;
        $message->isAgent = $req->isAgent;
        $message->isWhisper = $req->isWhisper;
        $res = $message->save();

        if($res)
        {
            return response()->json($message, 201);
        } 

        return response()->json("An error has occurred during saving", 400);
    }


    // Get message list
    public function getMessages(Request $req)
    {
        $messages = Message::get(['clientId','senderId','body','isAgent','isWhisper','created_at']);
        return response()->json($messages, 200);
    }


    // Get summary report list
    public function getReport()
    {

        // Get Message Volume Count List
        // Get Active Client Count List

        return response()->json(null, 200);
    }
}
