<?php

use App\Http\Controllers\AreaController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EquipmentController;
use App\Http\Controllers\LabelController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\ManufacturerController;
use App\Http\Controllers\PreferenceController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UnitController;
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
    Route::resource('/equipment', EquipmentController::class)->middleware(['checkLoggedIn', 'role:0']);
    Route::resource('/dashboard', DashboardController::class)->middleware('checkLoggedIn');

    Route::group(['prefix' => 'user'], function () {
        Route::get('/login', [UserController::class, 'show_login'])->name('user.login');
        Route::post('/login', [UserController::class, 'login'])->name('login');

        Route::get('/register', [UserController::class, 'show_register'])->name('user.register');
        Route::post('/register', [UserController::class, 'register'])->name('register');

        Route::get('/logout', [UserController::class, 'logout'])->name('logout');

        Route::get('/profile/{id}', [UserController::class, 'profile'])->name('user.profile');
        Route::patch('/profile/update/{id}', [UserController::class, 'profile_update'])->name('user.profile_update');
        
        Route::middleware(['checkLoggedIn' , 'role:0'])->group(function() {
            Route::get('/', [UserController::class, 'index'])->name('user.index');
            Route::get('/create', [UserController::class, 'create'])->name('user.create');
            Route::post('/store', [UserController::class, 'store'])->name('user.store');
            Route::get('/edit/{id}', [UserController::class, 'edit'])->name('user.edit');
            Route::patch('/update/{id}', [UserController::class, 'update'])->name('user.update');
            Route::get('/edit/password/{id}', [UserController::class, 'edit_password'])->name('user.edit_password');
            Route::patch('/update/password/{id}', [UserController::class, 'update_password'])->name('user.update_password');
            Route::post('/deactivate/{id}', [UserController::class, 'deactivate'])->name('user.deactivate');
            Route::post('/reactivate/{id}', [UserController::class, 'reactivate'])->name('user.reactivate');
        });
    });

    Route::middleware(['checkLoggedIn', 'role:0'])->group(function() {
        Route::group(['prefix' => 'location'], function () {
            Route::get('/', [LocationController::class, 'index'])->name('location.index');
            Route::match(['get', 'post'], '/create', [LocationController::class, 'create'])->name('location.create');
            Route::post('/store', [LocationController::class, 'store'])->name('location.store');
            Route::get('/edit/{id}', [LocationController::class, 'edit'])->name('location.edit');
            Route::patch('/update/{id}', [LocationController::class, 'update'])->name('location.update');
        });
    });


    Route::middleware('checkLoggedIn')->group(function() {
        Route::group(['prefix' => 'area'], function () {
            Route::middleware('role:0')->group(function () {
                Route::get('/', [AreaController::class, 'index'])->name('area.index');
                Route::match(['get', 'post'], '/create', [AreaController::class, 'create'])->name('area.create');
                Route::get('/edit/{id}', [AreaController::class, 'edit'])->name('area.edit');
                Route::patch('/update/{id}', [AreaController::class, 'update'])->name('area.update');
                Route::post('/deactivate/{id}', [AreaController::class, 'deactivate'])->name('area.deactivate');
                Route::post('/reactivate/{id}', [AreaController::class, 'reactivate'])->name('area.reactivate');
            });
            Route::post('/store', [AreaController::class, 'store'])->name('area.store');
            Route::post('/autocomplete', [AreaController::class, 'autocomplete'])->name('area.autocomplete');
        });
    });


    Route::middleware('checkLoggedIn')->group(function() {
        Route::group(['prefix' => 'manufacturer'], function () {
            Route::middleware('role:0')->group(function () {
                Route::get('/', [ManufacturerController::class, 'index'])->name('manufacturer.index');
                Route::match(['get', 'post'], '/create', [ManufacturerController::class, 'create'])->name('manufacturer.create');
                Route::get('/edit/{id}', [ManufacturerController::class, 'edit'])->name('manufacturer.edit');
                Route::patch('/update/{id}', [ManufacturerController::class, 'update'])->name('manufacturer.update');
                Route::post('/deactivate/{id}', [ManufacturerController::class, 'deactivate'])->name('manufacturer.deactivate');
                Route::post('/reactivate/{id}', [ManufacturerController::class, 'reactivate'])->name('manufacturer.reactivate');    
            });
            Route::post('/autocomplete', [ManufacturerController::class, 'autocomplete'])->name('manufacturer.autocomplete');
            Route::post('/store', [ManufacturerController::class, 'store'])->name('manufacturer.store');
        });
    });

    Route::middleware('checkLoggedIn')->group(function() {
        Route::group(['prefix' => 'product'], function () {
            Route::get('/add_inventory', [ProductController::class, 'add_inventory'])->name('product.add_inventory');
            Route::post('/product/autocomplete', [ProductController::class, 'product_autocomplete'])->name('product.product_autocomplete');
            
            Route::get('/add_new_inventory', [ProductController::class, 'add_new_inventory'])->name('product.add_new_inventory');
            Route::post('/store', [ProductController::class, 'store'])->name('product.store');
            
            Route::get('/remove_inventory', [ProductController::class, 'remove_inventory'])->name('product.remove_inventory');
            Route::post('/remove/autocomplete', [ProductController::class, 'remove_autocomplete'])->name('product.remove_autocomplete');
            
            Route::middleware('role:0')->group(function () {
                Route::get('edit/{id}', [ProductController::class, 'edit'])->name('product.edit'); 
                Route::patch('update/{id}', [ProductController::class, 'update'])->name('product.update');
            });
        });
    });

    Route::middleware('checkLoggedIn')->group(function() {
        Route::group(['prefix' => 'transaction'], function () {
            Route::get('show/{id}', [TransactionController::class, 'show'])->name('transaction.show'); // user
            Route::post('/store', [TransactionController::class, 'store'])->name('transaction.store'); // user

            Route::get('/item/{id}', [TransactionController::class, 'user_item'])->name('transaction.user_item'); // user/admin
            Route::post('/transfer', [TransactionController::class, 'transfer'])->name('transaction.transfer');

            Route::post('/transfer_item/{id}', [TransactionController::class, 'transfer_item'])->name('transaction.transfer_item');
            
            Route::put('/update/{id}', [TransactionController::class, 'update'])->name('transaction.update');
            Route::put('/transfer_update/{id}', [TransactionController::class, 'transfer_update'])->name('transaction.transfer_update');


            Route::delete('/destroy/{id}', [TransactionController::class, 'tranfer_destroy'])->name('transaction.destroy');

            Route::get('remove_show/{id}', [TransactionController::class, 'remove_show'])->name('transaction.remove_show');
            Route::post('/remove_store', [TransactionController::class, 'remove_store'])->name('transaction.remove_store');
            Route::middleware('role:0')->group(function () {
                Route::get('edit/{id}/loc_id/{loc_id}', [TransactionController::class, 'edit'])->name('transaction.edit');
                Route::get('/item/{id}/loc_id/{loc_id}', [TransactionController::class, 'item'])->name('transaction.item');
                Route::delete('/delete/{id}', [TransactionController::class, 'destroy'])->name('transaction.destroy');
                Route::get('remove_edit/{id}/loc_id/{loc_id}', [TransactionController::class, 'remove_edit'])->name('transaction.remove_edit');
            });
        });
    });

    Route::middleware('checkLoggedIn')->group(function() {
        Route::group(['prefix' => 'label'], function () {
            Route::post('/autocomplete', [LabelController::class, 'autocomplete'])->name('label.autocomplete');
        });
    });

    Route::middleware('checkLoggedIn')->group(function() {
        Route::group(['prefix' => 'unit'], function () {
            Route::post('/autocomplete', [UnitController::class, 'autocomplete'])->name('unit.autocomplete');
            Route::post('/store', [UnitController::class, 'store'])->name('unit.store');

            Route::middleware('role:0')->group(function () {
                Route::get('/', [UnitController::class, 'index'])->name('unit.index');
                Route::get('/create', [UnitController::class, 'create'])->name('unit.create');
                Route::get('/edit/{id}', [UnitController::class, 'edit'])->name('unit.edit');
                Route::patch('/update/{id}', [UnitController::class, 'update'])->name('unit.update');
            });
        });
    });

    Route::middleware('checkLoggedIn')->group(function() {
        Route::group(['prefix' => 'report'], function () {
            Route::get('/', [ReportController::class, 'index'])->name('report.index');
            Route::get('/inventory_transaction', [ReportController::class, 'inventory_transaction'])->name('report.inventory_transaction');
            Route::post('/search_inventory', [ReportController::class, 'search_inventory'])->name('report.search_inventory');
            
            Route::get('/daily_transaction', [ReportController::class, 'daily_transaction'])->name('report.daily_transaction');
            Route::post('/search_daily', [ReportController::class, 'search_daily'])->name('report.search_daily');

            Route::get('/new_stock', [ReportController::class, 'new_stock'])->name('report.new_stock');
            Route::post('/search_new_stock', [ReportController::class, 'search_new_stock'])->name('report.search_new_stock');

            Route::get('/current_stock_table', [ReportController::class, 'current_stock_table'])->name('report.current_stock_table');
            Route::get('/current_stock_list', [ReportController::class, 'current_stock_list'])->name('report.current_stock_list');
            Route::get('/low_stock_list', [ReportController::class, 'low_stock_list'])->name('report.low_stock_list');
        });
    });


    