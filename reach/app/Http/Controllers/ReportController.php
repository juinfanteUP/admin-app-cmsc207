<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use App\Services\PayUService\Exception;
use App\Models\Message;
use App\Models\Client;
use Session;

use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;


class ReportController extends Controller
{
    // Get List of Clients
    public function getClientList()
    {
        // Get Client and their data
        $clients= Client::get(['updated_at','clientId','ipaddress','domain','country','city'])
                            // add page_url and other info ie., browser_agent
                        ->sortByDesc('updated_at') 
                        ->take(10) //recent 10 clients
                            ;  

        return response()->json([ 
                "clients" => $clients,
        ], 200);
    }

    
    // Get Chat Volume per Day
    public function getChatVolumeByDateRange(Request $request)
    {
        $dateStart = $request->date_start == null ? Carbon::today()->subDays(5) : Carbon::parse($request->date_start);
        $dateEnd = $request->date_end == null ? Carbon::today()->addDay() : Carbon::parse($request->date_end)->addDay();

        // check inverse dates
        if( $dateStart > $dateEnd )
        {
            return response()->json(["result" => "Invalid request. Inverse Date."], 400);
        }

        $diff_in_days = $dateEnd->diffInDays($dateStart);
        
        // check date range to 31 days max
        if( $diff_in_days > 31 )
        {
            return response()->json(["result" => "Invalid request. Requested date is more than 31 days"], 400);
        }

        // Get Client and their data
        $messages = Message::where('created_at', '>=', $dateStart)
                        ->where('created_at', '<=', $dateEnd)
                        ->get()
                        ->groupBy(function($item) {
                            return Carbon::parse($item->created_at)->format("Y-m-d");
                        })
                        ->sortByDesc('created_at');  

        $chatVolumePerDay = [];
        foreach ($messages as $key => $message) {
            $day = $key;
            $totalCount = $message->count();
            array_push($chatVolumePerDay , [ "date" => $day,
                                            "messageVolumeCount" => $totalCount]);
        }
        return response()->json([ 
                "chatVolumePerDay" => $chatVolumePerDay,
        ], 200);
    }
}
