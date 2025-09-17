<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserRepository implements UserRepositoryInterface
{
    protected $table = 'users';

    public function all()
    {
        return DB::table($this->table)->get();
    }
    public function find($id){

        return DB::table($this->table)->where('id', $id)->first();

    }
    public function create(array $data){
        if (isset($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        }
        return DB::table($this->table)->insert($data);
    }
    public function update(array $data, $id){

        if(isset($data['password'])){
            $data['password'] = bcrypt($data['password']);
        }
        return DB::table($this->table)->where('id', $id)->update($data);

    }
    public function delete($id){
        return DB::table($this->table)->where('id', $id)->delete();

    }
}
