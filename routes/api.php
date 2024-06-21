<?php

use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\UploadController;
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

Route::prefix('user')->controller(UserController::class)->group(function(){
    Route::get('/','getUsers');
    Route::post('/create','createUser')->middleware('check_permission:create user');
    Route::get('/{id}','findById');
    Route::put('update/{id}','updateUser')->middleware('check_permission:update user');
});

Route::prefix('customer')->controller(CustomerController::class)->group(function () {
    Route::get('/','getCutomer')->middleware('check_permission:view customer');
    Route::post('/create','createCustomer')->middleware('check_permission:create customer');
    Route::get('/{id}','findById')->middleware('check_permission:view customer');;
    Route::put('update/{id}','updateCustomer')->middleware('check_permission:update customer');
});

Route::prefix('category')->controller(CategoryController::class)->group(function(){
    Route::get('/','getCategories');
    Route::get('/{id}','findById');
    Route::post('/create','createCategory')->middleware('check_permission:create category');
    Route::put('/update/{id}','updateCategory')->middleware('check_permission:update category');
    Route::delete('/delete/{id}','deleteCategory')->middleware('check_permission:delete category');
});

Route::prefix('service')->controller(ServiceController::class)->group(function () {
    Route::get('/','getServices');
    Route::post('/create','createService')->middleware('check_permission:create service');
    Route::get('/{id}','findById');
    Route::put('/update/{id}','updateService')->middleware('check_permission:update service');
    Route::delete('/delete/{id}','deleteService')->middleware('check_permission:delete service');
});

Route::prefix('history')->controller(HistoryController::class)->group(function () {
    Route::get('/','getHistory')->middleware('check_permission:view history');
    Route::get('/list-meeting','listMeeting');
    Route::post('/transfer-information','transferInformation')->middleware('check_permission:create meeting');
    Route::post('/create','createHistory')->middleware('check_permission:create history');
    Route::get('/{id}','findById')->middleware('check_permission:view history');
    Route::put('/update/{id}','updateHistory')->middleware('check_permission:update history');
});

Route::prefix('appointment')->controller(AppointmentController::class)->group(function () {
    Route::get('/','getAppointment')->middleware('check_permission:view appointment');
    Route::post('/create','createAppointment');
    Route::get('/{id}','findById')->middleware('check_permission:view appointment');
    Route::put('/update/{id}','updateAppointment')->middleware('check_permission:update appointment');
    Route::delete('/delete/{id}','deleteAppointment')->middleware('check_permission:delete appointment');
});

Route::prefix('schedule')->controller(ScheduleController::class)->group(function () {
    Route::get('/','getSchedule');
    Route::post('/create','createSchedule')->middleware('check_permission:create schedule');
    // Route::get('/{id}','findById');
    // Route::put('/update','updateAppointment');
    Route::delete('/delete/{id}','deleteAppointment')->middleware('check_permission:delete schedule');
});

Route::prefix('invoice')->controller(InvoiceController::class)->group(function () {
    Route::get('/','getInvoice')->middleware('check_permission:view invoice');
    Route::post('/create','createInvoice')->middleware('check_permission:create invoice');
    Route::get('/{id}',[InvoiceController::class,'findById'])->middleware('check_permission:view invoice');
    Route::put('/update/{id}',[InvoiceController::class,'updateInvoice']);
    // Route::delete('/delete/{id}',[InvoiceController::class,'deleteInvoice']);
});

Route::prefix('role')->controller(RoleController::class)->group(function(){
    Route::get('/','getRoles')->middleware('check_permission:view role');
    Route::get('/{id}','findByID')->middleware('check_permission:view role');
    Route::put('/update/{id}','updatePermissions')->middleware('check_permission:update role');
});

Route::post('/upload', [UploadController::class, 'upload']);