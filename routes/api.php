<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\TagController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\FollowController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['middleware' => 'cors'], function(){
    Route::post('users/login', [AuthController::class, 'login']);
    Route::post('users/register', [AuthController::class, 'register']);
    //
    Route::get('users', [AuthController::class, 'listUser']);
    Route::get('users/{user_name}', [AuthController::class, 'singleUser']);
    //
    Route::get('posts', [PostController::class, 'listPost']);
    Route::get('posts/{slug}', [PostController::class, 'singlePost']);
    //
    Route::get('tags', [TagController::class, 'listTag']);
    Route::get('tags/{slug}', [TagController::class, 'singleTag']);
    //
    Route::get('categories', [CategoryController::class, 'listCategory']);
    Route::get('categories/{slug}', [CategoryController::class, 'singleCategory']);
    //
    Route::get('comments', [CommentController::class, 'listComment']);
    Route::get('comments/{id}', [CommentController::class, 'singleComment']);
});

Route::group(['middleware' => ['auth:api', 'cors']], function(){
    Route::get('current_user/logout', [AuthController::class, 'logoutUser']);
    Route::get('current_user', [AuthController::class, 'currentUser']);
    //
    Route::put('users', [AuthController::class, 'updateUser']);
    Route::get('users/profile/edit', [AuthController::class, 'editUser']);
    //
    Route::post('follows', [FollowController::class, 'follow']);
    Route::delete('follows', [FollowController::class, 'unFollow']);
    //
    Route::post('comments', [CommentController::class, 'createComment']);
    Route::delete('comments', [CommentController::class, 'deleteComment']);
    Route::put('comments', [CommentController::class, 'updateComment']);
    Route::get('comments/{id}/edit', [CommentController::class, 'editComment']);
});
