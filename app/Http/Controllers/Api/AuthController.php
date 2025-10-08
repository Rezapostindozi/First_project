<?php

namespace App\Http\Controllers\Api;

use App\Enums\HttpStatus;
use App\Http\Controllers\Controller;
use App\Models\Register;
use App\Services\LoggerService;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
class AuthController extends Controller
{

    public function register (Request $request)
    {
        $validator = Validator::make(request()->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6'
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' =>$validator->errors(), HttpStatus::UNPROCESSABLE_ENTITY]);
        }
        $user = User::create([
            'username' => request('username'),
            'first_name' => request('first_name'),
            'last_name' => request('last_name'),
            'email' => request('email'),
            'password' => bcrypt(request('password')),
        ]);

        $token = JWTAuth::fromUser($user);


        return response()->json([
            'message' => 'Register successfully',
            'user' => $user,
            'token' => $token
        ],HttpStatus::CREATED->value);
    }
    public function login(Request $request)
    {
        $login = $request->only(['email', 'password']);

        if (!$token = Auth::guard('api')->attempt($login)) {
            return response()->json(['error' => 'Invalid username or password'], HttpStatus::UNAUTHORIZED->value);
        }

        $user = auth()->user();

        Register::where('user_id', $user->id)->delete();

        Register::updateOrCreate(
            ['user_id' => $user->id],
            ['token' => $token]
        );

        return response()->json([
            'message' => 'Login successfully',
            'token'   => $token,
            'user'    => $user,
        ]);
    }

    public function me (){

        return response()->json(auth()->user());
    }

    public function logout()
    {
        try {
            $token = JWTAuth::getToken();
            JWTAuth::invalidate($token);
            Register::where('token', $token)->delete();
            return response()->json(['message' => 'Successfully logged out']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to logout, token invalid'], 400);
        }
    }

    public function show()
    {
        $users = User::whereIn('id', Register::pluck('user_id'))->get();
        return response()->json(['active_users' => $users]);
    }

}
