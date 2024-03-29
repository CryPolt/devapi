<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Providers\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function register(Request $request){
        $fields=$request->validate([
            'name'=>'required|string',
            'email'=>'required|string|unique:users,email',
            'password'=>'required|string|confirmed'
        ]);

        $user = User::create([
            'name'=>$fields['name'],
            'email'=>$fields['email'],
            'password'=>bcrypt($fields['password'])

        ]);

        $token=$user->createToken('myapptoken')->plainTextToken;

        $response=[
            'user'=>$user,
            'token'=>$token

        ];
        return response($response,201);
    }


    public function logout(Request $request) {
        if ($request->user()) {
            $user = User::find($request->user()->id);
            $user->tokens()->delete();
            return ['message' => 'Logged out'];
        } else {
            return ['message' => 'No authenticated user found'];
        }
    }

    public function show($id){
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'status' => 'user',
                'message' => 'user not found'
            ], 404);
        }

        return response()->json($user,200);

    }



}
