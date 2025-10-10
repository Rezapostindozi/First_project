<?php

namespace App\Http\Controllers\Api;

use App\Enums\HttpStatus;
use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Like;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\RateLimiter;

class LikeController extends Controller
{

    public function like(Post $post): JsonResponse
    {
        $userId = auth('api')->id();

        if (!$userId) {
            return response()->json(['message' => 'User not authenticated'], Httpstatus::BAD_REQUEST->value);
        }

        $rateKey = "like-limit:{$userId}";
        if (RateLimiter::tooManyAttempts($rateKey, 7)) {
            $seconds = RateLimiter::availableIn($rateKey);
            return response()->json([
                'message' => "Rate limit exceeded. Try again in {$seconds} seconds."
            ], Httpstatus::BAD_REQUEST->value);
        }
        RateLimiter::hit($rateKey, 60);

        $like = Like::where('user_id', $userId)
            ->where('post_id', $post->id)
            ->first();

        if ($like) {
            if ($like->like_status === 'like') {
                $like->delete();
                Cache::tags(['posts', "post:{$post->id}"])->flush();
                return response()->json(['message' => 'Like removed']);
            } else {
                $like->update(['like_status' => 'like']);
                Cache::tags(['posts', "post:{$post->id}"])->flush();
                return response()->json(['message' => 'Changed to like']);
            }
        }

        Like::create([
            'user_id' => $userId,
            'post_id' => $post->id,
            'like_status' => 'like',
        ]);

        Cache::tags(['posts', "post:{$post->id}"])->flush();

        return response()->json(['message' => 'Post liked']);
    }

    public function dislike(Post $post): JsonResponse
    {
        $userId = auth('api')->id();

        if (!$userId) {
            return response()->json(['message' => 'User not authenticated'], 403);
        }

        $rateKey = "like-limit:{$userId}";
        if (RateLimiter::tooManyAttempts($rateKey, 7)) {
            $seconds = RateLimiter::availableIn($rateKey);
            return response()->json([
                'message' => "Rate limit exceeded. Try again in {$seconds} seconds."
            ], 429);
        }
        RateLimiter::hit($rateKey, 60);

        $like = Like::where('user_id', $userId)
            ->where('post_id', $post->id)
            ->first();

        if ($like) {
            if ($like->like_status === 'dislike') {
                $like->delete();
                Cache::tags(['posts', "post:{$post->id}"])->flush();
                return response()->json(['message' => 'Dislike removed']);
            } else {
                $like->update(['like_status' => 'dislike']);
                Cache::tags(['posts', "post:{$post->id}"])->flush();
                return response()->json(['message' => 'Changed to dislike']);
            }
        }

        Like::create([
            'user_id' => $userId,
            'post_id' => $post->id,
            'like_status' => 'dislike',
        ]);

        Cache::tags(['posts', "post:{$post->id}"])->flush();

        return response()->json(['message' => 'Post disliked']);
    }


    public function popular(): JsonResponse
    {
        $TTl = now()->addMinutes(10);
        $popularPosts = Cache::tags(['posts'])->remember('popular_posts', $TTl, function () {
            return Post::withCount(['likes' => function ($query) {
                $query->where('like_status', 'like');
            }])
                ->orderByDesc('likes_count')
                ->take(10)
                ->get();
        });

        return response()->json($popularPosts);
    }
}
