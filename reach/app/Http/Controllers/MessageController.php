<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use App\Services\PayUService\Exception;
use App\Models\Message;
use App\Models\Client;
use App\Models\Attachment;
use Session;

use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class MessageController extends Controller
{
    
    // Send message
    public function send(Request $req)
    {
        if($req->file())
        {
            $file = request()->file('uploadFile');
            $uid = substr(md5(uniqid(rand(), true)), 16);

            $fileExt = $req->file->extension();
            $filePath = $req->file('file')
                             ->storeAs('uploads', $uid . "." . $fileExt, 'uploads');

            $attachment = new Attachment;
            $attachment->id =  $uid;
            $attachment->filePath =  'public/' . $filePath;
            $attachment->fileName = $req->file->getClientOriginalName();
            $attachment->fileSize = $req->file->getSize();
            $attachment->save();
            $attachmentId = $uid;
            $jsonReq = json_decode($req->document);

            // Send plain message
            $message = new Message;
            $message->clientId =  $jsonReq->clientId;
            $message->senderId = $jsonReq->senderId;
            $message->body = $jsonReq->body;
            $message->isAgent = $jsonReq->isAgent;
            $message->isWhisper = $jsonReq->isWhisper;
            $message->isSeen = false;
            $message->attachmentId = $attachmentId;
            $message->fileName = $attachment->fileName;
            $message->fileSize = $attachment->fileSize;

            // get client conversationId
            $client = Client::where("clientId", $req->clientId)->first();

            $message->conversationId = $client->latestConversationId;
            $res = $message->save();

            if($res)
            {
                return response()->json($message, 201);
            }

            return response()->json("An error has occurred during saving", 400);
        }
        else 
        {
            // Send plain message
            $message = new Message;
            $message->clientId =  $req->clientId;
            $message->senderId = $req->senderId;
            $message->body = $req->body;
            $message->isAgent = $req->isAgent;
            $message->isWhisper = $req->isWhisper;
            $message->isSeen = false;
            $message->attachmentId = '0';
            $message->fileName = '';
            $message->fileSize = 0;
            // get client conversationId
            $client = Client::where("clientId", $req->clientId)->first();

            $message->conversationId = $client->latestConversationId;
            $res = $message->save();

            if($res)
            {
                return response()->json($message, 201);
            }

            return response()->json("An error has occurred during saving", 400);
        }
    }


    // Get message list
    public function getMessages(Request $req)
    {
        $messages = Message::leftJoin("attachments", "attachments.id", "=", "messages.attachmentId")->get();
        return response()->json($messages, 200);
    }

    
    // Get summary report list
    public function getReport()
    {
        // Get Message Volume Count List
        $messageVolumeCount  = Message::get(['id','created_at'])->count();
        $clientCount = Client::get(['id','created_at'])->count();  

        // HistoryList
        $messages = Message::get(['created_at','clientId','id'])
                        ->groupBy(function($item) {
                            return Carbon::parse($item->created_at)->format("Y-m-d");
                        })
                        ->sortByDesc('created_at')
                        ->take(31);  // last 31 days

        $historyList = [];
        foreach ($messages as $key => $message) {
            $day = $key;
            $totalCount = $message->count();
            $totalClient =  $message->unique('clientId')->count();
            array_push($historyList , [ "date" => $day ,
                                            "clientCount" =>  $totalClient, 
                                            "messageVolumeCount" => $totalCount]);
        }

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
            return Response::download($att->filePath, $att->fileName);
        }
        
        return response()->json("Attachment not found.", 404);
    }


    // Set messages seen by client
    public function setMessagesSeen(Request $req)
    {
        $item = Message::where("clientId", $req->clientId)
                        ->update([ 'isSeen' => false ]);

        return response()->json("Updated successfully!", 200);
    }
}
