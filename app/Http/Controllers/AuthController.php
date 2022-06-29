<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Factories\HasFactory ;
use Illuminate\Notifications\Notifiable; 
use App\Models\User;

class AuthController extends Controller 
{
    use HasApiTokens, HasFactory, Notifiable;

    public function regis (Request $request){
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|confirmed',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'tel' => 'required|max:10'
        ]);

        $user = User::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'password' => Hash::make($request['password']),
            'first_name' => $request['first_name'],
            'last_name' => $request['last_name'],
            'tel' => $request['tel'],
        ]);

        $token = $user->createToken('userToken')->plainTextToken;

        $response = [
            'user' => $user,
            'token' => $token
        ];

        return response($response , 201);
    }

    public function login (Request $request){
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email',$request['email'])->first();

        if(!$user || !Hash::check($request['password'], $user->password)){
            return response([
                'massage' => 'Wrong Username or Password!'
            ],401);
        }

        $token = $user->createToken('userToken')->plainTextToken;

        $response = [
            'user' => $user,
            'token' => $token
        ];

        return response($response , 201);
    }

    public function logout (Request $request){

        $request->user()->currentAccessToken()->delete();
        $response = [
            'massage' => 'Log out successful! Have a nice day~',
        ];

        return response($response);
    }
}
