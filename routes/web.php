<?php

use App\Http\Controllers\ServiceControl;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\UploadController;
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

Route::get('/', function () {
    return view('welcome');
});
Route::get('/invoice', function () {
    return view('invoice.index');
});