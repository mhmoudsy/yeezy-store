<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $fillable=[
        'product_name',
        'product_description',
        'product_price',
        'product_image',
        'category_id',
        
    ];
    public function categories(){
        return $this->belongsTo(Category::class,'category_id');
    }
    public function sizes(){
        return $this->hasMany(ProductSize::class,'product_id');
    }
    public function reviews(){
        return $this->hasMany(ProductReview::class,'product_id');
    }
    public function favorites(){
        return $this->belongsTo(Favorite::class,'product_id');
    }
       public function carts(){
        return $this->belongsTo(Cart::class,'product_id');
    }
     public function orders(){
        return $this->belongsTo(Order::class,'product_id');
    }

}
