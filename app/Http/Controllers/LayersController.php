<?php

namespace App\Http\Controllers;

use App\Services\Map\LayersService;

class LayersController extends Controller
{
    public function getLayers(LayersService $svc)
    {
        return response()->json($svc->build());
    }
}
