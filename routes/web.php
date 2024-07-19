<?php
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AZSContorller;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\TabController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Storage;

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

Route::get('/storage/{filename}', function ($filename) {
    $path = storage_path('app/public/' . $filename);

    if (!File::exists($path)) {
        abort(404);
    }

    $file = File::get($path);
    $type = File::mimeType($path);

    $response = Response::make($file, 200);
    $response->header("Content-Type", $type);

    return $response;
})->where('filename', '.*');

Route::get('/resources/js/{filename}', function ($filename) {
    $path = resource_path('js/' . $filename);

    if (!File::exists($path)) {
        abort(404);
    }

    $file = File::get($path);
    $type = File::mimeType($path);

    $response = Response::make($file, 200);
    $response->header("Content-Type", $type);

    return $response;
})->where('filename', '.*');

Route::get('/resources/css/{filename}', function ($filename) {
    $path = resource_path('css/' . $filename);

    if (!File::exists($path)) {
        abort(404);
    }

    $file = File::get($path);
    $type = File::mimeType($path);

    $response = Response::make($file, 200);
    $response->header("Content-Type", $type);

    return $response;
})->where('filename', '.*');

Route::get('/resources/sass/{filename}', function ($filename) {
    $path = resource_path('sass/' . $filename);

    if (!File::exists($path)) {
        abort(404);
    }

    $file = File::get($path);
    $type = File::mimeType($path);

    $response = Response::make($file, 200);
    $response->header("Content-Type", $type);

    return $response;
})->where('filename', '.*');

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::get('/AZS', [AZSContorller::class, 'index'])->name('AZS')->middleware('check.role:1');
Route::get('/staff', [StaffController::class, 'index'])->name('staff')->middleware('check.role:1');
Route::get('/staff/review/{staff}', [StaffController::class, 'review'])->name('review')->middleware('check.role:1');
Route::get('/report', [ReportController::class, 'index'])->name('report')->middleware('check.role:1');


