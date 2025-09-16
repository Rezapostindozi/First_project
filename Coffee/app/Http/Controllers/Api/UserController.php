<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function index(){

        return response()->json(User::all(), 200);

    }
    public function store(Request $request){

        $validated = $request->validate([
            'username' => 'required|string|unique:users',
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'required|email|unique:users',
            'phone' => 'nullable|string',
            'password' => 'required|string',
            'bio' => 'nullable|string',
            'avatar_url' => 'nullable|url',
            'is_active' => 'boolean',
        ]);
        $validated['password'] = bcrypt($validated['password']);


        $user = User::create($validated);
        return response()->json([
            'message'=> 'user created successfully',
            'user' => $user,
        ],200 );

    }

    public function show($id){
        $user = User::find($id);

        if(!$user){
            return response()->json(['message' => 'user not found'], 404);
        }

        return response()->json($user);
    }

    public function update(Request $request, $id){
        $user =User::find($id);

        if(!$user){
            return response()->json(['message' => 'user not found'], 404);

        }
        $validated = $request->validate([
            'username' => 'required|string|unique:users',
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'required|email|unique:users',
            'phone' => 'nullable|string',
            'password' => 'required|string',
            'bio' => 'nullable|string',
            'avatar_url' => 'nullable|url',
            'is_active' => 'boolean',
        ]);

        if(!empty(($validated['password']))){
        $validated = ['password' => bcrypt($validated['password'])];
        }
        else{
            unset($validated['password']);
        }
        $user->update($validated);

        return response()->json([
            'message'=> 'user updated successfully',
            'date'=> $user,
        ]);

    }

    public function destroy($id){

        $user = User::find($id);

        if(!$user){
            return response()->json(['message' => 'user not found'], 404);
        }
        $user->delete();

        return response()->json(['message' => 'user deleted successfully'], 200);


    }

}
