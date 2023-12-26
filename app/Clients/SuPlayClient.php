<?php


namespace App\Clients;

class SuPlayClient extends VintageStoryClient
{
    public function setUpUrl(): void
    {
        $this->url = 'https://dev.fplay.su/api/v1/servers';
    }

}
