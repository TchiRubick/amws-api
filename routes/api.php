<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ArticleController;

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

Route::post('/login', [AuthController::class, 'login']);

Route::prefix('admin')->middleware('auth:sanctum')->group(function () {
    Route::get('/users', [UserController::class, 'getMany']);
    Route::post('/user', [UserController::class, 'create']);
    Route::get('/user/{user}', [UserController::class, 'get']);
    Route::delete('/user/{user}', [UserController::class, 'remove']);
    Route::put('/user/{user}', [UserController::class, 'edit']);

    Route::post('/article-new', [ArticleController::class, 'create']);
    Route::post('/article/{article}', [ArticleController::class, 'edit']);
    Route::delete('/article/{article}', [ArticleController::class, 'remove']);
});

Route::get('/articles', [ArticleController::class, 'getMany']);
Route::get('/article/{article}', [ArticleController::class, 'get']);
