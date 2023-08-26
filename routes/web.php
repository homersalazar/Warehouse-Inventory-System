<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\CheckLoggedIn;
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
    return view('index');
});

Route::get('/login', [UserController::class, 'show_login'])->name('user.login');
Route::post('/login', [UserController::class, 'login'])->name('login');
Route::get('/register', [UserController::class, 'show_register'])->name('user.register');
Route::post('/register', [UserController::class, 'register'])->name('register');
Route::get('/logout', [UserController::class, 'logout'])->name('logout');

Route::middleware('checkLoggedIn')->group(function() {
    Route::resource('/dashboard', DashboardController::class);
});

Route::middleware('checkLoggedIn')->group(function() {
    Route::group(['prefix' => 'location'], function () {
        Route::get('/', [LocationController::class, 'index'])->name('location.index');
        Route::match(['get', 'post'], '/create', [LocationController::class, 'create'])->name('location.create');
        Route::post('/store', [LocationController::class, 'store'])->name('location.store');
        Route::get('/edit/{id}', [LocationController::class, 'edit'])->name('location.edit');
        Route::patch('/update/{id}', [LocationController::class, 'update'])->name('location.update');
        Route::delete('/delete/{id}', [LocationController::class, 'destroy'])->name('location.destroy');
    });
});