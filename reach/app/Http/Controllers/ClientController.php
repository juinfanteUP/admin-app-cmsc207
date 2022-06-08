<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use App\Services\PayUService\Exception;
use App\Models\ClientBan;
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
        $client = Client::where('clientId', strval($req->clientId))
                            ->where('domain', strval($req->domain))->first();
        // $bannedClient = Client::where('domain', '=', $req->domain)
        //                     ->orWhere('ipaddress', '=', $data->ip)
        //                      ->orWhere('city', '=', $data->city ?? "")
        //                      ->orWhere('country', '=', $data->country ?? ""); 

        $whitelisted = false;
        $banlisted = false;
        $isScheduled = false;
        
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
        else{
            if ($widget->whiteListEnabled == true){
                if(in_array($data->ip  ?? "", $widget->ipWhiteList) || 
                    in_array($req->domain ?? "", $widget->domainWhiteList) || 
                    in_array($data->country ?? "", $widget->countryWhiteList) || 
                    in_array($data->cityName  ?? "", $widget->cityWhiteList)){
                        $whitelisted = true; 
                }
                else{
                    $whitelisted = false;
                }
            }
            else{
                $whitelisted = true;
            }

            if($widget->banListEnabled == true){
                if(in_array($data->ip  ?? "", $widget->IpBanList) || 
                    in_array($req->domain  ?? "", $widget->domainBanList) || 
                    in_array($data->country  ?? "", $widget->countryBanList) || 
                    in_array($data->cityName  ?? "", $widget->cityBanList)){
                        $banlisted = true;    
                }
                else{
                    $banlisted = false;
                }
                //$whitelisted = true;
            }
            else{
                $banlisted = false;
            }
            
            if($widget->scheduleEnabled == true){
                $sched = $widget->schedule;
                
                $day = date('l');
                $time = date("H:i");
          
                foreach ($sched as $s){
                    
                    if($s['day'] == $day){
                        if($s['enabled'] == true){
                            if($time >= $s['start_time'] && $time <= $s['end_time']){
                                $isScheduled = true;
                            }
                            else{
                                $isScheduled = false;
                            }
                        }
                        else{
                            $isScheduled = true;
                        }
                    }
                }
            }
            else{
                $isScheduled = true;
            }
        }

        if($whitelisted == false || $banlisted == true || $isScheduled == false) 
        {
            return response()->json([
                'widget' => "", 
                'client' => null, 
                'isNew' => false,
                'messages' => $messages,
                'status' => 'widget disabled'
            ], 200);
        }


        // Validat if client is banned
        $clientBan = ClientBan::where('domain', strval($req->domain))
                                ->where('country', strval($data->countryName))
                                ->where('ipaddress', strval($data->ip))
                                ->first();
                                
        if(!is_null($clientBan)) 
        {
            return response()->json([
                'widget' => "", 
                'client' => null, 
                'isNew' => false,
                'messages' => $messages,
                'status' => 'domain/country/ip address banned'
            ], 403);
         }
        

        // Create new client record if client data is empty. Otherwise, retrieve messages
        if(is_null($client)) 
        {          
            $client = new Client;
            $client->clientId = substr(md5(uniqid(rand(), true)), 16);
            $client->ipaddress = $data->ip;
            $client->domain = $req->domain ?? "";      
            $client->source = $req->source ?? "";  
            $client->country = $data->countryName ?? "";
            $client->city = $data->cityName ?? "";
            $client->isActive = true;
            $client->isMute = false;
            $client->save();
            $isNewClient = true;
        }
        else 
        {
            $messages = Message::where('clientId', $req->clientId)
                        ->where('isWhisper', 'false')
                        ->get(['clientId','senderId','body','isAgent','isWhisper','created_at']);
        }

        // Update widget component based on settings
        $component = strval(View('widget.component'));
        $client->ipaddress = $data->ip;

        // Get URL links
        $links = [
            'sourceDomain' => env('APP_URL'),
            'socketioLib' => env('SOCKET_LIB_URL'),
            'socketurl' => env('SOCKET_SERVER_URL'),
        ];

        // Retrieve messages if client exist
        return response()->json([
            'settings' => $widget,
            'widget' => $component,
            'client' => $client,
            'isNew' => $isNewClient,
            'messages' => $messages,
            'links' => $links,
            'status' => 'successful'
        ], 200);
    }

    
    // Get all existing clients
    public function getClients()
    {
        $clients = Client::where("isActive", true)->get();
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

    // update client info
    public function update(Request $req)
    {
        $item = Client::where("clientId", $req->clientId)
                        ->update([ 
                            'isMute' => $req->isMute, 
                            'notes' => $req->notes, 
                            'label' => $req->label
                        ]);

        return response()->json("Updated successfully!", 200);
    }


    // End Session Client
    public function endSession(Request $req)
    {
        $item = Client::where("clientId", $req->clientId)
                        ->update([ 'isActive' => false ]);

        return response()->json("Updated successfully!", 200);
    }
    
    
    // Get Ban List 
    public function getBanList()
    {
        $clients = ClientBan::get();
        return response()->json($clients, 200);
    }


    // Ban client by ip and domain
    public function addClientInBanList(Request $req)
    {
        $email = Session::get('user');
        $item = Client::where("clientId", $req->clientId)
                        ->update([ 'isActive' => false ]);
              
        $clientBan = new ClientBan();
        $clientBan->clientId = $req->clientId;
        $clientBan->ipaddress = $req->ipaddress;
        $clientBan->domain = $req->domain;
        $clientBan->country = $req->country;
        $clientBan->bannedBy = $email;
        $clientBan->save();

        return response()->json("Client has been banned successfully!", 200);
    }


    // Get Ban List 
    public function removeClientFromBanList(Request $req)
    {
        $item = ClientBan::where("clientId", $req->clientId)->delete();
        return response()->json("Client has been removed from the ban list successfully!", 200);
    }
}
