<?php

namespace App\Http\Controllers\Api;

use App\Enums\HttpStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCommentRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Repositories\CommentRepository;
use app\Services\Loggerservice;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    protected $commentRepo;

    public function __construct()
    {
        $this->commentRepo = new CommentRepository();
    }

    public function index()
    {
        $comments = $this->commentRepo->paginate();
        return response()->json($comments);

    }

    public function show($id)
    {
        $comment = $this->commentRepo->find($id);
        if (!$comment) {
            Loggerservice::getLogger()->log("Comment Not Found");
            return response()->json(['message' => 'Comment not found'],HttpStatus::NOT_FOUND->value);
        }
        return response()->json($comment);
    }
    public function store(StoreCommentRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = auth()->id();
        $data['post_id'] = $request->route('post_id');

        $comment = $this->commentRepo->create($data);
        Loggerservice::getLogger()->log("Comment Created Successfully");
        return response()->json($comment, HttpStatus::CREATED->value);
    }

    public function update(UpdatePostRequest $request, $id)
    {
        $data = $request->validated();

        $updated = $this->commentRepo->update($id, $data);

        if (!$updated) {
            return response()->json(['message' => 'Comment not found or update failed'], HttpStatus::NOT_FOUND->value);
        }
        Loggerservice::getLogger()->log("Comment Updated Successfully");
        return response()->json(['message' => 'Comment updated successfully']);
    }

    public function destroy($id)
    {
        $deleted = $this->commentRepo->delete($id);

        if (!$deleted) {
            return response()->json(['message' => 'Comment not found or delete failed'], HttpStatus::NOT_FOUND->value);
        }
        Loggerservice::getLogger()->log("Comment Deleted successfully");
        return response()->json(['message' => 'Comment deleted successfully']);
    }
}
