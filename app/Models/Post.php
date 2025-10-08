<?php

namespace App\Models;

use http\Encoding\Stream\Deflate;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = [
        'title',
        'content',
        ];

    public $timestamps = true;

    protected $casts = [

    ];

    public function likes()
    {
        return $this->hasMany(Like::class);
    }
    public function likesCount(){

        return $this->likes()->where('like_status', 'like')->count();
    }

    public function disLikesCount(){

        return $this->likes()->where('like_status', 'dislike')->count();

    }
    public function likedBy($user){
        return $this->likes()
            ->where('user_id', $user->id)
            ->where('like_status', 'like')
            ->exists();
    }
    public function disLikedBy($user){
        return $this->likes()
            ->where('user_id', $user->id)
            ->where('like_status', 'dislike')
            ->exists();
    }







}
