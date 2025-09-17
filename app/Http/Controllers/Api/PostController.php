<?php

namespace App\Http\Controllers\Api;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use App\Repositories\PostRepository;

class PostController extends Controller
{

    protected $postRepo;

    public function __construct(PostRepositoryInterface $postRepo)
    {
        $this->postRepo = $postRepo;
    }
    public function index(){

        $posts = $this->postRepo->All();
        return response()->json($posts , 200);
    }
    public function store(Request $request){

        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'title' => 'required|string|max:255',
            'content' => 'required|string|max:255'
        ]);

        $validated['slug'] = Str::slug($validated['title']);

        $id =$this->postRepo->create($validated);

        $post =$this->postRepo->find($id);
        return response()->json([
            'message'=> 'post created successfully',
            'post' => $post,
        ],201);

    }
    public function show($id){
        $post = $this->postRepo->find($id);

        if(!$post){
            return response()->json(['message'=> 'post not found',],404);
        }
        return response()->json($post);

    }
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'title' => 'required|string|max:60',
            'content' => 'required|string',
        ]);

        $post = $this->postRepo->find($id);

        if (!$post) {
            return response()->json(['message' => 'post not found'], 404);
        }

        if (isset($validated['title'])) {
            $validated['slug'] = Str::slug($validated['title']);
        }

        $this->postRepo->update($id, $validated);

        $post = $this->postRepo->find($id);

        return response()->json([
            'message' => 'post updated successfully',
            'post' => $post,
        ]);
    }

    public function destroy($id){

        $post = $this->postRepo->find($id);

        if(!$post){
            return response()->json(['message'=> 'post not found'],404);
        }
        $this->postRepo->delete($id);
        return response()->json(['message'=> 'post deleted successfully']);
    }

}
