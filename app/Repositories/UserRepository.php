<?php

namespace App\Repositories;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use mysql_xdevapi\Collection;

class UserRepository
{
    protected $table = 'users';


    public function paginate ($perPage = 10):LengthAwarePaginator
    {
        return DB::table($this->table)->Paginate($perPage);
    }

    public function all():Collection
    {
        return DB::table($this->table)->get();
    }
    public function find($id){

        return DB::table($this->table)->where('id', $id)->first();

    }
    public function create(array $data): ?object
    {
        if (isset($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        }
        return DB::table($this->table)->insertGetId($data);
    }
    public function update(array $data, $id): int
    {

        if(isset($data['password'])){
            $data['password'] = bcrypt($data['password']);
        }
        return DB::table($this->table)->where('id', $id)->update($data);

    }
    public function delete($id): int
    {
        return DB::table($this->table)->where('id', $id)->delete();

    }
}
