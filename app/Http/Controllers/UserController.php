<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function allUser(){
        $users=User::all();
        return response()->json([
            'message'=>'success',
            'users'=>$users->load('address'),
        ]);
    }
    public function userProfile(){
        $user=auth()->user();
        if($user){
            return response()->json([
                'status'=>true,
                'message'=>'success',
                'user'=>$user->load('address'),
            ]);
        }else{
        return response()->json([
            'status'=>false,
            'message'=>'failed',
            'user'=>null,
        ]);
    }
    }
    public function deleteUser(String $id){
        $user=User::find($id);
        if($user){
            $user->delete();
            return response()->json(
                [
                    'message' => 'User deleted successfully',
                    'user'=>$user,
            ]);

        }
        return response()->json(['message' => 'User not found'], 404);

    }
    public function getSpecificUser($id){
        $user=User::where('id',$id)->first();
        if($user){
            return response()->json([
                'message'=>'find user successfully',
                'user'=>$user
            ]);
        }else{
            return response()->json([
                'message'=>'find user failed',
            ]);
        }
    }
    public function userSearch(Request $request){
        $query=User::query();
        if($request->has('username')){
        //ay 7aga shkl el request el gay mwgwda fl username
           $query->where('username','like','%'.$request->username.'%');
        
        } 
        if($request->has('email')){
            $query->where('email','like','%'.$request->email.'%');
    
        }
        if($request->has('username') || $request->has('email')){
            $users=$query->get();
            return response()->json([
                'message'=>'success',
                'users'=>$users
            ]);
        }
    }
    public function updateUser(Request $request){
        $user=$request->user();
        $validatedData =$request->validate([
            'username'=>['nullable','string','min:2','max:30'],
            'email'=>['nullable','string','email',],
            'phone_number'=>['nullable','min:11','max:20',],
        ]);

       
      
      try{
            
        if($validatedData){
            if(isset($validatedData['username'])){
                $user->username =$validatedData['username'];
            }
            if(isset($validatedData['email'])){
                $user->email =$validatedData['email'];
            } 
          
               if(isset($validatedData['phone_number'])){
                if($validatedData['phone_number']==User::where('phone_number',$validatedData['phone_number'])->where('id','!=',$user->id)->first()){
                    return response()->json([
                        'message'=>'this phone number is already exist',
                    ]);
                }else{
                    $user->phone_number =$validatedData['phone_number'];

                }
            } 
        
    
          
            $user->save();
            return response()->json([
                'message'=>'update user successfully',
                'user'=>$user
            ]);
          }
           
      }catch(\Exception $e){
          return response()->json([
              'message'=>'The phone number has already been taken',
              'user'=>auth()->user(),
          ]);
      }
         
     
    }
    public function updatePassword(Request $request){
        $user=$request->user();
        $request->validate([
            'old_password'=>['required','min:8'],
            'password'=>['required','min:7','confirmed','string']
        ]);
        if(!Hash::check($request->old_password,$user->password)){
           return response()->json([
            'message'=>'the old password is incorrect'
           ],400 );
        }
        $user->password=Hash::make($request->password);
        $user->save();
        return response()->json([
            'message'=>'password updated successfully'
        ]);
    }
    public function updateProfileImage(Request $request){
        $user=$request->user();
        $request->validate([
            'image' => 'required|image|max:2048',
        ]);

        if($request->hasFile('image')){
            $image=$request->file('image');
            $imageName=$image->getClientOriginalName();
            Storage::disk('public')->putFileAs('images', $image,$imageName);
            $path = Storage::disk('public')->putFile('images', $request->file('image'));
            $url = Storage::disk('public')->url($path);

            $user->image=$path;
         
         

      }
            $user->update();

            return response()->json([
                'message' => 'Image Updated Successfully',
                'userImage'=>$user->image
             ],200);

  
}
}