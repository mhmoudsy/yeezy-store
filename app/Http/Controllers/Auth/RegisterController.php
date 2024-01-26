<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\AddressController;
use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function createUser(Request $request){
        $users=User::all();

        $user=$request->validate([
            'username'=>'required|string|max:255',
            'email'=>'required| string| email',
            'password'=>'required| string| min:8 |confirmed',
            'phone_number'=>'required | string| between:10,13',
            'image'=>'nullable|string',
        ]);
        $user['password'] = Hash::make($user['password']);
      
         if($request->image==null){
            $user['image']="https://upload.najd-products.com/storage/images/profile-image.jpg";
        }

  
        foreach($users as $userX){
            if($userX->email==$request->email){
                return response()->json([
                    'message'=>'email already exists',
                    'user'=>null,
                    'token'=>null,
                ],400   );
            }
            if($userX->phone_number==$request->phone_number){
                return response()->json([
                    'message'=>'phone number already exists',
                    'user'=>null,
                    'token'=>null,
                ],400   );
            }
           
        }
        $newUser = User::create($user);

     
            
        Address::create(
            [
                'country'=>'unknown',
                'city'=>'unknown',
                'address_details'=>'unknown',
                'user_id'=>$newUser->id,
            ]
        );
   

        $token=$newUser->createToken('authToken')->plainTextToken;

        
        // User::find($newUser->id)->userImages()->create([
        //     'image'=>'https://i.postimg.cc/6QfwWwP6/profile-image.jpg',
        //     'user_id'=>$newUser->id,
        // ]);
        // 'image'=>User::find($newUser->id)->userImages->image??null,


        
        return response()->json([
            'message'=>'user created successfully',
            'user'=>$newUser,
            'token'=>$token,
        ],200);
      
    }
    }
