<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TaskController;

Route::get('/', function () {
    return view('welcome');
});

Route::post('/user/register', [UserController::class, 'register']);
Route::post('/user/login', [AuthController::class, 'login']);
Route::get('/post/view', [TaskController::class, 'view']);
Route::post('/post/add', [TaskController::class, 'add']);
Route::delete('/post/delete/{id}', [TaskController::class, 'delete']);
Route::get('/post/filter', [TaskController::class, 'filter']);
Route::put('/post/edit/{id}', [TaskController::class, 'edit']);

Route::get('/post/allposts', [TaskController::class, 'getPublicPosts']);