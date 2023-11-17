<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    //membuat fitur Register
    public function register(Request $request){
        $input = [
            'name' => $request -> name,
            'email' => $request -> email,
            'password' => Hash::make($request->password)
        ];
    
        //menginsert data ke table user
        $user = user::create($input);

        $data = [
            'message' => 'user is created successfully',
            'data' => $user
        ];

        //mengirim react json
        return response()->json($data,200);
    }

    public function login(Request $request){
        //menamgkap input user
        $input = [
            'email' => $request -> email,
            'password'=> $request -> password
        ];

        //mengambil data user 
        $user = User::where('email', $input['email'])->first();

        //membandingkan input user dengan data user
        $isLoginSuccessfully = (
            $input['email'] == $user->email
            &&
            Hash::check($input['password'], $user->password)
        );

        if($isLoginSuccessfully){
            $token = $user->createToken('auth_token');

            $data = [
                'message' => 'Login Successfully',
                'token' => $token->plainTextToken
            ];

            return response()->json($data,200);

        } else {
            $data = [
                'message' => 'Username or Password is wrong'
            ];
            
            return response()->json($data,401);
        }

    }
}