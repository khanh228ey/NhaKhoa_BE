<?php

use App\Http\Controllers\ServiceControl;
use App\Http\Controllers\ServiceController;
use App\Models\Service;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('admin');
// });
Route::get('/', [ServiceController::class, 'getService'])->name('getService');
Route::get('/add',[ServiceController::class, 'fromadd'])->name('fromadd');
Route::post('/add-service',[ServiceController::class, 'add'])->name('add');
Route::get('/{id}',[ServiceController::class, 'delete'])->name('delete');
Route::get('/edit/{id}',[ServiceController::class, 'edit'])->name('edit');
Route::post('/update',[ServiceController::class, 'update'])->name('update');

Route::resource('service', ServiceControl::class);