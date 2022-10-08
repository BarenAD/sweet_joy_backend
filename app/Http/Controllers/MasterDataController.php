<?php

namespace App\Http\Controllers;

use App\Http\Services\MasterDataService;

class MasterDataController extends Controller
{
    private MasterDataService $masterDataService;

    public function __construct(
        MasterDataService $masterDataService
    ) {
        $this->masterDataService = $masterDataService;
    }

    public function masterData()
    {
        return $this->masterDataService->getMasterData();
    }
}
