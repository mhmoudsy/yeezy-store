<?php

namespace App\Http\Controllers;

use App\Models\ProductReview;
use Illuminate\Http\Request;

class ProductReviewController extends Controller
{
    public function getReview(){
        $productReview = ProductReview::with(['users:id,username,image'])->get();
        return response()->json([
            'message'=>'success',
            'ProductReview'=>$productReview,
            
        ],200);
       }


       public function addReview(Request $request){
        $review=$request->validate([
            'product_id'=>'required',
            'user_id'=>'required',
            'content'=>'required',
            'rating'=>'required|numeric|between:0,5',
        ]);
        $productReview=ProductReview::create($review);
            return response()->json([
                'message'=>'success',
                'ProductReview'=>$productReview
            ],200);
        
      

       }
       public function deleteReview($id){
        $productReview=ProductReview::find($id);
        $productReview->delete();
        return response()->json([
            'message'=>'delete review successfully',
        ],200);
       }
}
