<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\ProductSize;
use App\Models\Tester;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TesterController extends Controller
{

    public function storeListOfImage(Request $request){
        $image= $request->validate([
            'product_image'=>'required|array',
        ]);
        #The file \"tester_image\" does not exist error solution

 
            if($request->hasFile('product_image')){
                $product_images=[];
                $image=$request->file('product_image');
                foreach($image as $img){
                    $imageName=$img->getClientOriginalName();
                    $path = Storage::disk('public')->putFile('tester_image', $img);
                    $url = Storage::disk('public')->url($path);
                    $product_images[]=$url;
                    Tester::create([
                        'images'=>$url,
                    ]);
                }
         
          }
    

     
 

        
         

    //   }
      return response()->json([
        'message' => 'Image Uploaded Successfully',
        'url' => $product_images,
    ], 200);
 

    }
}
      

