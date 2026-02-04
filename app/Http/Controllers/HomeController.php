<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
    public function index(Request $request)
    {
         $request->merge([
            'layer_selected' => 'gibgis:basemap_basic_1',
            'layer_geoserver' => [
                'gibgis:basemap_basic_1',
                'gibgis:aerial2013_v3',
                'gibgis:aerial2003',
                'gibgis:basemap_hybrid_2013_v3',
                'OSM_Base_Layer',
                'OSM_Base_Gray_Layer',
            ],
        ]);

        return view('home', [
                'mapConfig' => [
                    'geoserver' => [
                        'wms_url' => 'https://download.geoportal.gov.gi/geoserver/wms',
                    ],
                ],
        ]);
    }
}
