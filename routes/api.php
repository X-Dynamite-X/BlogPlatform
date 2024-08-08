<?php


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
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
    return $request->user();
});
Route::middleware(['auth:sanctum',])->prefix('v1')->group(function () {
    Route::apiResource("user",UserController::class);
    Route::apiResource("post",PostController::class);
    Route::apiResource("tag",TagController::class);
    Route::apiResource("category",CategoryController::class);
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
    Route::get('categories/{category}/posts', [PostCategorController::class, 'getAllPostInCategors']);
});
