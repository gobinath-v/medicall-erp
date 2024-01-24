<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserLoginActivity extends Model
{
    use HasFactory;

    protected $fillable = [
        'userable_id',
        'userable_type',
        'last_login_at',
        'last_logout_at',
        'ip_address',
        'user_agent',
    ];
}
