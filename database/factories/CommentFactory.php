<?php
use App\Models\Comment;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CommentFactory extends Factory
{
    protected $model = Comment::class;

    public function definition(){
        return [
            'user_id' => auth()->id(),
            'post_id' => $request->route('post_id'),
            'comment' => $request->comment,
        ];
    }





}
