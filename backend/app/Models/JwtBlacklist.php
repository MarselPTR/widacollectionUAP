<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JwtBlacklist extends Model
{
    protected $table = 'jwt_blacklists';

    public $timestamps = false;

    protected $fillable = [
        'jti',
        'expires_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
    ];
}
