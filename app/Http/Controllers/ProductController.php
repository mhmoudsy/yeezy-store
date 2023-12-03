<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Category;
use App\Models\Favorite;
use App\Models\Product;
use App\Models\ProductSize;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function allProduct(){
        $products=Product::all();
        $productId=[];
        foreach($products as $product){
            array_push($productId,$product->id);
        }
    
        $favorites=Favorite::where('user_id',auth()->user()->id)->get();
        $favoriteId=[];
         $carts=Cart::where('user_id',auth()->user()->id)->get();
        $cartId=[];

        foreach($favorites as $favorite){
            array_push($favoriteId,$favorite->product_id);
        }
        foreach($carts as $cart){
            array_push($cartId,$cart->product_id);
        }

        $products=Product::whereIn('id',$productId)->get();
        foreach($products as $product){
            $product['in_favorite']=false;
            $product['in_cart']=false;
            $product['quantity']=1;
            if(in_array($product->id,$favoriteId)){
                $product['in_favorite']=true;
            }
            else{
                $product['in_favorite']=false;
            } 
              if(in_array($product->id,$cartId)){
                $product['in_cart']=true;
            }
            else{
                $product['in_cart']=false;
            }

        }

     


    



            return response()->json([
                'message'=>'success',
                'products'=>$products->load('sizes'),

            ]);
       
      
       
    }
    public function StoreProduct(Request $request){
       $product= $request->validate([
            'product_name'=>'required',
            'product_description'=>'required|min:10,',
            'product_price'=>'required',
            'product_image'=>'required|image',
            'category_id'=>'required'
        ]);
        if($request->hasFile('product_image')){
            $image=$request->file('product_image');
            $imageName=$image->getClientOriginalName();
            Storage::disk('public')->putFileAs('product_image', $image,$imageName);
            $path = Storage::disk('public')->putFile('product_image', $request->file('product_image'));
            $url = Storage::disk('public')->url($path);
            $product['product_image']=$url;
         
         

      }
        $products=Product::create($product);
        return response()->json([
            'message'=>'success',
            'products'=>$products
        ]);

        
    }
    public function DeleteProduct(String $id){
        $product=Product::find($id);
        if($product){
            $product->delete();
            return response()->json([
                'message'=>'success delete product',
            ]);
        }
        else{
            return response()->json([
                'message'=>'product not found',
            ]);
        }
    }
    public function SearchProduct(Request $request){
        $request->validate([
            'text'=>'nullable'
        ]); 
        if($request->text==null){
            return response()->json([
                'message'=>'success',
                'products'=>[]
            ]);
        }
          if($request->has('text')){
            $product=Product::where('product_name','like','%'.$request->text.'%')->orWhere('product_description','like','%'.$request->text.'%')->orWhere('product_price','like','%'.$request->text.'%')->get();
            foreach($product as $products){
                $products['in_favorite']=false;
                $products['in_cart']=false;
                $products['quantity']=1;
            }
            if(Product::where('product_name','like','%'.$request->text.'%')->orWhere('product_description','like','%'.$request->text.'%')->orWhere('product_price','like','%'.$request->text.'%')->count()==0){
                return response()->json([
                    'message'=>'Product not found',
                    'status'=>false,
                    'products'=>[]
                ]);
            }
       


        }
        
    
    
        if($request->has('text') ){
            return response()->json([
                'message'=>'success',
                'status'=>true,

                'products'=>$product
            ]);
        }
    
    
    }
}
