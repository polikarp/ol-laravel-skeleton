<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\LayersController;

Auth::routes(['register' => false]);

Route::middleware(['auth', 'setAuthDB'])->group(function () {
    Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
});


Route::get('/api/layers/getLayers', [LayersController::class, 'getLayers']);


// Route::get('/proxy/geoserver', function (Request $req) {
//     $url = $req->query('url');
//     $response = Http::withHeaders([
//         'Accept' => 'application/json'
//     ])->get($url);
//     return response($response->body())
//         ->header('Access-Control-Allow-Origin', '*');
// });

Route::get('/proxy/geoserver', function (Request $req) {


    // 1) Decode the target URL (frontend sends it encoded)
    $encodedUrl = $req->query('url', '');
    $url = rawurldecode($encodedUrl);


    if (!$url || !preg_match('#^https?://#i', $url)) {
        return response('Invalid url', 400);
    }

    // 2) Optional accept override (e.g. application/json, image/png)
    $accept = $req->query('accept', '*/*');

    // 3) Forward request
    $resp = Http::withHeaders([
        'Accept' => $accept,
    ])->get($url);


    // 4) Return body with proper content-type and status
    $contentType = $resp->header('Content-Type') ?? 'application/octet-stream';


    return response($resp->body(), $resp->status())
        ->header('Content-Type', $contentType)
        ->header('Access-Control-Allow-Origin', '*');
});

