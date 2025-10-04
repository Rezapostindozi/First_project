<?php

namespace App\Repositories;

use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class PostRepository
{
    protected $table = 'posts';


    public function paginate($perPage = 10  ,$page =1)
    {
        $cacheKey = "posts.paginate.page_{$page}.perpage_{$perPage}";
        $TTL = 10 ;

        return Cache::remember($cacheKey, $TTL , function () use ($perPage, $page) {
            return DB::table($this->table)->paginate($perPage, ['*'], 'page', $page);

        });
    }

    public function all()
    {
        $cacheKey = 'posts.all';
        $TTL = 10 ;

        return Cache::remember($cacheKey , $TTL  , function() {
            return DB::table($this->table)->get();
        });
    }

    public function find($id)
    {
        $cacheKey= "posts.{$id}";
        $TTL = 10 ;

        return Cache::remember($cacheKey , $TTL , function () use ($id){
            return DB::table($this->table)->where('id', $id)->first();

        });
    }

    public function create(array $data)
    {
        $data['user_id'] = 3;
        $id = DB::table($this->table)->insertGetId($data);

        Cache::forget("posts.all");

        for ($page = 1; $page <= 10; $page++) {
            Cache::forget("posts.paginate.page_{$page}.perPage_10");
        }

        $post = DB::table($this->table)->where("id", $id)->first();

        Cache::put("posts.{$id}", $post, now()->addMinutes(10));

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
