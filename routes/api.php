<?php

use App\Http\Controllers\blogController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/posts',[blogController::class,'index']);
Route::get('/posts/{id}',[blogController::class,'show']);
Route::put('/posts/{id}',[blogController::class,'edit']);
Route::post('/posts',[blogController::class,'store']);
Route::delete('/posts/{id}',[blogController::class,'destroy']);

//
