<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use App\Services\PayUService\Exception;
use Session;


class WidgetController extends Controller
{

    // Send plain message by channel
    public function validateClientAndGetWidget(Request $req)
    {
        // do something for validation

        return response()->json(strval(View('widget.component')), 200);
    }


}
