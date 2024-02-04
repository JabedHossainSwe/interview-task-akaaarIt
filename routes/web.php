<?php

use App\Http\Controllers\CsvDataController;
use App\Http\Controllers\CsvUploadController;
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

Route::post('/upload', [CsvUploadController::class, 'upload'])->name('upload');
Route::get('data', [CsvUploadController::class, 'getData'])->name('data.get');
Route::get('data', [CsvDataController::class, 'getData'])->name('data.get');
