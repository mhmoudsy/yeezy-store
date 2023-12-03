<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{

    public function login(Request $request){
        $users=User::all();
       $credentials = $request->validate([
            'email'=>'nullable',
            'phone_number'=>'nullable',
            'password'=>'required',
        ]);
        
        if(Auth::attempt($credentials)){
            // user()-->get user who make request
            $user=$request->user();
            //kol mara y3ml login y3ml token gded w yms7 el token el 2dema
            $request->user()->tokens()->delete();
            $token=$user->createToken('authToken')->plainTextToken;
    
            return response()->json([
                'status'=>True, // 'status'=>'failed
                'message'=>'login successfully',
                'users'=>$user->load('address'),
                'token'=>$token,
            ],200);
        }
        else{
            if($users->where('email',$request->email)->count()==0||$users->where('password',$request->password)->count()==0){
                return response()->json([
                    'status'=>False, // 'status'=>'failed
                    'message'=>'login failed|email or password is incorrect',
                    'users'=>null,
                 
                ],400);
            }
     
        }
    }

}
