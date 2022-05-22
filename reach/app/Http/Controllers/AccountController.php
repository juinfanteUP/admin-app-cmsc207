<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use App\Services\PayUService\Exception;
use App\Events\MessagePost;
use Session;


class MessageController extends Controller
{


    // Send plain message by channel
    public function validateClientAndGetWidget(Request $req)
    {

        if($res)
        {
            $msg = Message::where('id', $msg->id)->first();
            broadcast(new MessagePost($msg, $user))->toOthers();
            return response()->json($msg, 200);
        }

        return response()->json("An error has occurred during saving", 400);
    }


}
