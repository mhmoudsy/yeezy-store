<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public Function getCarts(){
        $user_id = auth()->user()->id;
        $carts = Cart::where('user_id', $user_id)->get();
        $products = [];
        $sub_total = 0;
        $total = 0;
        $quentaty = 1;
        $size=[];
        $cartId=[];
        foreach($carts as $cart){
            array_push($cartId,$cart->product_id);
        }
        $quentaty=1;
    
        foreach($carts as $cart){
            $product = Product::find($cart->product_id);
            // $size = $product->sizes;
            $product['in_favorite']=false;
            $product['in_cart']=false;
            if(in_array($product->id,$cartId)){
                $product['in_cart']=true;
            }
    
            $sub_total += $product->product_price;
            $total += $product->product_price;
            array_push($products, $product);
        }
        foreach($products as $product){
            $product['quentaty']=1;
        }
        
        return response()->json([
            'status'=>true,
            'message' => 'Cart list',
            'product'=>$products,
            'sub_total'=>$sub_total,
            'total'=>$total,
        ], 200);
    }


    public function addOrDeleteCart(Request $requset){
        $requset->validate([
            'product_id' => 'required',
        ]);
       
        $product_id = $requset->product_id;
        $user_id = $requset->user()->id;
   
        $cart = Cart::where('user_id', $user_id)->where('product_id', $product_id)->first();
        if($cart){
            $cart->delete();

    
            return response()->json(
                [   
                    'status'=>true,
                    'message' => 'Product removed from Cart list',
                    'data'=>[
                        'id'=>$cart->id,
                        'product'=>[
                        'product_id'=>Product::find($product_id)->id,
                        'product_name'=>Product::find($product_id)->product_name,
                        'product_price'=>Product::find($product_id)->product_price,
                        'product_image'=>Product::find($product_id)->product_image,
                        ]
                    ]
                    
                ], 200);
        }else{
            $cart = new Cart();
            $cart->user_id = $user_id;
            $cart->product_id = $product_id;

                        //make parameter in_favorite = true

            $cart->save();  
            return response()->json([
                'status'=>true,
                'message' => 'Product added to Cart list',
                'data'=>[
                    'id'=>$cart->id,
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
