<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use App\Services\PayUService\Exception;
use App\Models\Widget;
use Session;


class WidgetController extends Controller
{

    // Get widget settings
    public function getSettings()
    {
        $widget = Widget::get()->first();

        if (is_null($widget)) {
            $widget = new Widget();
            $widget->name = "Reach App";
            $widget->isActive = true;
            $widget->color = "#5eb37a";
            $widget->hasSchedule = false;
            $widget->starttime = "6:00";
            $widget->endtime = "18:00";
            $widget->domainBanList = [];
            $widget->IpBanList = [];
            $widget->countryBanList = [];
            $widget->cityBanList = [];
            $widget->img_src = env('APP_URL') . "/assets/images/widget-icon.png";
            $widget->save();
        }

        $script = str_replace("[URL]",  env('APP_URL').'/widget/widget.js', strval(View('widget.script')));
        $widget->isActive = $widget->isActive == true ? 1 : 0;
        $widget->hasSchedule = $widget->hasSchedule == true ? 1 : 0;

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
        $widget->ipBanList = $req->ipBanList;
        $widget->countryBanList = $req->countryBanList;
        $widget->cityBanList = $req->cityBanList;
        $widget->domainWhiteList = $req->domainWhiteList;
        $widget->ipWhiteList = $req->ipWhiteList;
        $widget->countryWhiteList = $req->countryWhiteList;
        $widget->cityWhiteList = $req->cityWhiteList;
        $widget->img_src = $req->img_src;
        $widget->hasSchedule = (boolean)$req->hasSchedule;
        $widget->banListEnabled = $req->banListEnabled;
        $widget->whiteListEnabled = $req->whiteListEnabled;
        $widget->scheduleEnabled = $req->scheduleEnabled;
        $widget->schedule = $req->schedule;
        $widget->save();

        return response()->json(["result" => "ok"], 201);
    }
}
