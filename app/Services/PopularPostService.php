<?php

namespace App\Services;

use App\Models\Post;
use Illuminate\Support\Facades\Cache;

class PopularPostService
{
    public function fetPopularPost( int $limit = 10 , int $cacheMinutes = 10 )
    {
        return Cache::tags(['post'])->remember("popular_posts" , now()->addMinutes($cacheMinutes) , function () use ($limit)
                {
                    return Post::withCount([
                        'likes' => function ($query) {
                        $query->where('like_status', 'like');
                        }
                    ])
                    ->orderByDesc('likes_count')
                    ->take($limit)
                    ->get();
        });
    }
}
