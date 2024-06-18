<?php

use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\ServiceController;
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

// Route::prefix('category')->group(function () {
//     Route::get('/',[CategoryController::class,'getCategories'])->middleware();
//     Route::post('/create',[CategoryController::class,'createCategory']);
//     Route::get('/{id}',[CategoryController::class,'findById']);
//     Route::put('/update',[CategoryController::class,'updateCategory']);
//     Route::delete('/delete/{id}',[CategoryController::class,'deleteCategory']);
// });

Route::prefix('category')->controller(CategoryController::class)->group(function(){
    Route::get('/','getCategories')->middleware('check_permission:view category');
});

Route::prefix('service')->group(function () {
    Route::get('/',[ServiceController::class,'getServices']);
    Route::post('/create',[ServiceController::class,'createService']);
    Route::get('/{id}',[ServiceController::class,'findById']);
    Route::put('/update',[ServiceController::class,'updateService']);
    Route::delete('/delete/{id}',[ServiceController::class,'deleteService']);
});

Route::prefix('history')->group(function () {
    Route::get('/',[HistoryController::class,'getHistory']);
    Route::get('/list-meeting',[HistoryController::class,'listMeeting']);
    Route::post('/transfer-information',[HistoryController::class,'transferInformation']);
    Route::post('/create',[HistoryController::class,'createHistory']);
    Route::get('/{id}',[HistoryController::class,'findById']);
    Route::put('/update',[HistoryController::class,'updateHistory']);
});

Route::prefix('appointment')->group(function () {
    Route::get('/',[AppointmentController::class,'getAppointment']);
    Route::post('/create',[AppointmentController::class,'createAppointment']);
    Route::get('/{id}',[AppointmentController::class,'findById']);
    Route::put('/update',[AppointmentController::class,'updateAppointment']);
    Route::delete('/delete/{id}',[AppointmentController::class,'deleteAppointment']);
});

Route::prefix('schedule')->group(function () {
    Route::get('/',[ScheduleController::class,'getSchedule']);
    Route::post('/create',[ScheduleController::class,'createSchedule']);
    // Route::get('/{id}',[ScheduleController::class,'findById']);
    // Route::put('/update',[ScheduleController::class,'updateAppointment']);
    Route::delete('/delete/{id}',[ScheduleController::class,'deleteAppointment']);
});

Route::prefix('invoice')->group(function () {
    Route::get('/',[InvoiceController::class,'getInvoice']);
    Route::post('/create',[InvoiceController::class,'createInvoice']);
    Route::get('/{id}',[InvoiceController::class,'findById']);
    // Route::put('/update',[InvoiceController::class,'updateInvoice']);
    // Route::delete('/delete/{id}',[InvoiceController::class,'deleteInvoice']);
});