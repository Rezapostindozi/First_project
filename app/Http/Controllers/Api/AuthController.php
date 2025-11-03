<?php

namespace App\Http\Controllers\Api;

use App\Events\UserRegistered;
use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use App\Services\AuthService;
use App\Enums\HttpStatus;
use Illuminate\Http\JsonResponse;
class AuthController extends Controller
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function register(RegisterRequest $request)
    {
        $result = $this->authService->register($request->validated());
        event(new UserRegistered($result['user']));
        return response()->json([
            'message' => 'User successfully registered',
            'user' => $result['user'],
            'token' => $result['token']
        ] , HttpStatus::CREATED->value);

    }
    public function login(LoginRequest $request)
    {
        $result = $this->authService->login($request->validated());

        if(!$result){
            return response()->json([
                'errors' => 'invalid username or password',
            ] , HttpStatus::FORBIDDEN->value);
        }
        return response()->json([
            'message' => 'User successfully logged in',
            'user' => $result['user'],
            'token' => $result['token']
        ] , HttpStatus::OK->value);
    }

    public function logout()
    {
        $this->authService->logout();
        return response()->json([
            'message' => 'Logged out successfully'
        ]);
    }
    public function me()
    {
        return response()->json([
            'active_user' => $this->authService->showActiveUser()
        ]);
    }






}
