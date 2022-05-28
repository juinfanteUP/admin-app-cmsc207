<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use App\Services\PayUService\Exception;
use App\Models\Client;
use App\Models\Widget;
use App\Models\Message;
use Session;


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
            $data = \Location::get($ip.':5000'); 
        }

        // return response()->json($req->clientId, 200);

        $widget = Widget::get()->first();
        $client = Client::where('clientId', strval($req->clientId))->first();
        // $bannedClient = Client::where('domain', '=', $req->domain)
        //                     ->orWhere('ipaddress', '=', $data->ip)
        //                      ->orWhere('city', '=', $data->city ?? "")
        //                      ->orWhere('country', '=', $data->country ?? ""); 


        // Check if widget settings is enabled
        if($widget->isActive == false) 
        {
            return response()->json([
                'widget' => "", 
                'client' => null, 
                'isNew' => false,
                'messages' => $messages,
                'status' => 'widget disabled'
            ], 200);
        }


        // Check if client is included in ban list
        // if(!($bannedClient->first())) 
        // {
        //     $client->ipaddress = $data->ip;
        //     return response()->json([
        //         'widget' => "",
        //         'client' => 0,
        //         'isNew' => false,
        //         'messages' => $messages,
        //         'status' => 'banned'
        //     ], 200);
        // }

        // Create new client record if client data is empty. Otherwise, retrieve messages
        if(is_null($client)) 
        {          
            $client = new Client;
            $client->clientId = substr(md5(uniqid(rand(), true)), 16);
            $client->ipaddress = $data->ip;
            $client->domain = $req->domain ?? "";      
            $client->country = $data->country ?? "";
            $client->city = $data->cityName ?? "";
            // $client->timezone = $data->timezone;
            $client->save();
            $isNewClient = true;
        }
        else 
        {
            $messages = Message::where('clientId', strval($req->clientId))
                        ->where('isWhisper', false)
                        ->get(['clientId','senderId','body','isAgent','isWhisper','created_at']);
        }

        // Update widget component based on settings
        $component = strval(View('widget.component'));
        $component = str_replace("%DOMAIN%",  env('APP_URL'), $component);
        $component = str_replace("%NAME%",  $widget->name ?? "Reach App", $component);   
        $component = str_replace("%COLOR%",  $widget->color ?? "#CC9900", $component);
        $client->ipaddress = $data->ip;

        // Retrieve messages if client exist
        return response()->json([
            'widget' => $component,
            'client' => $client,
            'isNew' => $isNewClient,
            'messages' => $messages,
            'status' => 'successful'
        ], 200);
    }

    
    // Get all existing clients
    public function getClients()
    {
        $clients = Client::get();
        return response()->json($clients, 200);
    }


    // Get IP details of the client
    public function getIP(Request $req)
    {
        $ip = $req->ip();
        $data = \Location::get($ip);  
        
        if($data == false){
            $data = \Location::get($ip.':5000'); 
        }

        return response()->json(['ip' =>  $ip, 'data' =>  $data], 200);
    }
}
