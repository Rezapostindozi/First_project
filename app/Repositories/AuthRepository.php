<?php
namespace App\Repositories;

use App\Models\User;
use App\Models\Register;
use Illuminate\Support\Facades\Hash;
use mysql_xdevapi\Collection;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Auth;

class AuthRepository
{
    public function register(array $data): array
    {
        $user = User::create([
            'username' => $data['username'],
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        $token = JWTAuth::fromUser($user);
        return compact('user','token');

    }
    public function login(array $data):?array
    {
        if(!$token = Auth::guard('api')->attempt($data)){
            return null;
        }
        $user = auth()->user();

        Register::updateOrCreate(['user_id' => $user->id] , ['token' => $token]);

        return compact('user','token');
    }

    public function logout():void

    {
        $token = JWTAuth::getToken();
        JWTAuth::invalidate($token);
        Register::where('token', $token)->delete();
    }

    public function getActiveUser():Collection
    {
        return User::whereIn('id' , Register::pluck('user_id'))->get();
    }








}
