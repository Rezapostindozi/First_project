<?php

namespace App\Repositories;

use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class PostRepository
{
    protected $table = 'posts';


    public function paginate($prepage = 10  ,$page =1)
    {
        $cacheKey = "posts.paginate.page.{$page}.prepage.{$prepage}";

        return Cache::remember($cacheKey, 6000, function () use ($prepage, $page) {
            return DB::table($this->table)->paginate($prepage);

        });
    }

    public function all()
    {
        $cacheKey = 'posts.all';

        return Cache::remember($cacheKey , 600 , function() {
            return DB::table($this->table)->get();
        });
    }

    public function find($id)
    {
        $cacheKey= "posts.{$id}";
        return Cache::remember($cacheKey , 600, function () use ($id){
            return DB::table($this->table)->where('id', $id)->first();

        });
    }

    public function create(array $data)
    {
        $data['user_id'] = 3;
        $id = DB::table($this->table)->insertGetId($data);
        Cache::forget("posts.all");
        return $id;
    }

    public function update( array $data , $id)
    {
        $updated = DB::table($this->table)->where('id', $id)->update($data);

        if($updated){
            Cache::forget("posts.all");
            Cache::forget("posts.{$id}");
        }
        return $updated;
    }

    public function delete($id)
    {
       $deleted = DB::table($this->table)->where('id', $id)->delete();
       if($deleted){
           Cache::forget("posts.all");
           Cache::forget("posts.{$id}");
       }
       return $deleted;
    }
}
