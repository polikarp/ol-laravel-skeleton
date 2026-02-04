<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class setAuthDB
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        $password = Session::get('db_password');
        $password = $password ? decrypt($password) : env('DB_PASSWORD', '');
        Config::set("database.connections.pgsql", [
            'driver' => 'pgsql',
            "host" => env('DB_HOST'),
            "username" => Session::get('db_username', env('DB_USERNAME', '')),
            "password" => $password,
            "port" => env('DB_PORT'),
            "database" => env('DB_DATABASE'),
        ]);
        DB::purge('pgsql');

        return $next($request);
    }
}
