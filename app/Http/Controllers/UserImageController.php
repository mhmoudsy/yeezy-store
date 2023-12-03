<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;


class UserImageController extends Controller
{
    public function store(Request $request){
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'user_id'=>'required|exists:users,id'
        ]);
        if(UserImage::where('user_id',request('user_id'))->exists()){
            return response()->json([
                'message' => 'Image Already Exists'],200);
        }
            if($request->hasFile('image')){
                $image=$request->file('image');
                $imageName=$image->getClientOriginalName().'.'.time();
                Storage::disk('public')->put('images', $image);
             
        
               
           UserImage::create([
                'image' => $imageName,
                'user_id' => request('user_id'),
            ]);
            
           }
           return response()->json([
            'message' => 'Image Uploaded Successfully'],200);
           
}
      public function updateProfileImage(Request $request){
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'user_id'=>'required|exists:users,id'
        ]);
        $user=User::find(request('user_id'))->userImages->image;
        if(Storage::disk('public')->exists('images/'.UserImage::where('user_id',request('user_id'))->first()->image)){

            if($request->hasFile('image')){
                unlink(storage_path('app/public/images/'.$user));
                $image=$request->file('image');
                $imageName=$image->getClientOriginalName();
                Storage::disk('public')->putFileAs('images', $image,$imageName);
    
          }
            UserImage::where('user_id',request('user_id'))->update([
                'image' => $imageName,
                'user_id' => request('user_id'), 
            ]);
        
        }else{
      
            if($request->hasFile('image')){
                $image=$request->file('image');
                $imageName=$image->getClientOriginalName();
                Storage::disk('public')->putFileAs('images', $image,$imageName);
    
          }
          $path = Storage::disk('public')->putFile('images', $request->file('image'));
          $url = Storage::disk('public')->url($path);


            UserImage::where('user_id',request('user_id'))->update([
                'image' => $url,
                'user_id' => request('user_id'), 
            
      
            ]);
        }
        

        return response()->json([
            'message' => 'Image Updated Successfully',
         ],200);
    }
}
