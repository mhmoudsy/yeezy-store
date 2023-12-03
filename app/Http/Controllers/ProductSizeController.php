<?php

namespace App\Http\Controllers;

use App\Models\ProductSize;
use Illuminate\Http\Request;

class ProductSizeController extends Controller
{

    public function allSize(){
        $sizes=ProductSize::all();
        return response()->json([
            'message'=>'success',
            'sizes'=>$sizes
        ]);
    }
    public function addSize(Request $request){
        $size= $request->validate([
            'size'=>'required',
            'product_id'=>'required'
        ]);
        $sizes=ProductSize::create($size);
        return response()->json([
            'message'=>'success',
            'sizes'=>$sizes
        ]);
    }
    public function deleteSize($id){
        $size=ProductSize::find($id);
        if($size){
            $size->delete();
            return response()->json([
                'message'=>'success delete size',
            ]);
        }
        else{
            return response()->json([
                'message'=>'size not found',
            ]);
        }
    }
}
