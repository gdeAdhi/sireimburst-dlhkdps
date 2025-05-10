<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\TipeKendaraanController;
use App\Http\Controllers\MerkKendaraanController;
use App\Http\Controllers\RutePerjalananController;
use App\Http\Controllers\KendaraanController;
use App\Http\Controllers\PerjalananController;
use App\Http\Controllers\ReportPerjalananController;
use App\Http\Controllers\LoginController;
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
//     echo "hello world";
// });
require __DIR__.'/auth.php';
//dashboard
Route::group(['middleware' => 'auth'], function () {
    Route::get('/',[HomeController::class, 'index'])->name('index');
    Route::middleware(['permission:User Management'])->group(function () {
        Route::resource('user', UserController::class);
    });

    Route::middleware(['permission:Role Management'])->group(function () {
        Route::resource('roles', RoleController::class);
        Route::get('/roles/{role}/permissions', [RoleController::class, 'getRolePermissions'])->name('roles.getRolePermissions');
        Route::post('roles/assign-permissions', [RoleController::class, 'assignPermissions'])->name('roles.assign-permissions');
    });

    Route::middleware(['permission:Kendaraan'])->group(function () {
        Route::resource('tipe/kendaraan', TipeKendaraanController::class)->names('tipe.kendaraan');
        Route::resource('merk/kendaraan', MerkKendaraanController::class)->names('merk.kendaraan');
        Route::resource('kendaraan', KendaraanController::class);
    });

    Route::middleware(['permission:Rute Perjalanan'])->group(function () {
        Route::get('rute/perjalanan/maps', [RutePerjalananController::class, 'showMaps'])->name('rute.perjalanan.maps');
        Route::resource('rute/perjalanan', RutePerjalananController::class)->names('rute.perjalanan');
    });

    Route::middleware(['permission:Perjalanan|Kelola Perjalanan'])->group(function () {
        Route::resource('perjalanan', PerjalananController::class);
        Route::post('/api/ors-route', [\App\Http\Controllers\ORSController::class, 'getRoute']);
    });

    Route::middleware(['permission:Kelola Perjalanan|Kelola Report Perjalanan'])->group(function () {
        Route::get('/report/perjalanan/pdf', [ReportPerjalananController::class, 'exportPdf'])->name('report.perjalanan.pdf');
    });

    Route::middleware(['permission:Kelola Perjalanan|Kelola Report Perjalanan|Report Perjalanan'])->group(function () {
        Route::resource('report/perjalanan', ReportPerjalananController::class)->names('report.perjalanan');
        Route::get('/perjalanan/by-user/{id}', [PerjalananController::class, 'getByUser']);
    });

});

//index
// Route::get('/user',[HomeController::class, 'index'])->name('index')->middleware('auth');
// Route::get('/create',[HomeController::class, 'create'])->name('user.create');
// Route::post('/store',[HomeController::class, 'store'])->name('user.store');

//CRUD
// Route::get('/edit/{id}',[HomeController::class, 'edit'])->name('user.edit');
// Route::put('/update/{id}',[HomeController::class, 'update'])->name('user.update');
// Route::delete('/delete/{id}',[HomeController::class, 'delete'])->name('user.delete');