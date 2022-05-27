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
        $isNewClient = false;
        $messages = [];

        $ip = $req->ip();
        $data = \Location::get($ip);    
        
        if($data == false){
            $data = \Location::get($ip.':8000'); 
        }


        $widget = Widget::where('widgetId', 1)->first();
        $client = Client::where('clientId', $req->clientId)->first();
        $bannedClient = Client::where('domain', '=', $req->domain)
                            ->orWhere('ipaddress', '=', $data->ipaddress)
                            ->orWhere('city', '=', $data->city)
                            ->orWhere('country', '=', $data->country);


        // Check if widget settings is enabled
        if($widget->isActive == false) 
        {
            return response()->json([
                'widget' => "", 
                'clientId' => 0, 
                'isNew' => false,
                'ipAddress' => $data->ip,
                'messages' => $messages
            ], 200);
        }

        if ($client->isEmpty()) 
        {
            $client->clientId = 0;
        }

        // Check if client is included in ban list
        if(!($bannedClient>isEmpty())) 
        {
            return response()->json([
                'widget' => "",
                'clientId' => $client->clientId,
                'isNew' => false,
                'ipAddress' => $data->ip,
                'messages' => $messages
            ], 200);
        }
   
        // Create new client record if client data isempty. Otherwise, retrieve messages
        if($client->isEmpty()) 
        {          
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
        else 
        {
            $messages = Message::where('clientId', $client->clientId)
                        ->where('isWhispher', false)
                        ->get(['clientId','senderId','body','isAgent','isWhispher','created_at']);
        }

        // Update widget component based on settings
        $component = strval(View('widget.component'));
        $component = str_replace("%DOMAIN%",  env('APP_URL'), $component);
        $component = str_replace("%NAME%",  $widget->name ?? "Reach App", $component);   
        $component = str_replace("%COLOR%",  $widget->color ?? "#CC9900", $component);

        // Retrieve messages if client exist

        return response()->json([
            'widget' => $component,
            'clientId' => $client->clientId,
            'isNew' => $isNewClient,
            'ipAddress' => $data->ip,
            'messages' => $messages
        ], 200);
    }

    
    // Get all existing clients
    public function getClients()
    {
        $clients = Client::get();
        return response()->json(['clients' =>  $clients], 200);
    }


    // Get IP details of the client
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
