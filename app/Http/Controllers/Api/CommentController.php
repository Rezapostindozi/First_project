<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\CommentRepositoryInterface;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    protected $commentRepo;

    public function __construct(CommentRepositoryInterface $commentRepo)
    {
        $this->commentRepo = $commentRepo;
    }

    public function index()
    {
        $comments = $this->commentRepo->all();
        return response()->json($comments);
    }

    public function show($id)
    {
        $comment = $this->commentRepo->find($id);
        if (!$comment) {
            return response()->json(['message' => 'Comment not found'], 404);
        }
        return response()->json($comment);
    }
    public function store(Request $request)
    {
        $data = $request->validate([
            'content' => 'required|string',
            'parent_id' => 'nullable|exists:comments,id',
            'approved_at' => 'nullable|date',
        ]);

        $data['user_id'] = auth()->id();

        $data['post_id'] = $request->route('post_id');

        $comment = $this->commentRepo->create($data);
        return response()->json($comment, 201);
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'content' => 'sometimes|string',
            'approved_at' => 'nullable|date',
        ]);

        $updated = $this->commentRepo->update($id, $data);

        if (!$updated) {
            return response()->json(['message' => 'Comment not found or update failed'], 404);
        }

        return response()->json(['message' => 'Comment updated successfully']);
    }

    public function destroy($id)
    {
        $deleted = $this->commentRepo->delete($id);

        if (!$deleted) {
            return response()->json(['message' => 'Comment not found or delete failed'], 404);
        }

        return response()->json(['message' => 'Comment deleted successfully']);
    }
}
