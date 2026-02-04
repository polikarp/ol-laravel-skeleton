<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class SetDatabaseCredentials
{
    public function handle(Request $request, Closure $next)
    {
        if (Session::has('db_username') && Session::has('db_password')) {
            Config::set('database.connections.pgsql.username', Session::get('db_username'));
            Config::set('database.connections.pgsql.password', decrypt(Session::get('db_password')));
            DB::purge('pgsql');
            DB::reconnect('pgsql');
        }

        return $next($request);
    }
}
