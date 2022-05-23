<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Agent;
use App\Models\Client;

class AgentController extends Controller
{
    public function show($agentId)
    {
        // return view('agent', [
        //     'agent' => Agent::where('agentId', $agentId)->first()
        // ]);
        $agent = Agent::where('agentId', $agentId)->first();
        return response()->json(['agent' => $agent],200);
    }

    public function store(Request $request)
    {
        $agent = Agent::where('email',$request->email)->first();

        if(is_null($agent)) {
            $agent = new Agent;
        }

        $agent->agentId = $request->agentId;
        $agent->email = $request->email;
        $agent->firstname = $request->firstname;
        $agent->lastname = $request->lastname;
        $agent->nickname = $request->nickname;
        // $agent->createddate = $request->createddate;
 
        $agent->save();
        // $agent->upsert(["upsert" => true]);
 
        return response()->json(["result" => "ok"], 201);
    }

    public function update($agentId, Request $request)
    {
        $agent = Agent::where('agentId',$agentId)->first();
        if (is_null($agent)) {
            return response()->json(["result" => "nok"], 400);
        }
        $agent->email = $request->email;
        $agent->firstname = $request->firstname;
        $agent->lastname = $request->lastname;
        $agent->nickname = $request->nickname;
        $agent->save();
        // $agent->upsert(["upsert" => true]);
 
        return response()->json(["result" => "ok"], 201);
    }

    public function destroy($agentId)
    {
        $agent = Agent::where('agentId',$agentId)->first();
        $agent->delete();
        
        return response()->json(["result" => "ok"], 201);
    }

    public function getClientsByAgentId($agentId)
    {
        // return view('agentClients', [
        //     'clients' => Client::where('agentId', $agentId)->get(),
        //     'agent' => Agent::where('agentId',$request->agentId)->first()
        // ]);
        $clients = Client::where('agentId', 'all', [$agentId])->get();
        return response()->json(['clients' => $clients],200);

    }

}
