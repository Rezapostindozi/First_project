<?php

namespace App\Services;

use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Facades\RateLimiter;




class RateLimiteService{

    public function checkRateLimit(string $key , int $maxAttempts = 5 , int $decaySeconds = 60 ){
        if(RateLimiter::tooManyAttempts($key , $maxAttempts)){
            $secound = RateLimiter::availableIn($key);
            return response()->json(["message" => "Too many attempts"], $decaySeconds );
        }

        RateLimiter::hit($key , $decaySeconds);
        return null;
    }




}
