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
        $message->isAgent = (boolean)$req->isAgent;
        $message->isWhisper = (boolean)$req->isWhisper;
        // $message->attachment->refId = $req->attach_refId;
        // $message->attachment->size = $req->attach_size;
        // $message->attachment->type = $req->attach_type;
        // $message->attachment->filename = $req->attach_filename;

        $res = $message->save();

        if($res)
        {
            return response()->json($req, 201);
        } 

        return response()->json("An error has occurred during saving", 400);
    }


    // Get message list
    public function getByClientId(Request $req)
    {
        if($req)
        {
            $messages = Message::where('clientId', $req->query('clientId'))
                        ->get(['clientId','senderId','body','isAgent','isWhisper','createddtm']);

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


    // Download file attachment
    public function download(Request $req)
    {
    //   $att = Attachment::where('id', $req->query('id'))->first();

    //   if($att)
    //   {
    //       $file_path = storage_path($att->file_path);
    //       return Response::download($file_path, $att->file_name);
    //   }
        
        return response()->json("Attachment not found.", 404);
    }



    // Upload attachment as message
    // public function upload(Request $req)
    // {
    //     if($req->file()) 
    //     {
    //         $user = Session::get('user');
    //         $fileExt = $req->file->extension();
    //         $filePath = $req->file('file')
    //                         ->storeAs('uploads', substr(md5(uniqid(rand(), true)), 16) . "." . $fileExt, 'public');

    //         $att = new Attachment();
    //         $att->file_name = $req->file->getClientOriginalName();
    //         $att->file_path =  'app/public/' . $filePath;
    //         $att->mb_size =  $req->file->getSize();
    //         $upload = $att->save();

    //         if($upload)
    //         {
    //             $msg = new Message();
    //             $msg->channel_id = $req->query('channel_id');
    //             $msg->user_id = $user->id;
    //             $msg->attachment_id = $att->id;
    //             $msg->message = json_decode($req->document)->message ?? "";
    //             $res = $msg->save();
        
    //             if($res)
    //             {
    //                 $res_msg = Message::where('id', $msg->id)->first();
    //                 $res_att = Attachment::where('id', $att->id)->first();

    //                 return response()->json([
    //                     'clientId'=> $res_msg->user_id,
    //                     'agentId' => $res_msg->channel_id,
    //                     'message' => $res_msg->message,   
    //                     'created_dtm'=> $res_msg->created_dtm,
    //                     'name' => $user->name,          
    //                     'picture' => $user->picture,  
    //                     'file_name' => $res_att->file_name,
    //                     'mb_size' => $res_att->mb_size
    //                 ], 200);
    //             }
    //         }
    //     }

    //     return response()->json("An error has occurred during upload", 400);
    // }
}
