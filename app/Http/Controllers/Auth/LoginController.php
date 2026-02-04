<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\AuditLogin;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use PDO;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = 'home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function username()
    {
        return 'rolname';
    }

    public function login (Request $request)
    {
        try {
            $param_connection = env('DB_CONNECTION').':host='.env('DB_HOST').';dbname='.env('DB_DATABASE');
            $connection = new PDO(
                $param_connection ,
                $request->username,
                $request->password
            );
            $user = User::where($this->username(), $request->username)->first();
            Auth::login($user);
            Session::put('db_username', $request->username);
            Session::put('db_password', encrypt($request->password));
            $audit_loging = new AuditLogin();
            $audit_loging->username = $request->username;
            $audit_loging->action = 'Login';
            $audit_loging->date_action = Carbon::now();
            $audit_loging->successful = true;
            $audit_loging->save();
            return $this->sendLoginResponse($request);
        } catch (\Exception $e) {
            $errorMessage = str_contains($e->getMessage(), 'SQLSTATE[08006]') ? 'Invalid credentials' : 'Unexpected error, please contact support';

            $audit_loging = new AuditLogin();
            $audit_loging->username = $request->username;
            $audit_loging->action = 'Login';
            $audit_loging->date_action = Carbon::now();
            $audit_loging->successful = false;
            $audit_loging->error_login = $e->getMessage();
            $audit_loging->save();

            return back()
            ->withInput($request->only('username'))
            ->withErrors([
                'error' =>  $errorMessage,
            ]);
        }
    }
}
