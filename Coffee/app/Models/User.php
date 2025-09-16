<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class User extends Model
{
    protected $fillable = [
//        'id',
        'username',
        'first_name',
        'last_name',
        'email', 'phone',
        'password',
        'bio', 'avatar_url',
//        'is_active', 'last_login_at',
//        'created_at', 'updated_at',
//        'deleted_at'
    ];
    protected $casts = [
        'is_active' => 'boolean',
        'last_login_at' => 'datetime',
    ];
    public $timestamps = true;
}
