<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use App\Services\PayUService\Exception;
use App\Models\Client;
use App\Models\Widget;
use Session;


// TODO: PLEASE REFINE AND TEST...
class ClientController extends Controller
{

    // Validate widget based on client id and client details
    public function validateClient(Request $req)
    {  

        // Get IP and client's details
        $ip = $req->ip();
        $data = \Location::get($ip);  
        
        if($data == false){
            $data = \Location::get($ip.':8000'); 

            // data: ['countryName', 'cityName', 'ip', 'domain']    -- use these properties for validation
        }


        // Get widget settings
        $isNewClient = false;
        $widget = Widget::where('widgetId', 1)->first();

        // Do validation using widget variable
        // Validate client's domain/ip/location

        if($widget->isActive == false) // OR INCLUDED IN BAN LIST) 
        {
            return response()->json(['widget' => null, 'clientId' => 0, 'isNew' => false], 200);
        }

        $client = Client::where('clientId', $req->clientId)->first();

        if(is_null($agent)) {          
            $client = new Client;
            $client->clientId = substr(md5(uniqid(rand(), true)), 16);
            $client->ipaddress = $data->ip;
            $client->domain = $req->domain;      
            $client->country = $data->country;
            $client->city = $data->cityName;
            $client->timezone = $data->timezone;
            $client->save();
            $isNewClient = true;
        }

        // Update widget component based on settings
        $component = strval(View('widget.component'));
        $component = str_replace("%DOMAIN%",  env('APP_URL'), $component);
        $component = str_replace("%NAME%",  $widget->name ?? "Reach App", $component);   
        $component = str_replace("%COLOR%",  $widget->color ?? "#CC9900", $component);

        return response()->json([
            'widget' => $component,
            'clientId' => $client->clientId,
            'isNew' => $isNewClient
        ], 200);
    }

    
    // Get all existing clients
    public function getClients()
    {
        $clients = Client::get();
        return response()->json(['clients' =>  $clients], 200);
    }


    // Get IP
    public function getIP(Request $req)
    {
        $ip = $req->ip();
        $data = \Location::get($ip);  
        
        if($data == false){
            $data = \Location::get($ip.':8000'); 
        }

        return response()->json(['ip' =>  $ip, 'data' =>  $data], 200);
    }
}
