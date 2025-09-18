<?php

namespace App\Repositories;

use App\Models\Comment;

class CommentRepository implements CommentRepositoryInterface
{
    public function all()
    {
        return Comment::all();
    }

    public function find($id)
    {
        return Comment::find($id);
    }

    public function create(array $data): Comment
    {
        return Comment::create($data);
    }

    public function update($id, array $data): bool
    {
        $comment = $this->find($id);
        if ($comment) {
            return $comment->update($data);
        }
        return false;
    }

    public function delete($id): bool
    {
        $comment = $this->find($id);
        if ($comment) {
            return $comment->delete();
        }
        return false;
    }
}
