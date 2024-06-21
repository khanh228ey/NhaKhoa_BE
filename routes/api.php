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
    Route::post('/create','createUser');
    Route::get('/{id}','findById');
    Route::put('update/{id}','updateUser');
});

Route::prefix('customer')->controller(CustomerController::class)->group(function () {
    Route::get('/','getCutomer');
    Route::post('/create','createCustomer');
    Route::get('/{id}','findById');
    Route::put('update/{id}','updateCustomer');
});

Route::prefix('category')->controller(CategoryController::class)->group(function(){
    Route::get('/','getCategories');
    Route::get('/{id}','findById');
    Route::post('/create','createCategory');
    Route::put('/update/{id}','updateCategory');
    Route::delete('/delete/{id}','deleteCategory');
});

Route::prefix('service')->controller(ServiceController::class)->group(function () {
    Route::get('/','getServices');
    Route::post('/create','createService');
    Route::get('/{id}','findById');
    Route::put('/update/{id}','updateService');
    Route::delete('/delete/{id}','deleteService');
});

Route::prefix('history')->controller(HistoryController::class)->group(function () {
    Route::get('/','getHistory');
    Route::get('/list-meeting','listMeeting');
    Route::post('/transfer-information','transferInformation');
    Route::post('/create','createHistory');
    Route::get('/{id}','findById');
    Route::put('/update/{id}','updateHistory');
});

Route::prefix('appointment')->controller(AppointmentController::class)->group(function () {
    Route::get('/','getAppointment');
    Route::post('/create','createAppointment');
    Route::get('/{id}','findById');
    Route::put('/update/{id}','updateAppointment');
    Route::delete('/delete/{id}','deleteAppointment');
});

Route::prefix('schedule')->controller(ScheduleController::class)->group(function () {
    Route::get('/','getSchedule');
    Route::post('/create','createSchedule');
    // Route::get('/{id}','findById');
    // Route::put('/update','updateAppointment');
    Route::delete('/delete/{id}','deleteAppointment');
});

Route::prefix('invoice')->controller(InvoiceController::class)->group(function () {
    Route::get('/','getInvoice');
    Route::post('/create','createInvoice');
    Route::get('/{id}',[InvoiceController::class,'findById']);
    Route::put('/update/{id}',[InvoiceController::class,'updateInvoice']);
    // Route::delete('/delete/{id}',[InvoiceController::class,'deleteInvoice']);
});

Route::prefix('role')->controller(RoleController::class)->group(function(){
    Route::get('/','getRoles');
    Route::get('/{id}','findByID');
    Route::put('/update/{id}','updatePermissions');
});

Route::post('/upload', [UploadController::class, 'upload']);