<?php

namespace App\Http\Controllers\Api;
use App\Enums\HttpStatus;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use App\Repositories\PostRepository;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
class PostController extends Controller
{

    protected $postRepo;

    public function __construct(PostRepositoryInterface $postRepo)
    {
        $this->postRepo = $postRepo;
    }
    public function index(){
        $prepage = 10;
        $posts = $this->postRepo->Paginate($prepage);
        return response()->json($posts , HttpStatus::OK->value);
    }
    public function store(Request $request){

        $validated = $request->validate();
        $validated['slug'] = Str::slug($validated['title']);
        $id =$this->postRepo->create($validated);
        $post =$this->postRepo->find($id);
        return response()->json([
            'message'=> 'post created successfully',
            'post' => $post,
        ],HttpStatus::CREATED->value);

    }
    public function show($id){
        $post = $this->postRepo->find($id);

        if(!$post){
            return response()->json(['message'=> 'post not found',],HttpStatus::NOT_FOUND->value);
        }
        return response()->json($post);

    }
    public function update(Request $request, $id)
    {
        $validated = $request->validate();

        $post = $this->postRepo->find($id);

        if (!$post) {
            return response()->json(['message' => 'post not found'], HttpStatus::NOT_FOUND->value);
        }

        if (isset($validated['title'])) {
            $validated['slug'] = Str::slug($validated['title']);
        }

        $this->postRepo->update($id, $validated);

        $post = $this->postRepo->find($id);

        return response()->json([
            'message' => 'post updated successfully',
            'post' => $post,
            HttpStatus::OK->value,
        ]);
    }

    public function destroy($id){

        $post = $this->postRepo->find($id);

        if(!$post){
            return response()->json(['message'=> 'post not found'],HttpStatus::NOT_FOUND->value);
        }
        $this->postRepo->delete($id);
        return response()->json(['message'=> 'post deleted successfully'], HttpStatus::NO_CONTENT->value);
    }

}
