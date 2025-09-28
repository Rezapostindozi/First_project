<?php

namespace App\Repositories;

use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PostRepository
{
    protected $table = 'posts';


    public function paginate($prepage = 10)
    {
        return DB::table($this->table)->paginate($prepage);
    }

    public function all()
    {
        return DB::table($this->table)->get();
    }

    public function find($id)
    {
        return DB::table($this->table)->where('id', $id)->first();
    }

    public function create(array $data)
    {
        $data['user_id'] = 3;
        return DB::table($this->table)->insertGetId($data);
    }

    public function update( array $data , $id)
    {
        return DB::table($this->table)->where('id', $id)->update($data);
    }

    public function delete($id)
    {
        return DB::table($this->table)->where('id', $id)->delete();
    }
}
