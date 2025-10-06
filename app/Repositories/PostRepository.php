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
        $cacheKey = "posts.paginate.page_{$page}.perPage_{$perPage}";
        $TTL = 10 ;

        return Cache::tags(['posts'])->remember($cacheKey, $TTL , function () use ($perPage, $page) {
            return DB::table($this->table)->paginate($perPage, ['*'], 'page', $page);

        });
    }

    public function all()
    {
        $cacheKey = 'posts.all';
        $TTL = 10 ;

        return Cache::tags(['posts'])->remember($cacheKey , $TTL  , function() {
            return DB::table($this->table)->get();
        });
    }

    public function find($id)
    {
        $cacheKey= "posts.{$id}";
        $TTL = 10 ;

        return Cache::tags(['posts', "post_{$id}"])->remember($cacheKey , $TTL , function () use ($id){
            return DB::table($this->table)->where('id', $id)->first();

        });
    }

    public function create(array $data)
    {
        $data['user_id'] = 3;
        $id = DB::table($this->table)->insertGetId($data);

        Cache::tags(["posts"])->flush();

        $post = DB::table($this->table)->where("id", $id)->first();

        Cache::tags(["posts" , "post_{$id}"])->put("post_{$id}", $post , now()->addMinutes(10));

        return $id;
    }


    public function update( array $data , $id)
    {
        $updated = DB::table($this->table)->where('id', $id)->update($data);

        if($updated){
            Cache::tags(["posts_{$id}"])->flush();
            Cache::tags(["posts"])->flush();
        }
        return $updated;
    }

    public function delete($id)
    {
       $deleted = DB::table($this->table)->where('id', $id)->delete();
       if($deleted){

           Cache::tags(["posts_{$id}"])->flush();
           Cache::tags(["posts"])->flush();
       }
       return $deleted;
    }
}
