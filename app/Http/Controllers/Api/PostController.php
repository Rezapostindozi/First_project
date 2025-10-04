<?php

namespace App\Http\Controllers\Api;

use App\Enums\HttpStatus;
use App\Services\Loggerservice;
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

    public function __construct()
    {
        $this->postRepo = new PostRepository();
    }

    public function index()
    {

        $posts = $this->postRepo->paginate();
        return response()->json($posts, HttpStatus::OK->value);
    }

    public function store(StorePostRequest $request)
    {

        $validated = $request->validated();
        $validated['slug'] = Str::slug($validated['title']);
        $id = $this->postRepo->create($validated);
        $post = $this->postRepo->find($id);
        LoggerService::getLogger()->log("Created post Successfully $id");

        return response()->json([
            'message' => 'post created successfully',
            'post' => $post,
        ], HttpStatus::CREATED->value);

    }

    public function show($id)
    {
        $post = $this->postRepo->find($id);

        if (!$post) {
            return response()->json(['message' => 'post not found',], HttpStatus::NOT_FOUND->value);
        }
        return response()->json($post);

    }

    public function update(UpdatePostRequest $request, $id)
    {
        $validated = $request->validated();

        $post = $this->postRepo->find($id);

        if (!$post) {
            return response()->json(['message' => 'post not found'], HttpStatus::NOT_FOUND->value);
        }

        if (isset($validated['title'])) {
            $validated['slug'] = Str::slug($validated['title']);
        }

        $this->postRepo->update($id, $validated);

        $post = $this->postRepo->find($id);
        LoggerService::getLogger()->log("Update post Successfully $id");


        return response()->json([
            'message' => 'post updated successfully',
            'post' => $post,
            HttpStatus::OK->value,
        ]);
    }

    public function destroy($id)
    {

        $post = $this->postRepo->find($id);

        if (!$post) {
            return response()->json(['message' => 'post not found'], HttpStatus::NOT_FOUND->value);
        }
        LoggerService::getLogger()->log("Deleted post Successfully $id");
        $this->postRepo->delete($id);
        return response()->json(['message' => 'post deleted successfully'], HttpStatus::NO_CONTENT->value);
    }

}
