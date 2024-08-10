<?php

use App\Http\Controllers\Client\AppointmentController as ClientAppointmentController;
use App\Http\Controllers\Manager\CategoryController;
use App\Http\Controllers\Client\CategoryController as ClientCategoryController;
use App\Http\Controllers\Client\DoctorController;
use App\Http\Controllers\Client\ScheduleController as ClientScheduleController;
use App\Http\Controllers\Client\ServiceController as ClientServiceController;
use App\Http\Controllers\Manager\AppointmentController;
use App\Http\Controllers\Manager\AuthController;
use App\Http\Controllers\Manager\CustomerController;
use App\Http\Controllers\Manager\DoctorController as ManagerDoctorController;
use App\Http\Controllers\Manager\ExportController;
use App\Http\Controllers\Manager\HistoryController;
use App\Http\Controllers\Manager\InvoiceController;
use App\Http\Controllers\Manager\NotificationController;
use App\Http\Controllers\Manager\OverviewController;
use App\Http\Controllers\Manager\RoleController;
use App\Http\Controllers\Manager\ScheduleController;
use App\Http\Controllers\Manager\ServiceController;
use App\Http\Controllers\Manager\StatisticsController;
use App\Http\Controllers\Manager\UploadController;
use App\Http\Controllers\Manager\UserController;
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
Route::post('v1/upload', [UploadController::class, 'uploadImage']);
Route::group([

    'middleware' => 'api',
    'prefix' => 'v1/auth'

], function ($router) {
    Route::post('login', [AuthController::class,'login']);
    Route::post('logout', [AuthController::class,'logout']);
    Route::post('refresh', [AuthController::class,'refresh']);
    Route::get('profile', [AuthController::class,'profile']);
    Route::post('change-password', [AuthController::class,'changePassword']);
});
// Middelware check token đăng nhập
Route::middleware('check_token')->group(function () {
Route::prefix('v1/user')->controller(UserController::class)->group(function(){
    Route::get('/','getUsers');
    Route::post('/','createUser');
    Route::get('/{id}','findById')->name('user.detail');
    Route::put('/{id}','updateUser');
});
//customer
Route::prefix('v1/customer')->controller(CustomerController::class)->group(function () {
    Route::get('/','getCutomer');
    Route::post('/','createCustomer');
    Route::get('/{id}','findById')->name('customer.detail');
    Route::put('/{id}','updateCustomer');
});
//category
Route::prefix('v1/category')->controller(CategoryController::class)->group(function(){
    Route::get('/','getCategories');
    Route::get('/{id}','findById')->name('category.detail');
    Route::post('/','createCategory');
    Route::put('/{id}','updateCategory');
    Route::delete('/{id}','deleteCategory');
});
//service
Route::prefix('v1/service')->controller(ServiceController::class)->group(function () {
    Route::get('/','getServices');
    Route::post('/','createService');
    Route::get('/{id}','findById')->name('service.detail');
    Route::put('/{id}','updateService');
    Route::delete('/{id}','deleteService');
});
//history
Route::prefix('v1/history')->controller(HistoryController::class)->group(function () {
    Route::get('/','getHistory');
    Route::post('/','createHistory');
    Route::get('/{id}','findById')->name('history.detail');
    Route::put('/{id}','updateHistory');
});
//appointment
Route::prefix('v1/appointment')->controller(AppointmentController::class)->group(function () {
    Route::get('/','getAppointment');
    Route::post('/','createAppointment');
    Route::get('/{id}','findById')->name('appointment.detail');
    Route::put('/{id}','updateAppointment');
    Route::delete('/{id}','deleteAppointment');
});
//schedule
Route::prefix('v1/schedule')->controller(ScheduleController::class)->group(function () {
    Route::get('/','getSchedule');
    Route::post('/','createSchedule');
    Route::delete('/{date}/{doctor_id}','deleteSchedule');
});
//invoice
Route::prefix('v1/invoice')->controller(InvoiceController::class)->group(function () {
    Route::get('/','getInvoice');
    Route::post('/','createInvoice');
    Route::get('/{id}','findById')->name('invoice.detail');
    Route::put('/{id}','updateInvoice');
    Route::post('/print/{id}','printInvoicePdf');
  
});
//role
Route::prefix('v1/role')->controller(RoleController::class)->group(function(){
    Route::get('/','getRoles');
    Route::get('/{id}','findByID');
    Route::put('/update/{id}','updatePermissions');
});
//Hiển thị doctor trong đặt lịch
Route::prefix('v1')->group(function(){
    Route::prefix('/doctor')->controller(ManagerDoctorController::class)->group(function(){
        Route::get('/','getDoctor');
    });
    //get lịch làm việc doctor
    Route::prefix('/schedule')->controller(ManagerDoctorController::class)->group(function(){
        Route::get('/{id}','getDoctorScheduleWithTimeslots');
        Route::get('/{id}/{date}','getDoctorTimeslotsByDate');
      
    });
    Route::get('/time',[ManagerDoctorController::class,'getTime']);
});
//tongquan
Route::prefix('v1/overview')->controller(OverviewController::class)->group(function(){
    Route::get('/','totalOverView');
    Route::get('/invoice','monthlyStatistics');
    Route::get('/appointment','appointmentStatistics');
    
});

//Xuất file excel
Route::prefix('v1/export')->controller(ExportController::class)->group(function(){
    Route::post('/service','exportService');
    Route::post('/invoice','exportInvoice');
    Route::post('/appointment','exportAppointment');
    Route::post('/history','exportHistory');
});

//Thống kê
Route::prefix('v1/statistics')->controller(StatisticsController::class)->group(function(){
    Route::get('/invoice','getStatistics');
    Route::get('/service','getService');
    Route::get('/history','getHistories');
    Route::get('/appointment','getAppointment');
});
//Thong bao
Route::prefix('v1/notification')->controller(NotificationController::class)->group(function(){
    Route::get('/','getNoti');
    Route::put('/{id}','updateNotification');
});


});

