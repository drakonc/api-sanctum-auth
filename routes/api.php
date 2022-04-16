<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\BlogController;
use App\Http\Controllers\Api\UserController;

Route::post('register',[UserController::class,'register']);
Route::post('login',[UserController::class,'login']);

Route::group(['middleware' => ["auth:sanctum"]], function(){
    //Todo: Rutas de Auth
    Route::get('user-profile',[UserController::class,'userProfile']);
    Route::get('logout',[UserController::class,'logout']);

    //Todo: Rutas del Blog
    Route::get('list-blog',[BlogController::class,'listBlog']);
    Route::get('show-blog/{id}',[BlogController::class,'showBlog']);
    Route::post('create-blog',[BlogController::class,'createBlog']);
    Route::put('update-blog/{id}',[BlogController::class,'updateBlog']);
    Route::delete('delete-blog/{id}',[BlogController::class,'deleteBlog']);
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});