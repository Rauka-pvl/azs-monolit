<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TabController;

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


Route::get('/tab/{zone}', [TabController::class, 'index'])->name('tab');
Route::get('/qr/{user}', [TabController::class, 'qr'])->name('qr');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
