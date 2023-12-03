<?php

namespace App\Http\Controllers;

use App\Models\Address;
use Illuminate\Http\Request;

class AddressController extends Controller
{
   public Function getAddresses(){
       $addresses=Address::all();
      return response()->json([
        "message"=>"all addresses",
        "addresses"=>$addresses,
      ]

    );
   }
   static public Function addAddress(Request $request){

   $validate=$request->validate([
        'country'=>'required|string',
        'city'=>'required|string',
        'address_details'=>'required|string',
    ]);
    if(auth()->user()->address){
        return response()->json([
            "message"=>"you have already address",
        ]);
    }
    $validate['user_id']=auth()->user()->id;
    $address = Address::create($validate);

  
    return response()->json([
        "message"=>"address added successfully",
    
    ]);
   }
   public Function updateAddress(Request $request){
    $user=auth()->user();
    $address=Address::where('user_id',$user->id)->first();
    $validatedData=$request->validate([
        'country'=>'required|string',
        'city'=>'required|string',
        'address_details'=>'required',
    ]);
    if($validatedData){
        if(isset($validatedData['country'])){
            $address->country =$validatedData['country'];
        } 
         if(isset($validatedData['city'])){
            $address->city =$validatedData['city'];
        } 
         if(isset($validatedData['address_details'])){
            $address->address_details =$validatedData['address_details'];
        }
        $address->save();
        return response()->json([
            "message"=>"address updated successfully",
            "address"=>$user->address,
        ]);

    }
   }

}
