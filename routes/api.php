<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\V1\Blog\TagController;
use App\Http\Controllers\V1\Blog\PostController;
use App\Http\Controllers\v1\User\UserController;
use App\Http\Controllers\V1\Blog\CommentController;
use App\Http\Controllers\V1\blog\PostTagController;
use App\Http\Controllers\V1\Blog\CategoryController;
use App\Http\Controllers\V1\Blog\PostCategorController;

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

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    // dd($request);
    // return response()->json(['user' =>$request->user()->load('roles'),"test" => $request->user()->load('roles')]);
    return $request->user()->load('roles');
});
Route::middleware(['auth:sanctum',])->prefix('v1')->group(function () {
    Route::prefix('user')->group(function () {
        Route::put('update', [UserController::class, 'updateAuth']);

    });
    // Route::apiResource("user",UserController::class);
    Route::apiResource("post",PostController::class);
    Route::apiResource("tag",TagController::class);
    Route::apiResource("category",CategoryController::class);
    // Route::apiResource("admin",AdminController::class);
    Route::prefix('admin')->group(function () {
        Route::prefix("user")->group(function (){
            Route::get('', [AdminController::class, 'indexAllUser']);
            Route::post('', [AdminController::class, 'storeUser']);
            Route::get('{user}', [AdminController::class, 'showUser']);
            Route::put('{user}', [AdminController::class, 'updateUser']);
            Route::delete('{user}', [AdminController::class, 'destroyUser']);
        });
        Route::prefix("post")->group(function (){
            Route::get('', [AdminController::class, 'getAllPost']);
            Route::post('', [AdminController::class, 'storePost']);
            Route::get('{post}', [AdminController::class, 'showPost']);
            Route::put('{post}', [AdminController::class, 'updatePost']);
            Route::delete('{post}', [AdminController::class, 'destroyPost']);
        });
    Route::apiResource("role",RoleController::class);

    });

    Route::prefix('posts/{post}')->group(function () {
        Route::get('comments', [CommentController::class, 'indexForPost']);
        Route::get('comments/{comment}', [CommentController::class, 'showForPost']);
        Route::post('comments', [CommentController::class, 'storeForPost']);
        Route::put('comments/{comment}', [CommentController::class, 'updateForPost']);
        Route::delete('comments/{comment}', [CommentController::class, 'destroyForPost']);
        Route::get('tag', [PostTagController::class, 'getAllTagInPost']);
        Route::get('categories', [PostCategorController::class, 'getAllCategorInPost']);
    });
    Route::get('tags/{tag}/post', [PostTagController::class, 'getAllPostInTags']);
    Route::get('user/{user}/post', [PostController::class, 'getPostInUser']);

    Route::get('categories/{category}/posts', [PostCategorController::class, 'getAllPostInCategors']);
});

