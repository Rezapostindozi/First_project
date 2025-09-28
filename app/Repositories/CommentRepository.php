<?php

namespace App\Repositories;

use App\Models\Comment;
use function Laravel\Prompts\table;
use Illuminate\Support\Facades\DB;

class CommentRepository
{

    protected $table = 'comments';
    public  function  paginate($prepage = 10)
    {
        return DB::table($this->table)->paginate($prepage);
    }
    public function all()
    {
        return DB::table($this->table)->get();
    }

    public function find($id)
    {
        return DB::table($this->table)->where("id", $id)->first();
    }

    public function create(array $data)
    {
        return DB::table($this->table)->insertGetId($data);
    }

    public function update($id, array $data): bool
    {
        return DB::table($this->table)->where("id", $id)->update($data);
    }

    public function delete($id): bool
    {
        return DB::table($this->table)->where("id", $id)->delete();
    }
}
