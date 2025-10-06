<?php

namespace App\Models;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Http\Middleware\Authenticate;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;


class User extends Authenticatable implements JWTSubject
{


    use HasFactory;
    protected $fillable = [
        'username',
        'first_name',
        'last_name',
        'country',
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


    public function getJWTIdentifier(){
        return $this->getKey();
    }
    public function getJWTCustomClaims(){
        return ['secret_token'];
    }
}
