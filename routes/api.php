<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Administration\AuthController;
use App\Http\Controllers\Administration\UserController;
use App\Http\Controllers\Administration\RoleController;
use App\Http\Controllers\Administration\CountryController;

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

Route::post('login',[AuthController::class,'authenticate']);
Route::put('login',[AuthController::class,'getAuthenticatedUser'])->middleware('jwt.verify');

Route::get   ('users',[UserController::class,'list'])->middleware('jwt.verify');
Route::post  ('users',[UserController::class,'new'])->middleware('jwt.verify');
Route::put   ('users',[UserController::class,'edit'])->middleware('jwt.verify');
Route::delete('users',[UserController::class,'delete'])->middleware('jwt.verify');

Route::get   ('roles',[RoleController::class,'list'])->middleware('jwt.verify');
Route::post  ('roles',[RoleController::class,'new'])->middleware('jwt.verify');
Route::put   ('roles',[RoleController::class,'edit'])->middleware('jwt.verify');
Route::delete('roles',[RoleController::class,'delete'])->middleware('jwt.verify');

Route::get   ('countries',[CountryController::class,'list'])->middleware('jwt.verify');

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
