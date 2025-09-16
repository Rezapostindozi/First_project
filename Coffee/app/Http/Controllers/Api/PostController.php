<?php

namespace App\Http\Controllers\Api;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index(){

        return response()->json(Post::all(),200);

    }
    public function store(Request $request){

        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'title' => 'required|string|max:255',
            'content' => 'required|string|max:255'
        ]);

        $validated['slug'] = Str::slug($validated['title']);

        $post =Post::create($validated);
        return response()->json([
            'message'=> 'post created successfully',
            'post' => $post,
        ],201);

    }
    public function show($id){
        $post = Post::find($id);
        if(!$post){
            return response()->json(['message'=> 'post not found',],404);
        }
        return response()->json($post);

    }

    public function update(Request $request, $id){

        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'title' => 'required|string|max:60',
            'content' => 'required|string'
        ]);

        $post = Post::find($id);

        if(!$post){

            return response()->json(['message'=> 'post not found'],404);
        }

        if (isset($validated['title'])) {
            $validated['slug'] = Str::slug($validated['title']);}

        $post->update($validated);
        return response()->json(['message'=> 'post updated successfully','post' => $post]);

    }

    public function destroy($id){
        $post = Post::find($id);

        if(!$post){
            return response()->json(['message'=> 'post not found'],404);
        }
        $post->delete();

        return response()->json(['message'=> 'post deleted successfully']);
    }

}
