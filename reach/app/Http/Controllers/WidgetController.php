<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use App\Services\PayUService\Exception;
use App\Models\Widget;
use Session;


// TODO: PLEASE REFINE AND TEST...
class WidgetController extends Controller
{

    // Get widget settings
    public function getSettings()
    {
        $widget = Widget::get()->first();

        if (is_null($widget)) {
            return response()->json(["result" => "widget not found"], 400);
        }

        $script = str_replace("%URL%",  env('APP_URL'), strval(View('widget.script')));
        return response()->json(['widget'=> $widget, 'script'=> $script], 200);
    }


    // Update widget settings
    public function update(Request $req)
    {
        $widget = Widget::get()->first();

        if (is_null($widget)) {
            return response()->json(["result" => "widget not found"], 400);
        }

        $widget->name = $req->name;
        $widget->isActive = (boolean)$req->isActive;
        $widget->color = $req->color;
        $widget->starttime = $req->starttime;
        $widget->endtime = $req->endtime;
        $widget->domainBanList = $req->domainBanList;
        $widget->IpBanList = $req->IpBanList;
        $widget->countryBanList = $req->countryBanList;
        // $widget->timezone = $req->timezone;
        $widget->save();

        return response()->json(["result" => "ok"], 201);
    }
}