//route khách hàng
    //get Doctor
Route::prefix('v2/{lang}')->group(function(){
    Route::prefix('/doctor')->controller(DoctorController::class)->group(function(){
        Route::get('/','getDoctor');
        Route::Get('/{id}','getDoctorDetail')->name('doctor.detail');
    });
    //get lịch làm việc doctor
    Route::prefix('/schedule')->controller(ClientScheduleController::class)->group(function(){
        Route::get('/{id}','getDoctorScheduleWithTimeslots');
        Route::get('/{id}/{date}','getDoctorTimeslotsByDate');
    });
    Route::get('/time',[ClientScheduleController::class,'getTime']);
    //get Category
    Route::prefix('/category')->controller(ClientCategoryController::class)->group(function(){
        Route::get('/','getCategories');
        Route::Get('/{id}','categoryfindById')->name('category.detail');
       
    });
    //get Service
    Route::prefix('/service')->controller(ClientServiceController::class)->group(function(){
        Route::get('/','getServices');
        Route::Get('/{id}','serviceFindById')->name('service.detail');
       
    });
    Route::post('/appointment',[ClientAppointmentController::class,'createAppointment'])->middleware('passes.appointment');
});


Route::prefix('v1/translate')->group(function(){
    Route::prefix('/category')->controller(CategoryController::class)->group(function(){
        Route::get('/{id}','getCateTranslate');
        Route::patch('/{id}','categoryTranslate');
    });
    Route::prefix('/service')->controller(ServiceController::class)->group(function(){
        Route::get('/{id}','getServiceTrans');
        Route::patch('/{id}','serviceTranslate');
    });
    Route::prefix('/doctor')->controller(UserController::class)->group(function(){
        Route::get('/{id}','getDoctorTrans');
        Route::patch('/{id}','doctorTranslate');
    });

});
