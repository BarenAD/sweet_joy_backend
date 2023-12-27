<?php

namespace App\Http\Controllers;

use App\Clients\LicenseClient;
use App\Clients\SuPlayClient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class VintageStoryController extends Controller
{
    private $clients;
    public function __construct(
    ) {
        $this->clients = [
            new SuPlayClient(),
            new LicenseClient(),
        ];
    }

    public function register(Request $request)
    {
        $data = $request->all();
        $lastResponse = null;
        foreach ($this->clients as $client) {
            $lastResponse = $client->register($data);
            Cache::put($client->url, $lastResponse['data']);
        }
        return response()->json($lastResponse);
    }

    public function heartbeat(Request $request)
    {
        $data = $request->all();
        $lastResponse = null;
        $countPlayer = $data['players'];
        foreach ($this->clients as $client) {
            $preparedData = [
                'token' => Cache::get($client->url),
                'players' => $countPlayer,
            ];
            $lastResponse = $client->heartbeat($preparedData);
        }
        return response()->json($lastResponse);
    }

}
