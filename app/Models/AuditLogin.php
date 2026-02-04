<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuditLogin extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'gcdr.audit_login';
    public $timestamps = false;

    protected $guarded = [];
    protected $hidden = [];

    protected $casts = [
        'date_action' => 'datetime:Y-m-d H:i:s',
    ];
}
