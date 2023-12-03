<?php

use App\Http\Controllers\AddressController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\FavoirteController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductReviewController;
use App\Http\Controllers\ProductSizeController;
use App\Http\Controllers\TesterController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
//user
Route::post('/register',[RegisterController::class,'createUser']);
Route::post('/login',[LoginController::class,'login']);
Route::get('/users',[UserController::class,'allUser']);
Route::delete('/user/delete/{id}',[UserController::class,'deleteUser']);
Route::post('/user/search',[UserController::class,'userSearch']);
Route::post('user/updateProfileImage',[UserController::class,'updateProfileImage'])->middleware('auth:sanctum');
Route::put('/user/update',[UserController::class,'updateUser'])->middleware('auth:sanctum');
Route::put('/user/passwordUpdate',[UserController::class,'updatePassword'])->middleware('auth:sanctum');
Route::get('/profile',[UserController::class,'userProfile'])->middleware('auth:sanctum');
//category
Route::get('/categories',[CategoryController::class,'AllCategory'])->middleware('auth:sanctum');
Route::post('/category/store',[CategoryController::class,'StoreCategory'])->middleware('auth:sanctum');
Route::delete('/category/delete/{id}',[CategoryController::class,'DeleteCategory'])->middleware('auth:sanctum');
//product
Route::get('/products',[ProductController::class,'allProduct'])->middleware('auth:sanctum');
Route::post('/product/store',[ProductController::class,'StoreProduct'])->middleware('auth:sanctum');
Route::delete('/product/delete/{id}',[ProductController::class,'DeleteProduct'])->middleware('auth:sanctum');
Route::post('/product/search',[ProductController::class,'SearchProduct'])->middleware('auth:sanctum');
Route::post('/product/addsize',[ProductSizeController::class,'addSize'])->middleware('auth:sanctum');
Route::delete('/product/deletesize/{id}',[ProductSizeController::class,'deleteSize'])->middleware('auth:sanctum');

Route::get('/product/getReview',[ProductReviewController::class,'getReview'])->middleware('auth:sanctum');
Route::post('/product/addReview',[ProductReviewController::class,'addReview'])->middleware('auth:sanctum');
Route::delete('/product/deleteReview/{id}',[ProductReviewController::class,'deleteReview'])->middleware('auth:sanctum');
Route::post('/product/favorite',[FavoirteController::class,'addOrDeleteFavoirte'])->middleware('auth:sanctum');
Route::get('/product/favorite',[FavoirteController::class,'getFavorite'])->middleware('auth:sanctum');

Route::get('/product/cart',[CartController::class,'getCarts'])->middleware('auth:sanctum');
Route::post('/product/cart',[CartController::class,'addOrDeleteCart'])->middleware('auth:sanctum');
Route::get('/product/size',[ProductSizeController::class,'allSize'])->middleware('auth:sanctum');



Route::post('/order',[OrderController::class,'addToOrder'])->middleware('auth:sanctum');
Route::get('/order',[OrderController::class,'getOrder'])->middleware('auth:sanctum');


//address
Route::get('/address',[AddressController::class,'getAddresses'])->middleware('auth:sanctum');
Route::post('/address',[AddressController::class,'addAddress'])->middleware('auth:sanctum');
Route::put('/address',[AddressController::class,'updateAddress'])->middleware('auth:sanctum');


Route::post('/order/search',[OrderController::class,'SearchOrByCode'])->middleware('auth:sanctum');




Route::post('/test',[TesterController::class,'storeListOfImage'])->middleware('auth:sanctum');
