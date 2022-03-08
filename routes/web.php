<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AjaxController;
/*
 
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

Route::get('/ajaxdata',[AjaxController::class, 'index'])->name('ajaxdata');

Route::get('/ajaxdata/getdata',[AjaxController::class, 'getdata'])->name('ajaxdata.getdata');

Route::post('/ajaxdata/submitdata',[AjaxController::class, 'postdata'])->name('ajaxdata.postdata');

Route::get('ajaxdata/fetchdata',[AjaxController::class, 'fetchdata'])->name('ajaxdata.fetchdata');

Route::get('ajaxdata/removedata',[AjaxController::class, 'removedata'])->name('ajaxdata.removedata');
 