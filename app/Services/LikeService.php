<?php

namespace App\Services;

use App\Enums\HttpStatus;
use App\Models\Like;
use App\Models\Post;
use App\Models\User;
use App\Notifications\PostLikedNotification;
use App\Repositories\LikeRepository;
use Illuminate\Support\Facades\Cache;


class LikeService
{
    protected $likeRepo;

    public function __construct(LikeRepository $likeRepo)
    {
        $this->likeRepo = $likeRepo;
    }


    public function like(Post $post , User $liker)
    {
        $userId = auth('api')->id();

        if(!$userId){
            return response()->json(["message" => "User not authenticated"], Httpstatus::FORBIDDEN->value);
        }

        $like = $this->likeRepo->find($userId , $post->id);

        if($like) {
            if ($like->like_status === 'like') {
                $like->likeRepo->delete($like);
                return response()->json(["message" => "Like removed"], Httpstatus::OK->value);
            } else {
                $like->likeRepo->update(['like_status' => 'like']);
                return response()->json(["message" => "Liked"], Httpstatus::OK->value);
            }
        }
        $this->likeRepo->create($userId , $post->id , 'like');

        $owner = $post->user;
        $owner->notify(new PostLikedNotification($liker , $post));

        return response()->json(["message" => "Liked"], Httpstatus::OK->value);
    }
    public function unlike(Post $post){

        $userId = auth('api')->id();
        if(!$userId){
            return response()->json(["message" => "User not authenticated"], Httpstatus::OK->value);
        }
        $like = $this->likeRepo->find($userId , $post->id);

        if($like){
            if($like->like_status === 'unlike'){
                $like->likeRepo->delete($like);
                return response()->json(["message" => "Unliked"], Httpstatus::OK->value);
            }
            else{
                $like->likeRepo->update(['like_status' => 'unlike']);
                return response()->json(["message" => "Unliked"], Httpstatus::OK->value);
            }

        }
        $this->likeRepo->create($userId , $post->id , 'unlike');
        return response()->json(["message" => "Unliked"], Httpstatus::OK->value);
    }
}
