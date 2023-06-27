<?php

use App\Http\Controllers\AppController;
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

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/{all?}', [AppController::class, 'index'])->where('all', '^(?!api).*$');
//Route::get('/{all?}', [AppController::class, 'index'])->where('all', '^(?!api)|^(?!test).*$');
//Route::get('test', [AppController::class, 'test']);
