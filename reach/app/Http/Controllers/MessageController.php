<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use App\Services\PayUService\Exception;
use App\Events\MessagePost;
use Session;


class MessageController extends Controller
{

    public function sendMessage(Request $request)
    {
        $message = new Message;
        $message->clientId = $request->clientId;
        $message->senderId = $request->senderId;
        $message->body = $request->body;
        $message->byAgent = $request->byAgent;
        $message->isWhisper = $request->isWhisper;
        // $message->attachment->refId = $request->attach_refId;
        // $message->attachment->size = $request->attach_size;
        // $message->attachment->type = $request->attach_type;
        // $message->attachment->filename = $request->attach_filename;

        $res = $message->save();

        if($res)
        {
            return response()->json(["result" => "ok"], 201);
        } else {
            return response()->json("An error has occurred during saving", 400);
        }

    }

    public function getMessagesByClientId($clientId)
    {
        $messages = Message::where('clientId', $clientId)
            ->get(['clientId','senderId','body','byAgent','isWhispher','created_at']);
        return response()->json(['messages' => $messages],200);

    }


}
