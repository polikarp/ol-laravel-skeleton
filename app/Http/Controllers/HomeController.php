<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\Map\LayersService;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(LayersService $layersService)
    {
        $baseLayers = $layersService->getBaseLayers();

        $layerSelected = $baseLayers->firstWhere('visible_default', true)?->layer_name
            ?? $baseLayers->first()?->layer_name;


        return view('home', [
            'baseLayers'    => $baseLayers,
            'layerSelected' => $layerSelected,
        ]);
    }

}
