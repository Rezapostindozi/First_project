<?php
namespace App\Repositories;




use App\Enums\HttpStatus;
use App\Models\Like;
use Illuminate\Support\Facades\Cache;

class LikeRepository
{
    protected $like;

    public function __construct(Like $like)
    {
        $this->like = $like;
    }
    public function create($like ,int $userId, int $postId , string $status)
    {
        $like->create([
            "user_id" => $userId,
            "post_id" => $postId,
            "status" => $status
        ]);
        Cache::tags(['posts', "post:{$postId}"])->flush();

        return $like;
    }

    public function update ($like , string $status)
    {
        $like->update(['like_status' => $status]);

        Cache::tags(['posts', "post:{$like->post_id}"])->flush();

        return $like;
    }
    public function delete ($like)
    {
        $postId = $like->post_id;
        $like->delete();
        Cache::tags(['posts', "post:{$postId}"])->flush();
        return $like;
    }

    public function find($like,int $userId , int $postId)
    {
        return $like->where('user_id',$userId)
            ->where('post_id',$postId)
            ->first();
    }

}
