<?php

use App\Http\Controllers\AreaController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LabelController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\ManufacturerController;
use App\Http\Controllers\PreferenceController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\CheckLoggedIn;
use App\Models\Transaction;
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

Route::resource('/preference', PreferenceController::class)->middleware(['checkLoggedIn', 'role:0']); // preference
Route::resource('/dashboard', DashboardController::class)->middleware('checkLoggedIn');

Route::get('/login', [UserController::class, 'show_login'])->name('user.login');
Route::post('/login', [UserController::class, 'login'])->name('login');
Route::get('/register', [UserController::class, 'show_register'])->name('user.register');
Route::post('/register', [UserController::class, 'register'])->name('register');
Route::get('/logout', [UserController::class, 'logout'])->name('logout');
Route::get('/profile/{id}', [UserController::class, 'profile'])->name('user.profile');
Route::patch('/profile/update/{id}', [UserController::class, 'profile_update'])->name('user.profile_update');
Route::middleware(['checkLoggedIn' , 'role:0'])->group(function() {
    Route::group(['prefix' => 'user'], function () {
        Route::get('/', [UserController::class, 'index'])->name('user.index');
        Route::get('/edit/{id}', [UserController::class, 'edit'])->name('user.edit');
        Route::patch('/update/{id}', [UserController::class, 'update'])->name('user.update');
        Route::get('/edit/password/{id}', [UserController::class, 'edit_password'])->name('user.edit_password');
        Route::patch('/update/password/{id}', [UserController::class, 'update_password'])->name('user.update_password');
        Route::post('/deactivate/{id}', [UserController::class, 'deactivate'])->name('user.deactivate');
        Route::post('/reactivate/{id}', [UserController::class, 'reactivate'])->name('user.reactivate');
    });
});

Route::middleware('checkLoggedIn')->group(function() {
    Route::group(['prefix' => 'location'], function () {
        Route::get('/', [LocationController::class, 'index'])->name('location.index');
        Route::match(['get', 'post'], '/create', [LocationController::class, 'create'])->name('location.create');
        Route::post('/store', [LocationController::class, 'store'])->name('location.store');
        Route::get('/edit/{id}', [LocationController::class, 'edit'])->name('location.edit');
        Route::patch('/update/{id}', [LocationController::class, 'update'])->name('location.update');
        Route::delete('/delete/{id}', [LocationController::class, 'destroy'])->name('location.destroy');
        Route::get('/app', [LocationController::class, 'universal_location'])->name('location.app');
    });
});

Route::middleware('checkLoggedIn')->group(function() {
    Route::group(['prefix' => 'label'], function () {
        Route::post('/autocomplete', [LabelController::class, 'autocomplete'])->name('label.autocomplete');
    });
});

Route::middleware('checkLoggedIn')->group(function() {
    Route::group(['prefix' => 'area'], function () {
        Route::get('/', [AreaController::class, 'index'])->name('area.index');
        Route::match(['get', 'post'], '/create', [AreaController::class, 'create'])->name('area.create');
        Route::post('/store', [AreaController::class, 'store'])->name('area.store');
        Route::get('/edit/{id}', [AreaController::class, 'edit'])->name('area.edit');
        Route::patch('/update/{id}', [AreaController::class, 'update'])->name('area.update');
        Route::post('/deactivate/{id}', [AreaController::class, 'deactivate'])->name('area.deactivate');
        Route::post('/reactivate/{id}', [AreaController::class, 'reactivate'])->name('area.reactivate');
        Route::post('/autocomplete', [AreaController::class, 'autocomplete'])->name('area.autocomplete');
    });
});

Route::middleware('checkLoggedIn')->group(function() {
    Route::group(['prefix' => 'manufacturer'], function () {
        Route::get('/', [ManufacturerController::class, 'index'])->name('manufacturer.index');
        Route::match(['get', 'post'], '/create', [ManufacturerController::class, 'create'])->name('manufacturer.create');
        Route::post('/store', [ManufacturerController::class, 'store'])->name('manufacturer.store');
        Route::get('/edit/{id}', [ManufacturerController::class, 'edit'])->name('manufacturer.edit');
        Route::patch('/update/{id}', [ManufacturerController::class, 'update'])->name('manufacturer.update');
        Route::post('/deactivate/{id}', [ManufacturerController::class, 'deactivate'])->name('manufacturer.deactivate');
        Route::post('/reactivate/{id}', [ManufacturerController::class, 'reactivate'])->name('manufacturer.reactivate');
        Route::post('/autocomplete', [ManufacturerController::class, 'autocomplete'])->name('manufacturer.autocomplete');
    });
});

Route::middleware('checkLoggedIn')->group(function() {
    Route::group(['prefix' => 'product'], function () {
        Route::get('/add_inventory', [ProductController::class, 'add_inventory'])->name('product.add_inventory');
        Route::get('/add_new_inventory', [ProductController::class, 'add_new_inventory'])->name('product.add_new_inventory');
        Route::post('/product/autocomplete', [ProductController::class, 'product_autocomplete'])->name('product.product_autocomplete');
        Route::post('/store', [ProductController::class, 'store'])->name('product.store');
    });
});

Route::middleware('checkLoggedIn')->group(function() {
    Route::group(['prefix' => 'transaction'], function () {
        Route::get('/recievedValue', [TransactionController::class, 'recievedValue'])->name('transaction.recievedValue');
        Route::match(['get', 'post'], '/edit/{id}', [TransactionController::class, 'edit'])->name('transaction.edit');
        Route::post('/store', [TransactionController::class, 'store'])->name('transaction.store');
    });
});

