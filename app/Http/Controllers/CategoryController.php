<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    public function AllCategory(){
        $categories=Category::all();
        return response()->json([
            'message'=>'success',
            //mooot
            'categories'=>$categories->load('products'),
        ]);
    }
    public function StoreCategory(Request $request){
        $parameter=$request->validate([
            'category_name'=>'required|unique:categories|max:255',
            'category_description'=>'required',
            'category_image'=>'required|image'
        ]);
        if($request->hasFile('category_image')){
            $image=$request->file('category_image');
            $imageName=$image->getClientOriginalName();
            Storage::disk('public')->putFileAs('category_image', $image,$imageName);
            $path = Storage::disk('public')->putFile('category_image', $request->file('category_image'));
            $url = Storage::disk('public')->url($path);
            $parameter['category_image']=$url;
         
         

      }
        $category=Category::create($parameter);
        return response()->json([
            'message'=>'success',
            'category'=>$category
        ]);


    
    }
    public function DeleteCategory(String $id){
        $category=Category::find($id);
        if($category){
            $category->delete();
            return response()->json([
                'message'=>'category deleted successfully',
            ]);
        }
        else{
            return response()->json([
                'message'=>'category not found',
            ]);
        }
    }
}
