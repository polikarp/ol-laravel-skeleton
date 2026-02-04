<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;
    use HasRoles;

    protected $table = 'pg_authid';
    protected $primaryKey = 'rolname';
    protected $keyType = 'string';
    public $timestamps = false;


    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'rolname',
        'rolsuper',
        'rolinherit',
        'rolcreaterole',
        'rolcreatedb',
        'rolcanlogin',
        'rolreplication',
        'rolbypassrls',
        'rolconnlimit',
        'rolpassword',
        'rolvaliduntil'

    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'rolpassword',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected $casts = [
        'rolpassword' => 'encrypted',
    ];

    public function hasRole($rolname)
    {
        $has_role = DB::select('SELECT groname
                                FROM pg_group JOIN pg_user ON pg_user.usesysid = ANY(pg_group.grolist)
                                WHERE pg_user.usename = ? and groname = ?', [$this->rolname, $rolname]);
        return count($has_role) > 0 ? true : false;
    }




}
