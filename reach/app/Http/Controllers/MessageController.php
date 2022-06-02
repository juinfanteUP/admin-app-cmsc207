<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use App\Services\PayUService\Exception;
use App\Models\Message;
use App\Models\Client;
use App\Models\Attachment;
use Session;


class MessageController extends Controller
{
    
    // Send message
    public function send(Request $req)
    {
        $attachmentId = 0;

        // Send message with attachment - TODO
        if($req->file())
        {
            $file = request()->file('uploadFile');
            $uid = substr(md5(uniqid(rand(), true)), 16);

            $fileExt = $req->file->extension();
            $filePath = $req->file('file')
                             ->storeAs('uploads', $uid . "." . $fileExt, 'uploads');

            $attachment = new Attachment;
            $attachment->id =  $uid;
            $attachment->path =  'public/' . $filePath;
            $attachment->name = $req->file->getClientOriginalName();
            $attachment->path = $req->file->getSize();
            $attachment->save();
            $attachmentId = $uid;
        }

        // Send plain message
        $message = new Message;
        $message->clientId =  $req->clientId;
        $message->senderId = $req->senderId;
        $message->body = $req->body;
        $message->isAgent = $req->isAgent;
        $message->isWhisper = $req->isWhisper;
        $message->attachmentId = $attachmentUrl;
        
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
        $messageVolumeCount  = Message::get(['id','created_at'])->count();
        $clientCount = Client::get(['id','created_at'])->count();;  
        $historyList = [];

        return response()->json([ 
                "messageVolumeCount" => $messageVolumeCount,
                "clientCount" => $clientCount,
                "historyList" => $historyList,
        ], 200);
    }


    // Download file attachment
    public function download(Request $req)
    {
        $att = Attachment::where('id', $req->query('id'))->first();

        if($att)
        {
            return Response::download($att->path, $att->name);
        }
        
        return response()->json("Attachment not found.", 404);
    }
}
