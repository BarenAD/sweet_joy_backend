<?php


namespace App\Clients;


use GuzzleHttp\Client;

abstract class VintageStoryClient
{

    public $url;
    private $client;

    abstract function setUpUrl(): void;
    public function __construct()
    {
        $this->setUpUrl();
        $this->client = new Client([
            'verify' => false,
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json'
            ]
        ]);
    }

    public function register(array $data)
    {
        $response = $this->client->request('POST', $this->url . '/register',[
            'json' => $data
        ]);
        return json_decode($response->getBody()->getContents(), true);
    }

    public function heartbeat(array $data)
    {
        $response = $this->client->request('POST', $this->url . '/heartbeat', [
            'json' => $data
        ]);
        return json_decode($response->getBody()->getContents(), true);
    }

}
