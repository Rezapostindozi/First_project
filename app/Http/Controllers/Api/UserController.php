<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Repositories\UserRepositoryInterface;
use App\Enums\HttpStatus;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;

class UserController extends Controller
{
    protected $userRepo;

    public function __construct(UserRepositoryInterface $userRepo)
    {
        $this->userRepo = $userRepo;
    }
    public function index(){

        $prepage = 10;
        $users = $this->userRepo->paginate($prepage);
        return response()->json($users , HttpStatus::OK->value);

    }
    public function store(StoreUserRequest $request){

        $validated = $request->validated();
        $id = $this->userRepo->create($validated);
        $user = $this->userRepo->find($id);


        return response()->json([
            'message'=> 'user created successfully',
            'user' => $user,
        ],HttpStatus::CREATED->value );

    }

    public function show($id){
        $user = $this->userRepo->find($id);
        if(!$user){
            return response()->json(['message' => 'user not found'], HttpStatus::NOT_FOUND->value );
        }

        return response()->json($user);
    }

    public function update(UpdateUserRequest $request, $id){
        $user = $this->userRepo->find($id);
        if(!$user){
            return response()->json(['message' => 'user not found'], HttpStatus::NOT_FOUND->value );

        }
        $validated = $request->validated();
        $updated = $this->userRepo->update($validated, $id);
        if(!$updated){
            return response()->json(['message' => 'user not found'], HttpStatus::NOT_FOUND->value );
        }
        $user = $this->userRepo->find($id);


        return response()->json([
            'message'=> 'user updated successfully',
            'date'=> $user,
            HttpStatus::OK->value,
        ]);

    }

    public function destroy($id){

        $user = $this->userRepo->find($id);
        if(!$user){
            return response()->json(['message' => 'user not found'], HttpStatus::NOT_FOUND->value );
        }
        $this->userRepo->delete($id);

        return response()->json(['message' => 'user deleted successfully'], HttpStatus::OK->value);


    }

}
