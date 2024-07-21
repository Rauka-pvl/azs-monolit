<?php

use App\Http\Controllers\StaffController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TabController;
use App\Http\Controllers\AZSContorller;
use App\Http\Controllers\ReportController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('/loginTab', [TabController::class, 'loginTab']);
Route::get('/checkSession', [TabController::class, 'checkSession']);
Route::post('/grade', [TabController::class, 'grade']);
Route::post('/review', [TabController::class, 'review']);
Route::middleware('auth:sanctum')->post('/staff', [AZSContorller::class, 'staff'])->middleware('check.role:1');
Route::middleware('auth:sanctum')->post('/azs', [AZSContorller::class, 'azs'])->middleware('check.role:1');
Route::middleware('auth:sanctum')->post('/azs/add', [AZSContorller::class, 'add'])->middleware('check.role:1');
Route::middleware('auth:sanctum')->post('/azs/edit', [AZSContorller::class, 'edit'])->middleware('check.role:1');
Route::middleware('auth:sanctum')->post('/azs/delete', [AZSContorller::class, 'delete'])->middleware('check.role:1');

Route::middleware('auth:sanctum')->post('/staff', [StaffController::class, 'staff'])->middleware('check.role:1');
Route::middleware('auth:sanctum')->post('/staff/add', [StaffController::class, 'add'])->middleware('check.role:1');
Route::middleware('auth:sanctum')->post('/staff/edit', [StaffController::class, 'edit'])->middleware('check.role:1');
Route::middleware('auth:sanctum')->post('/staff/delete', [StaffController::class, 'delete'])->middleware('check.role:1');
Route::middleware('auth:sanctum')->post('/staff/getAllStaff', [StaffController::class, 'getAllStaff'])->middleware('check.role:1');