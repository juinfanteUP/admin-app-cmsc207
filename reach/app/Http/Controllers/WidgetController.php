<?php

namespace App\Http\Controllers;

use App\Models\Widget;
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
        
    }

    public function show($widgetId)
    {
        // return view('agent', [
        //     'agent' => Widget::where('widgetId', $widgetId)->first()
        // ]);
        $widget = Widget::where('widgetId', $widgetId)->first();
        return response()->json(['widget' => $widget],200);
    }

    public function store(Request $request)
    {
        $widget = Widget::where('widgetId',$request->widgetId)->first();

        if(is_null($widget)) {
            $widget = new Widget;
        }

        $widget->widgetId = $request->widgetId;
        $widget->name = $request->name;
        $widget->isActive = $request->isActive;
        $widget->color = $request->color;
        $widget->timezone = $request->timezone;
        $widget->starttime = $request->starttime;
        $widget->endtime = $request->endtime;
        $widget->domainBanList = $request->domainBanList;
        $widget->IpBanList = $request->IpBanList;
        $widget->countryBanList = $request->countryBanList;
 
        $widget->save();
 
        return response()->json(["result" => "ok"], 201);
    }

    public function update($widgetId, Request $request)
    {
        $widget = Widget::where('widgetId',$widgetId)->first();
        if (is_null($widget)) {
            return response()->json(["result" => "nok"], 400);
        }
        $widget->name = $request->name;
        $widget->isActive = $request->isActive;
        $widget->color = $request->color;
        $widget->timezone = $request->timezone;
        $widget->starttime = $request->starttime;
        $widget->endtime = $request->endtime;
        $widget->domainBanList = $request->domainBanList;
        $widget->IpBanList = $request->IpBanList;
        $widget->countryBanList = $request->countryBanList;
 
        $widget->save();
 
        return response()->json(["result" => "ok"], 201);
    }

    public function destroy($widgetId)
    {
        $widget = Widget::where('widgetId',$widgetId)->first();
        $widget->delete();
        
        return response()->json(["result" => "ok"], 201);
    }

    
}
