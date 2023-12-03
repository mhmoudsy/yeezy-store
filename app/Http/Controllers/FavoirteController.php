<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use App\Models\Product;
use Illuminate\Http\Request;

class FavoirteController extends Controller
{
    public function getFavorite(){
        $user_id = auth()->user()->id;
        $favorites = Favorite::where('user_id', $user_id)->get();
        $products = [];
     
        foreach($favorites as $favorite){
            $product = Product::find($favorite->product_id);
            $product['in_favorite']=false;
            $product['in_cart']=false;
            $product['sizes']=Product::find($favorite->product_id)->sizes;
        
            array_push($products, $product);
        }
        return response()->json([
            'status'=>true,
            'message' => 'Favorite list',
            'data'=>$products,
        ], 200);
    }
    public function addOrDeleteFavoirte(Request $requset){
        $requset->validate([
            'product_id' => 'required',
        ]);
       
        $product_id = $requset->product_id;
        $user_id = $requset->user()->id;
   
        $favorite = Favorite::where('user_id', $user_id)->where('product_id', $product_id)->first();
        if($favorite){
            $favorite->delete();

    
            return response()->json(
                [   
                    'status'=>true,
                    'message' => 'Product removed from favorite list',
                    'data'=>[
                        'id'=>$favorite->id,
                        'product'=>[
                        'product_id'=>Product::find($product_id)->id,
                        'product_name'=>Product::find($product_id)->product_name,
                        'product_price'=>Product::find($product_id)->product_price,
                        'product_image'=>Product::find($product_id)->product_image,
                        ]
                    ]
                    
                ], 200);
        }else{
            $favorite = new Favorite();
            $favorite->user_id = $user_id;
            $favorite->product_id = $product_id;

                        //make parameter in_favorite = true

            $favorite->save();  
            return response()->json([
                'status'=>true,
                'message' => 'Product added to favorite list',
                'data'=>[
                    'id'=>$favorite->id,
                    'product'=>[
                    'product_id'=>Product::find($product_id)->id,
                    'product_name'=>Product::find($product_id)->product_name,
                    'product_price'=>Product::find($product_id)->product_price,
                    'product_image'=>Product::find($product_id)->product_image,
                    ]
                ]
            ], 200);
        }

        
        

        
    }
}
