<?php

use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ClientController;
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

Route::prefix('v1/user')->controller(UserController::class)->group(function(){
    Route::get('/','getUsers');
    Route::post('/','createUser');
    Route::get('/{id}','findById')->name('user.detail');
    Route::put('/{id}','updateUser');
});

Route::prefix('v1/customer')->controller(CustomerController::class)->group(function () {
    Route::get('/','getCutomer');
    Route::post('/','createCustomer');
    Route::get('/{id}','findById')->name('customer.detail');
    Route::put('/{id}','updateCustomer');
});

Route::prefix('v1/category')->controller(CategoryController::class)->group(function(){
    Route::get('/','getCategories');
    Route::get('/{id}','findById')->name('category.detail');
    Route::post('/','createCategory');
    Route::put('/{id}','updateCategory');
    Route::delete('/{id}','deleteCategory');
});

Route::prefix('v1/service')->controller(ServiceController::class)->group(function () {
    Route::get('/','getServices');
    Route::post('/','createService');
    Route::get('/{id}','findById')->name('service.detail');
    Route::put('/{id}','updateService');
    Route::delete('/{id}','deleteService');
});

Route::prefix('v1/history')->controller(HistoryController::class)->group(function () {
    Route::get('/','getHistory');
    Route::get('/list-meeting','listMeeting');
    Route::post('/transfer-information','transferInformation');
    Route::post('/','createHistory');
    Route::get('/{id}','findById')->name('history.detail');
    Route::put('/{id}','updateHistory');
});

Route::prefix('v1/appointment')->controller(AppointmentController::class)->group(function () {
    Route::get('/','getAppointment');
    Route::post('/','createAppointment');
    Route::get('/{id}','findById')->name('appointment.detail');
    Route::put('/{id}','updateAppointment');
    Route::delete('/{id}','deleteAppointment');
});

Route::prefix('v1/schedule')->controller(ScheduleController::class)->group(function () {
    Route::get('/','getSchedule');
    Route::post('/','createSchedule');
    // Route::get('/{id}','findById');
    // Route::put('/','updateAppointment');
    // Route::delete('/{id}','deleteAppointment');
});

Route::prefix('v1/invoice')->controller(InvoiceController::class)->group(function () {
    Route::get('/','getInvoice');
    Route::post('/','createInvoice');
    Route::get('/{id}','findById')->name('invoice.detail');
    Route::put('/{id}','updateInvoice');
    // Route::delete('/delete/{id}',[InvoiceController::class,'deleteInvoice']);
});

Route::prefix('v1/role')->controller(RoleController::class)->group(function(){
    Route::get('/','getRoles');
    Route::get('/{id}','findByID');
    Route::put('/update/{id}','updatePermissions');
});
Route::post('v1/upload', [UploadController::class, 'uploadImage']);



// Route::prefix('v2')->group(function(){
//     Route::get('/category',[CategoryController::class,'getCategories']);
//     Route::get('category/{id}',[CategoryController::class,'findById']);
//     Route::get('/service',[ServiceController::class,'getServices']);
//     Route::get('/service/{id}',[ServiceController::class,'findById']);
//     Route::Get('doctor',[UserController::class,'getDoctor']);
//     Route::get('doctor/{id}',[UserController::class,'getDoctorId']);
//     Route::Get('doctor/{id}/date/{date}',[UserController::class,'getDoctorTimeslotsByDate']);
//     Route::get('appointment/create',[AppointmentController::class,'createAppointment']);
// });

Route::prefix('v2')->controller(ClientController::class)->group(function(){
    Route::prefix('doctor')->group(function(){
        Route::get('/','getDoctor');
        Route::Get('/{id}','getDoctorDetail');
    });
    Route::prefix('schedule')->group(function(){
        Route::get('/{id}','getDoctorScheduleWithTimeslots');
        Route::get('/{id}/{date}','getDoctorTimeslotsByDate');
    });
    Route::post('/Appointment','createAppointment');
    Route::get('/time','getTime');
    
    Route::prefix('category')->group(function(){
        Route::get('/','getCategories');
        Route::Get('/{id}','categoryfindById')->name('category.detail');
       
    });
    Route::prefix('service')->group(function(){
        Route::get('/','getServices');
        Route::Get('/{id}','serviceFindById')->name('service.detail');
       
    });
});


