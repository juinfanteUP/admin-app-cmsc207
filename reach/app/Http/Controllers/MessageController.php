<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use App\Services\PayUService\Exception;
use App\Events\MessagePost;
use Session;


class MessageController extends Controller
{

    public function sendMessage(Request $req)
    {
        if($res)
        {

        }

        return response()->json("An error has occurred during saving", 400);
    }

    public function getMessagesByClientId(Request $req)
    {
        if($res)
        {

        }

        return response()->json("An error has occurred during saving", 400);
    }
}
