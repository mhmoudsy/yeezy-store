<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $fillable = [
        'product_id',
        'user_id',
        'product_quentaty',
        'size',
        'address',
        'is_paid',
        'is_in_the_way',
        'is_preparing',
        'is_deliverd',
        'order_code',
    ];
    public function product(){
        return $this->hasMany(Product::class);
    }
    public function user(){
        return $this->hasMany(User::class);
    }
}
