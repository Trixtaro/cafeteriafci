<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\UserPostsController;
use App\Http\Controllers\Api\PermissionController;
use App\Http\Controllers\Api\UserCommentsController;
use App\Http\Controllers\Api\PostCommentsController;
use App\Http\Controllers\Api\CategoryPostsController;
use App\Http\Controllers\Api\PostCategoriesController;

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

Route::post('/login', [AuthController::class, 'login'])->name('api.login');

Route::middleware('auth:sanctum')
    ->get('/user', function (Request $request) {
        return $request->user();
    })
    ->name('api.user');

Route::name('api.')
    ->middleware('auth:sanctum')
    ->group(function () {
        Route::apiResource('roles', RoleController::class);
        Route::apiResource('permissions', PermissionController::class);

        Route::apiResource('users', UserController::class);

        // User Posts
        Route::get('/users/{user}/posts', [
            UserPostsController::class,
            'index',
        ])->name('users.posts.index');
        Route::post('/users/{user}/posts', [
            UserPostsController::class,
            'store',
        ])->name('users.posts.store');

        // User Comments
        Route::get('/users/{user}/comments', [
            UserCommentsController::class,
            'index',
        ])->name('users.comments.index');
        Route::post('/users/{user}/comments', [
            UserCommentsController::class,
            'store',
        ])->name('users.comments.store');

        Route::apiResource('categories', CategoryController::class);

        // Category Posts
        Route::get('/categories/{category}/posts', [
            CategoryPostsController::class,
            'index',
        ])->name('categories.posts.index');
        Route::post('/categories/{category}/posts/{post}', [
            CategoryPostsController::class,
            'store',
        ])->name('categories.posts.store');
        Route::delete('/categories/{category}/posts/{post}', [
            CategoryPostsController::class,
            'destroy',
        ])->name('categories.posts.destroy');

        Route::apiResource('posts', PostController::class);

        // Post Comments
        Route::get('/posts/{post}/comments', [
            PostCommentsController::class,
            'index',
        ])->name('posts.comments.index');
        Route::post('/posts/{post}/comments', [
            PostCommentsController::class,
            'store',
        ])->name('posts.comments.store');

        // Post Categories
        Route::get('/posts/{post}/categories', [
            PostCategoriesController::class,
            'index',
        ])->name('posts.categories.index');
        Route::post('/posts/{post}/categories/{category}', [
            PostCategoriesController::class,
            'store',
        ])->name('posts.categories.store');
        Route::delete('/posts/{post}/categories/{category}', [
            PostCategoriesController::class,
            'destroy',
        ])->name('posts.categories.destroy');

        Route::apiResource('comments', CommentController::class);
    });
