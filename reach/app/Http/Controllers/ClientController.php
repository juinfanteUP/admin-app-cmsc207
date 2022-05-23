<?php

namespace App\Http\Controllers;
use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function show($clientId)
    {
        // return view('client', [
        //     'client' => Client::where('clientId', $clientId)->first()
        // ]);
        $client = Client::where('clientId',$clientId)->first();
        return response()->json(['client' => $client], 200);
    }

    public function store(Request $request)
    {
        $client = Client::where('clientId',$request->clientId)->first();
        if(is_null($client)) {
            $client = new Client;
        }
        $client->clientId = $request->clientId;
        $client->ipaddress = $request->ipaddress;
        $client->country = $request->country;
        $client->region = $request->region;
        $client->timezone = $request->timezone;
        $client->domain = $request->domain;
        $client->push('agentId', $request->agentId, true);
        $client->save();

        return response()->json(["result" => "ok"], 201);
    }

    public function update($clientId, Request $request)
    {
        $client = Client::where('clientId',$clientId)->first();
        if (is_null($client)) {
            return response()->json(["result" => "nok"], 400);
        }
        $client->ipaddress = $request->ipaddress;
        $client->country = $request->country;
        $client->region = $request->region;
        $client->timezone = $request->timezone;
        $client->domain = $request->domain;
        $client->push('agentId', $request->agentId, true);
        $client->save();
        return response()->json(["result" => "ok"], 201);
    }

    public function destroy($clientId)
    {
        $client = Client::where('clientId',$clientId)->first();
        $client->delete();
        
        return response()->json(["result" => "ok"], 201);
    }
}
