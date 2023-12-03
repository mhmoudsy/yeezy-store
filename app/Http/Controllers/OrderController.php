<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Favorite;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductSize;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class OrderController extends Controller
{
    //

    // public function test(){
    //     $orderTime=Order::find(32);
    //     $isDeliverd=false;
  
    //     $currentDateTime = Carbon::now();

    //     $daysDifference = $orderTime->created_at->diffInMinutes($currentDateTime);

    //     if($daysDifference>20){
    //         $isDeliverd=true;
            
    //     return response()->json([
    //         'status'=>true,
    //         'message' => $orderTime->created_at->diffForHumans(),
    //         'isDeliverd'=>$isDeliverd,

    //     ], 200);
    //     }

    //     return response()->json([
    //         'status'=>true,
    //         'message' => $orderTime->created_at->diffForHumans(),
    //         'isDeliverd'=>$isDeliverd,

    //     ], 200);
    // }

    public function SearchOrByCode(Request $request){
        $request->validate([
            'order_code' => 'required|string',
        ]);
        $user_id=auth()->user()->id;
        $orders=Order::where('user_id',$user_id)->get();

        $products=[];
        $total_price=0;
        foreach($orders as $order){
            $product=Product::find($order->product_id);
            $orderx=Order::find($order->id);

            unset($product['in_cart']);
            unset($product['in_favorite']);
            unset($product['category_id']);
            unset($product['created_at']);
            unset($product['updated_at']);
         
            $product['quantity']=$order->product_quentaty;
            $total_price=$product->product_price*$order->product_quentaty;
            $product['total_price']=$total_price;
            $product['order_address']=$order->address;
            $product['size']=$order->size;
            $product['order_code']=$order->order_code;            
            $product['order_created_at']=$order->created_at->format('d-M-Y h:i A');
            if($order->is_paid){
                $product['is_paid']=true;
            }else{
                $product['is_paid']=false;
            }
            $currentDateTime = Carbon::now();
            $daysDifference = $order->created_at->diffInDays($currentDateTime);
            $MinutesDifference = $order->created_at->diffInMinutes($currentDateTime);
            if($MinutesDifference>20){
                $orderx['is_preparing']=true;
                $orderx->save();
            }
            if($daysDifference>3){
                $orderx['is_deliverd']=true;
                $orderx->save();

            }
            if($daysDifference>1){
                $orderx['is_in_the_way']=true;
                $orderx->save();
            }
            if($order->is_in_the_way){
                $product['is_in_the_way']=true;
                }else{
                $product['is_in_the_way']=false;
                 } 
                   if($order->is_preparing){
                $product['is_preparing']=true;
                }else{
                $product['is_preparing']=false;
                 }
                 
                  if($order->is_deliverd){
                $product['is_delivered']=true;
                }else{
                $product['is_delivered']=false;
                 }

          
          

            array_push($products,$product);
        }
        $OrderCode=[];
        foreach($products as $product){
         
                array_push($OrderCode,$product['order_code']);
                
        }
        if(!in_array($request->order_code,$OrderCode)){
            return response()->json([
                'status'=>false,
                'message' => 'Order Code Not Found', 
            ], 404);
        }
       
        $productByCode=collect($products)->where('order_code',$request->order_code)->all();
        $pr=[];
        foreach($productByCode as $product){
            array_push($pr,$product);
        }
        return response()->json([
            'status'=>true,
            'message' => 'Order List By Code', 
            'products'=>$pr,
        ], 200);
    
       
    
    }
    public function getOrder(){
        $user_id=auth()->user()->id;
        $orders=Order::where('user_id',$user_id)->get();

        $products=[];
        $total_price=0;
    

      
 
        foreach($orders as $order){
            $product=Product::find($order->product_id);
            $orderx=Order::find($order->id);

            unset($product['in_cart']);
            unset($product['in_favorite']);
            unset($product['category_id']);
            unset($product['created_at']);
            unset($product['updated_at']);
         
            $product['quantity']=$order->product_quentaty;
            $total_price=$product->product_price*$order->product_quentaty;
            $product['total_price']=$total_price;
            $product['order_address']=$order->address;
            $product['size']=$order->size;
            $product['order_code']=$order->order_code;            
            $product['order_created_at']=$order->created_at->format('d-M-Y h:i A');
            if($order->is_paid){
                $product['is_paid']=true;
            }else{
                $product['is_paid']=false;
            }
            $currentDateTime = Carbon::now();
            $daysDifference = $order->created_at->diffInDays($currentDateTime);
            $MinutesDifference = $order->created_at->diffInMinutes($currentDateTime);
            if($MinutesDifference>20){
                $orderx['is_preparing']=true;
                $orderx->save();
            }
            if($daysDifference>3){
                $orderx['is_deliverd']=true;
                $orderx->save();

            }else if($daysDifference>1){
                $orderx['is_in_the_way']=true;
                $orderx->save();
            }
            if($order->is_in_the_way){
                $product['is_in_the_way']=true;
                }else{
                $product['is_in_the_way']=false;
                 } 
                   if($order->is_preparing){
                $product['is_preparing']=true;
                }else{
                $product['is_preparing']=false;
                 }
                 
                  if($order->is_deliverd){
                $product['is_delivered']=true;
                }else{
                $product['is_delivered']=false;
                 }

          
          

            array_push($products,$product);
        }
       
        return response()->json([
            'status'=>true,
            'message' => 'Order list for user', 
            'products'=>$products,
        ], 200);

    }
    public function addToOrder(Request $request){
        $user_id = auth()->user()->id;   

        $request->validate([
            'product_id' => 'required|array|',
            'product_quantity' => 'required|array',
            'size' => 'required|array',
            'address' => 'required|string',
            'is_paid' => 'required|boolean',

        ]);
      

        $product_ids = $request->product_id;
        $product_quentatys= $request->product_quantity;
        $sizes=$request->size;
        $address=$request->address;
        $is_paid=$request->is_paid;
        $order_code=substr(uniqid('YS-'),0,10);
       
        $exists = ProductSize::whereIn('size', $sizes,)->exists();
        if(!$exists){
            return response()->json([
                'status'=>false,
                'message' => 'size not found',
            ], 404);
        }
 
        $count = count($product_ids);

        for ($i = 0; $i < $count; $i++) {
            Order::create([
                'product_id' => $product_ids[$i],
                'user_id' => $user_id,
                'product_quentaty' => $product_quentatys[$i],
                'address' => $address,
                'is_paid' => $is_paid,
                'size'=>$sizes[$i],
                'order_code'=>$order_code,
            ]);
            
        }
     
        return response()->json([
            'status'=>true,
            'message' => 'Order added successfully',
        ], 200);

    }
}
