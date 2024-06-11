<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::group([

    'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {
    Route::post('login', [AuthController::class,'login']);
    Route::post('logout', [AuthController::class,'logout']);
    Route::post('refresh', [AuthController::class,'refresh']);
    Route::get('profile', [AuthController::class,'profile']);
});

Route::prefix('user')->group(function () {
    Route::get('/', [UserController::class, 'getUsers']);
    Route::post('/create', [UserController::class, 'createUser']);
    Route::get('/{id}', [UserController::class, 'findById']);
    Route::put('update',[UserController::class, 'updateUser']);
});

Route::prefix('customer')->group(function () {
    Route::get('/',[CustomerController::class,'getCutomer']);
    Route::post('/create',[CustomerController::class,'createCustomer']);
    Route::get('/{id}', [CustomerController::class, 'findById']);
    Route::put('update',[CustomerController::class, 'updateCustomer']);
});

Route::prefix('category')->group(function () {
    Route::get('/',[CategoryController::class,'getCategories']);
    Route::post('/create',[CategoryController::class,'createCategory']);
    Route::get('/{id}',[CategoryController::class,'findById']);
    Route::put('/update',[CategoryController::class,'updateCategory']);
    Route::delete('/delete/{id}',[CategoryController::class,'deleteCategory']);
});



