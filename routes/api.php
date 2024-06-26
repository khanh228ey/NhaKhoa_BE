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
    Route::post('/create','createUser');
    Route::get('/{id}','findById');
    Route::put('update/{id}','updateUser');
});

Route::prefix('v1/customer')->controller(CustomerController::class)->group(function () {
    Route::get('/','getCutomer');
    Route::post('/create','createCustomer');
    Route::get('/{id}','findById');
    Route::put('update/{id}','updateCustomer');
});

Route::prefix('v1/category')->controller(CategoryController::class)->group(function(){
    Route::get('/','getCategories');
    Route::get('/{id}','findById');
    Route::post('/create','createCategory');
    Route::put('/update/{id}','updateCategory');
    Route::delete('/delete/{id}','deleteCategory');
});

Route::prefix('v1/service')->controller(ServiceController::class)->group(function () {
    Route::get('/','getServices');
    Route::post('/create','createService');
    Route::get('/{id}','findById');
    Route::put('/update/{id}','updateService');
    Route::delete('/delete/{id}','deleteService');
});

Route::prefix('v1/history')->controller(HistoryController::class)->group(function () {
    Route::get('/','getHistory');
    Route::get('/list-meeting','listMeeting');
    Route::post('/transfer-information','transferInformation');
    Route::post('/create','createHistory');
    Route::get('/{id}','findById');
    Route::put('/update/{id}','updateHistory');
});

Route::prefix('v1/appointment')->controller(AppointmentController::class)->group(function () {
    Route::get('/','getAppointment');
    Route::post('/create','createAppointment');
    Route::get('/{id}','findById');
    Route::put('/update/{id}','updateAppointment');
    Route::delete('/delete/{id}','deleteAppointment');
});

Route::prefix('v1/schedule')->controller(ScheduleController::class)->group(function () {
    Route::get('/','getSchedule');
    Route::post('/create','createSchedule');
    // Route::get('/{id}','findById');
    // Route::put('/update','updateAppointment');
    Route::delete('/delete/{id}','deleteAppointment');
});

Route::prefix('v1/invoice')->controller(InvoiceController::class)->group(function () {
    Route::get('/','getInvoice');
    Route::post('/create','createInvoice');
    Route::get('/{id}',[InvoiceController::class,'findById']);
    Route::put('/update/{id}',[InvoiceController::class,'updateInvoice']);
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
        Route::get('{id}/schedule','getDoctorSchedule');
        Route::get('{id}/schedule/{date}','getDoctorTimeslotsByDate');
    });
    Route::post('/Appointment/create','createAppointment');
    Route::get('/time','getTime');
    
    Route::prefix('category')->group(function(){
        Route::get('/','getCategories');
        Route::Get('/{id}','categoryfindById');
       
    });
    Route::prefix('service')->group(function(){
        Route::get('/','getServices');
        Route::Get('/{id}','serviceFindById');
       
    });
});


