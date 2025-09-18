<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Repositories\UserRepositoryInterface;


class UserController extends Controller
{
    protected $userRepo;

    public function __construct(UserRepositoryInterface $userRepo)
    {
        $this->userRepo = $userRepo;
    }
    public function index(){

        $users = $this->userRepo->all();
        return response()->json($users , 200);

    }
    public function store(Request $request){

        $validated = $request->validate([
            'username' => 'required|string|unique:users',
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'contery' => 'required|string',
            'email' => 'required|email|unique:users',
            'phone' => 'nullable|string',
            'password' => 'required|string',
            'bio' => 'nullable|string',
            'avatar_url' => 'nullable|url',
            'is_active' => 'boolean',
        ]);
        $id = $this->userRepo->create($validated);
        $user = $this->userRepo->find($id);


        return response()->json([
            'message'=> 'user created successfully',
            'user' => $user,
        ],201 );

    }

    public function show($id){
        $user = $this->userRepo->find($id);
        if(!$user){
            return response()->json(['message' => 'user not found'], 404);
        }

        return response()->json($user);
    }

    public function update(Request $request, $id){
        $user = $this->userRepo->find($id);
        if(!$user){
            return response()->json(['message' => 'user not found'], 404);

        }
        $validated = $request->validate([
            'username' => 'required|string|unique:users',
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'country' => 'required|string',
            'email' => 'required|email|unique:users',
            'phone' => 'nullable|string',
            'password' => 'required|string',
            'bio' => 'nullable|string',
            'avatar_url' => 'nullable|url',
            'is_active' => 'boolean',
        ]);
        $updated = $this->userRepo->update($validated, $id);
        if(!$updated){
            return response()->json(['message' => 'user not found'], 404);
        }
        $user = $this->userRepo->find($id);


        return response()->json([
            'message'=> 'user updated successfully',
            'date'=> $user,
        ]);

    }

    public function destroy($id){

        $user = $this->userRepo->find($id);
        if(!$user){
            return response()->json(['message' => 'user not found'], 404);
        }
        $this->userRepo->delete($id);

        return response()->json(['message' => 'user deleted successfully'], 200);


    }

}
