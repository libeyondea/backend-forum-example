<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\TagController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\SettingController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\VerificationController;

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
    Route::get('posts/{user_name}/{slug}', [PostController::class, 'singlePost']);
    Route::get('posts_ghim', [PostController::class, 'listPostGhim']);
    //
    Route::get('tags', [TagController::class, 'listTag']);
    Route::get('tags/{slug}', [TagController::class, 'singleTag']);
    //
    Route::get('categories', [CategoryController::class, 'listCategory']);
    Route::get('categories/{slug}', [CategoryController::class, 'singleCategory']);
    //
    Route::get('comments', [CommentController::class, 'listComment']);
    Route::get('comments/{slug}', [CommentController::class, 'singleComment']);
    //
    Route::post('current_user/deletion', [AuthController::class, 'facebookUserDeletion']);
    //
    Route::get('email/verify/{id}/{hash}', [VerificationController::class, 'verify'])->name('verification.verify');
    Route::post('email/resend', [VerificationController::class, 'resend'])->name('verification.resend');
});

Route::group(['middleware' => ['auth:api', 'cors']], function(){
    Route::get('current_user/logout', [AuthController::class, 'logoutUser']);
    Route::get('current_user', [AuthController::class, 'currentUser']);
    //
    Route::get('tags_followed', [TagController::class, 'listTagFollowed']);
    //
    Route::get('settings/profile/edit', [SettingController::class, 'editProfile']);
    Route::post('settings/profile', [SettingController::class, 'updateProfile']);
    Route::get('settings/customization/edit', [SettingController::class, 'editCustomization']);
    Route::put('settings/customization', [SettingController::class, 'updateCustomization']);
    //
    Route::get('dashboard/posts', [DashboardController::class, 'listPost']);
    Route::get('dashboard/favorited_posts', [DashboardController::class, 'listFavoritedPost']);
    Route::get('dashboard/user_followers', [DashboardController::class, 'listUserFollower']);
    Route::get('dashboard/following_users', [DashboardController::class, 'listFollowingUser']);
    Route::get('dashboard/following_tags', [DashboardController::class, 'listFollowingTag']);
    //
    Route::post('posts', [PostController::class, 'createPost']);
    Route::post('posts/{slug}', [PostController::class, 'updatePost']);
    Route::delete('posts/{slug}', [PostController::class, 'deletePost']);
    Route::get('posts/{user_name}/{slug}/edit', [PostController::class, 'editPost']);

    Route::post('favorite_post', [PostController::class, 'favoritePost']);
    Route::delete('favorite_post', [PostController::class, 'unFavoritePost']);
    //
    Route::post('follow_user', [AuthController::class, 'followUser']);
    Route::delete('follow_user', [AuthController::class, 'unFollowUser']);
    //
    Route::post('follow_tag', [TagController::class, 'followTag']);
    Route::delete('follow_tag', [TagController::class, 'unFollowTag']);
    //
    Route::post('comments', [CommentController::class, 'createComment']);
    Route::delete('comments/{slug}', [CommentController::class, 'deleteComment']);
    Route::put('comments/{slug}', [CommentController::class, 'updateComment']);
    Route::get('comments/{slug}/edit', [CommentController::class, 'editComment']);
    Route::get('comments/{slug}/delete', [CommentController::class, 'deleteCommentConfirm']);

    Route::post('favorite_comment', [CommentController::class, 'favoriteComment']);
    Route::delete('favorite_comment', [CommentController::class, 'unFavoriteComment']);
});
