<?php

use App\Http\Controllers\HomeController;
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

//dashboard
Route::get('/dashboard',[HomeController::class, 'dashboard'])->name('dashboard');

//login 
Route::get('/login',[LoginController::class, 'index'])->name('login')->middleware('guest');
Route::post('/login_process',[LoginController::class, 'login_process'])->name('login_process')->middleware('guest');


//index
Route::get('/user',[HomeController::class, 'index'])->name('index')->middleware('auth');
Route::get('/create',[HomeController::class, 'create'])->name('user.create');
Route::post('/store',[HomeController::class, 'store'])->name('user.store');

//CRUD
Route::get('/edit/{id}',[HomeController::class, 'edit'])->name('user.edit');
Route::put('/update/{id}',[HomeController::class, 'update'])->name('user.update');
Route::delete('/delete/{id}',[HomeController::class, 'delete'])->name('user.delete');