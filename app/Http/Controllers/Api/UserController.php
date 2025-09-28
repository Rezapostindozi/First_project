<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use App\Services\Loggerservice;
use Illuminate\Http\Request;
use App\Models\User;
use App\Enums\HttpStatus;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;

class UserController extends Controller
{
    protected $userRepo;

    public function __construct()
    {
        $this->userRepo = new UserRepository(); ;
    }
    public function index(){

        $users = $this->userRepo->paginate();
        return response()->json($users , HttpStatus::OK->value);

    }
    public function store(StoreUserRequest $request){


        $validated = $request->validated();
        $id = $this->userRepo->create($validated);
        $user = $this->userRepo->find($id);
        Loggerservice::getLogger()->log("User Created Successfully");


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
            Loggerservice::getLogger()->log("User Not Found");
            return response()->json(['message' => 'User Not Found'], HttpStatus::NOT_FOUND->value );
        }
        $validated = $request->validated();
        $updated = $this->userRepo->update($validated, $id);
        if(!$updated){
            return response()->json(['message' => 'user not found'], HttpStatus::NOT_FOUND->value );
        }
        $user = $this->userRepo->find($id);

        Loggerservice::getLogger()->log("User Updated Successfully");

        return response()->json([
            'message'=> 'User Updated Successfully',
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
        Loggerservice::getLogger()->log("User Deleted Successfully");
        return response()->json(['message' => 'user deleted successfully'], HttpStatus::OK->value);


    }

}
