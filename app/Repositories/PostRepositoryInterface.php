<?php
namespace App\Repositories;

interface PostRepositoryInterface
{
    public function all();
    public function find($id);
    public function create(array $date);
    public function update(array $date, $id);
    public function delete($id);
}
