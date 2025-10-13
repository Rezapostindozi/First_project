<?php

namespace App\Services;

use App\Enums\HttpStatus;
use App\Models\Like;
use App\Models\Post;
use App\Models\User;
use App\Notifications\PostLikedNotification;
use Illuminate\Support\Facades\Cache;


class LikeService{

    public function like(Post $post , User $liker){
        $userId = auth('api')->id();

        if(!$userId){
            return response()->json(["message" => "User not authenticated"], Httpstatus::OK->value);
        }

        $like  = Like::where('user_id' , $userId)
            ->where('post_id', $post->id)
            ->first();

        if($like){
            if($like->like_status === 'like'){
                $like->delete();
                Cache::tags(['posts',"post:{$post->id}"])->flush();
                return response()->json(["message" => "Like removed"], Httpstatus::OK->value);
            }
            else{
                $like->update(['like_status' => 'like']);
                Cache::tags(['posts' , "post:{$post->id}"])->flush();
                return response()->json(["message" => "Liked"], Httpstatus::OK->value);
            }
        }
        Like::create([
            'user_id' => $userId,
            'post_id' => $post->id,
            'like_status' => 'like'
        ]);

        $owner = $post->user;
        $owner->notify(new PostLikedNotification($liker , $post));

        Cache::tags(['posts' ,"post:{$post->id}"])->flush();
        return response()->json(["message" => "Liked"], Httpstatus::OK->value);
    }
    public function dislike(Post $post){

        $userId = auth('api')->id();
        if(!$userId){
            return response()->json(["message" => "User not authenticated"], Httpstatus::OK->value);
        }
        $like = Like::where('user_id' , $userId)
            ->where('post_id' , $post->id)
            ->first();

        if($like){
            if($like->like_status === 'dislike'){
                $like->delete();
                Cache::tags(['posts', "post:{$post->id}"])->flush();
                return response()->json(["message" => "Disliked"], Httpstatus::OK->value);
            }
            else{
                $like->update(['like_status' => 'dislike']);
                Cache::tags(['post' , "post:{$post->id}"])->flush();
                return response()->json(["message" => "Disliked"], Httpstatus::OK->value);
            }

        }
        Like::create([
            'user_id' => $userId,
            'post_id' => $post->id,
            'like_status' => 'dislike'
        ]);
        Cache::tags(['post',"post:{$post->id}"])->flush();
        return response()->json(["message" => "Disliked"], Httpstatus::OK->value);
    }
}
