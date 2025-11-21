<?php

use App\Http\Controllers\AdminUsersController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\CompanyDashboardController;
use App\Http\Controllers\CompanyUsersController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\MachineController;
use App\Http\Controllers\PartyController;
use App\Http\Controllers\ProcessController;
use App\Http\Controllers\SalesOrderController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;


Route::get('/clear', function () {
    Artisan::call('config:clear');
    Artisan::call('cache:clear');
    Artisan::call('route:clear');
    return 'Route cache cleared successfully!';
});

Route::get('/', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('check-login', [AuthController::class, 'checkLogin'])->name('check.login');
Route::get('logout', [AuthController::class, 'Logout'])->name('logout');

Route::get('/get-cities/{state_id}', [DashboardController::class, 'getCities'])->name('get.cities');

Route::middleware(['auth', 'checkRole:admin'])->group(function () {

    Route::prefix('admin')->group(function () {
        Route::get('dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
        Route::prefix('profile')->group(function () {
            Route::get('/', [AuthController::class, 'profile'])->name('admin.profile');
            Route::post('store', [AuthController::class, 'profile_store'])->name('profile.store');
            Route::post('change_password', [AuthController::class, 'change_password'])->name('profile.change_password');
        });

        Route::prefix('site_setting')->group(function () {
            Route::get('/', [SettingController::class, 'index'])->name('admin.site_setting');
            Route::post('store', [SettingController::class, 'store'])->name('site_setting.store');
        });

        //Company
        Route::prefix('company')->group(function () {
            Route::get('/', [CompanyController::class, 'company'])->name('admin.company');
            Route::get('create_company', [CompanyController::class, 'create_company'])->name('admin.create_company');
            Route::post('/store', [CompanyController::class, 'store_company'])->name('admin.store_company');
            Route::get('/edit/{id}', [CompanyController::class, 'edit_company'])->name('admin.edit_company');
            Route::put('/update/{id}', [CompanyController::class, 'update_company'])->name('admin.update_company');
            Route::get('/destroy/{id}', [CompanyController::class, 'destroy_company'])->name('admin.destroy_company');
        });

        Route::prefix('admin_users')->group(function () {
            Route::get('/', [AdminUsersController::class, 'users'])->name('admin.users');
            Route::get('create_user', [AdminUsersController::class, 'create_user'])->name('admin.create_user');
            Route::post('/store', [AdminUsersController::class, 'store_user'])->name('admin.store_user');
            Route::get('/edit/{id}', [AdminUsersController::class, 'edit_user'])->name('admin.edit_user');
            Route::put('/update/{id}', [AdminUsersController::class, 'update_user'])->name('admin.update_user');
            Route::get('/destroy/{id}', [AdminUsersController::class, 'destroy_user'])->name('admin.destroy_user');
        });
    });
});

Route::middleware(['auth:company_user'])->prefix('user')->group(function () {

    Route::get('dashboard', [UserController::class, 'index'])->name('user.dashboard');

    Route::prefix('user_profile')->group(function () {
        Route::get('/', [UserController::class, 'profile'])->name('user.profile');
        Route::post('store', [UserController::class, 'profile_store'])->name('user_profile.store');
        Route::post('change_password', [UserController::class, 'change_password'])->name('user_profile.change_password');
    });

    Route::prefix('party')->group(function () {
        Route::get('/', [PartyController::class, 'index'])->name('party.index');
        Route::get('create', [PartyController::class, 'create'])->name('party.create');
        Route::post('/store', [PartyController::class, 'store'])->name('party.store');
        Route::get('/edit/{id}', [PartyController::class, 'edit'])->name('party.edit');
        Route::put('/update/{id}', [PartyController::class, 'update'])->name('party.update');
        Route::get('/destroy/{id}', [PartyController::class, 'destroy'])->name('party.destroy');
    });

    Route::prefix('item')->group(function () {
        Route::get('/', [ItemController::class, 'index'])->name('item.index');
        Route::get('create', [ItemController::class, 'create'])->name('item.create');
        Route::post('/store', [ItemController::class, 'store'])->name('item.store');
        Route::get('/edit/{id}', [ItemController::class, 'edit'])->name('item.edit');
        Route::put('/update/{id}', [ItemController::class, 'update'])->name('item.update');
        Route::get('/destroy/{id}', [ItemController::class, 'destroy'])->name('item.destroy');
    });

    Route::prefix('process-item/{item}')->group(function () {
        Route::get('/', [ProcessController::class, 'index'])->name('process.index');
        // Route::get('/create', [ProcessController::class, 'create'])->name('process.create');
        Route::get('/edit/{id}', [ProcessController::class, 'edit'])->name('process.edit');
        Route::put('/update/{id}', [ProcessController::class, 'update'])->name('process.update');
        Route::delete('/delete/{id}', [ProcessController::class, 'destroy'])->name('process.delete');
        
        // Drag & Drop Sort
        Route::post('/sort', [ProcessController::class, 'sort'])->name('process.sort');
    });
    Route::post('process-item/store-multiple/{itemId}', [ProcessController::class, 'storeMultiple'])->name('process.storeMultiple');

    Route::get('/process-item', [ProcessController::class, 'itemList'])->name('process.items');
    Route::get('/process/get/{id}', [ProcessController::class, 'getProcesses'])->name('process.get');

    Route::prefix('process_master')->group(function () {
        Route::get('/', [ProcessController::class, 'process_master_index'])->name('process_master.index');
        Route::get('create', [ProcessController::class, 'process_master_create'])->name('process_master.create');
        Route::post('/store', [ProcessController::class, 'process_master_store'])->name('process_master.store');
        Route::get('/edit/{id}', [ProcessController::class, 'process_master_edit'])->name('process_master.edit');
        Route::put('/update/{id}', [ProcessController::class, 'process_master_update'])->name('process_master.update');
        Route::get('/destroy/{id}', [ProcessController::class, 'process_master_destroy'])->name('process_master.destroy');
    });

    Route::prefix('employee')->group(function () {
        Route::get('/', [EmployeeController::class, 'index'])->name('employee.index');
        Route::get('create', [EmployeeController::class, 'create'])->name('employee.create');
        Route::post('/store', [EmployeeController::class, 'store'])->name('employee.store');
        Route::get('/edit/{id}', [EmployeeController::class, 'edit'])->name('employee.edit');
        Route::put('/update/{id}', [EmployeeController::class, 'update'])->name('employee.update');
        Route::get('/destroy/{id}', [EmployeeController::class, 'destroy'])->name('employee.destroy');
    });
    
    Route::prefix('machine')->group(function () {
        Route::get('/', [MachineController::class, 'index'])->name('machine.index');
        Route::get('create', [MachineController::class, 'create'])->name('machine.create');
        Route::post('/store', [MachineController::class, 'store'])->name('machine.store');
        Route::get('/edit/{id}', [MachineController::class, 'edit'])->name('machine.edit');
        Route::put('/update/{id}', [MachineController::class, 'update'])->name('machine.update');
        Route::get('/destroy/{id}', [MachineController::class, 'destroy'])->name('machine.destroy');
    });
    
    Route::prefix('sales_order')->group(function () {
        Route::get('/', [SalesOrderController::class, 'index'])->name('sales_order.index');
        Route::get('create', [SalesOrderController::class, 'create'])->name('sales_order.create');
        Route::post('/store', [SalesOrderController::class, 'store'])->name('sales_order.store');
        Route::get('/edit/{id}', [SalesOrderController::class, 'edit'])->name('sales_order.edit');
        Route::put('/update/{id}', [SalesOrderController::class, 'update'])->name('sales_order.update');
        Route::get('/destroy/{id}', [SalesOrderController::class, 'destroy'])->name('sales_order.destroy');
    });
});
