<?php

namespace App\Http\Controllers\Api;

use App\Enums\HttpStatus;
use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Services\LikeService;
use App\Services\RateLimiteService;
use App\Services\PopularPostService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;

class LikeController extends Controller
{
    protected LikeService $likeService;
    protected RateLimiteService $rateLimitService;
    protected PopularPostService $popularPostService;

    public function __construct(
        LikeService $likeService,
        RateLimiteService $rateLimitService,
        PopularPostService $popularPostService
    ) {
        $this->likeService = $likeService;
        $this->rateLimitService = $rateLimitService;
        $this->popularPostService = $popularPostService;
    }

    public function like(Post $post)
    {
        $user = auth('api')->user();

        if (!$user) {
            return response()->json(['message' => 'User not authenticated'], HttpStatus::OK->value);
        }

        $rateKey = "like-limit:{$user->id}";
        $wait = $this->rateLimitService->checkRateLimit($rateKey);

        if ($wait !== null) {
            return response()->json([
                'message' => "Rate limit exceeded. Try again in {$wait} seconds."
            ], HttpStatus::OK->value);
        }

        return $this->likeService->like($post , $user);
    }

    public function dislike(Post $post)
    {
        $user = auth('api')->user();

        if (!$user) {
            return response()->json(['message' => 'User not authenticated'], HttpStatus::NOT_FOUND->value);
        }

        $rateKey = "like-limit:{$user->id}";
        $wait = $this->rateLimitService->checkRateLimit($rateKey);

        if ($wait !== null) {
            return response()->json([
                'message' => "Rate limit exceeded. Try again in {$wait} seconds."
            ], HttpStatus::FORBIDDEN->value);
        }

        return $this->likeService->dislike($post);
    }

    public function popular()
    {
        $popularPosts = $this->popularPostService->getPopularPosts();

        return response()->json($popularPosts);
    }
}
