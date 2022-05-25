<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use App\Services\PayUService\Exception;
use App\Models\Message;
use Session;


// TODO: PLEASE REFINE AND TEST...
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
        // $message->attachment->refId = $req->attach_refId;
        // $message->attachment->size = $req->attach_size;
        // $message->attachment->type = $req->attach_type;
        // $message->attachment->filename = $req->attach_filename;

        $res = $message->save();

        if($res)
        {
            return response()->json(["result" => "ok"], 201);
        } 

        return response()->json("An error has occurred during saving", 400);
    }


    // Get message list
    public function getByClientId(Request $req)
    {
        if($req)
        {
            $messages = Message::where('clientId', $req->query('clientId'))
                        ->get(['clientId','senderId','body','isAgent','isWhispher','created_at']);

            return response()->json(['messages' => $messages], 200);
        }

        return response()->json("An error has occurred during saving", 400);
    }


    // Get summary report list
    public function getReport()
    {

        // Get Message Volume Count List
        // Get Active Client Count List

        return response()->json(null, 200);
    }
}
