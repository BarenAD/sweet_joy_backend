<?php


namespace App\Clients;

class LicenseClient extends VintageStoryClient
{
    public function setUpUrl(): void
    {
        $this->url = 'https://masterserver.vintagestory.at/api/v1/servers';
    }

}
