<?php

use App\Http\Controllers\AdminUsersController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\CompanyDashboardController;
use App\Http\Controllers\CompanyUsersController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\GrnController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\MachineController;
use App\Http\Controllers\PartyController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ProcessController;
use App\Http\Controllers\SalesOrderController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\SupplierController;
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

        Route::get('/user-permissions/{id}', [PermissionController::class, 'index'])->name('admin.user.permissions');
        Route::post('/user-permissions/{id}', [PermissionController::class, 'store'])->name('admin.user.permissions.store');
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
        Route::get('/', [PartyController::class, 'index'])->name('party.index')->middleware('permission:party_master,view');
        Route::get('create', [PartyController::class, 'create'])->name('party.create')->middleware('permission:party_master,add');
        Route::post('/store', [PartyController::class, 'store'])->name('party.store')->middleware('permission:party_master,add');
        Route::get('/edit/{id}', [PartyController::class, 'edit'])->name('party.edit')->middleware('permission:party_master,edit');
        Route::put('/update/{id}', [PartyController::class, 'update'])->name('party.update')->middleware('permission:party_master,edit');
        Route::get('/destroy/{id}', [PartyController::class, 'destroy'])->name('party.destroy')->middleware('permission:party_master,delete');
    });

    Route::prefix('item')->group(function () {
        Route::get('/', [ItemController::class, 'index'])->name('item.index')->middleware('permission:item_master,view');
        Route::get('create', [ItemController::class, 'create'])->name('item.create')->middleware('permission:item_master,add');
        Route::post('/store', [ItemController::class, 'store'])->name('item.store')->middleware('permission:item_master,add');
        Route::get('/edit/{id}', [ItemController::class, 'edit'])->name('item.edit')->middleware('permission:item_master,edit');
        Route::put('/update/{id}', [ItemController::class, 'update'])->name('item.update')->middleware('permission:item_master,edit');
        Route::get('/destroy/{id}', [ItemController::class, 'destroy'])->name('item.destroy')->middleware('permission:item_master,delete');
    });

    Route::prefix('process-item/{item}')->group(function () {
        Route::get('/', [ProcessController::class, 'index'])->name('process.index')->middleware('permission:item_process,view');
        // Route::get('/create', [ProcessController::class, 'create'])->name('process.create');
        Route::get('/edit/{id}', [ProcessController::class, 'edit'])->name('process.edit')->middleware('permission:item_process,edit');
        Route::put('/update/{id}', [ProcessController::class, 'update'])->name('process.update')->middleware('permission:item_process,edit');
        Route::delete('/delete/{id}', [ProcessController::class, 'destroy'])->name('process.delete')->middleware('permission:item_process,delete');

        // Drag & Drop Sort
        Route::post('/sort', [ProcessController::class, 'sort'])->name('process.sort');
    });
    Route::post('process-item/store-multiple/{itemId}', [ProcessController::class, 'storeMultiple'])->name('process.storeMultiple')->middleware('permission:item_process,add');

    Route::get('/process-item', [ProcessController::class, 'itemList'])->name('process.items')->middleware('permission:item_process,view');
    Route::get('/process/get/{id}', [ProcessController::class, 'getProcesses'])->name('process.get');

    Route::prefix('process_master')->group(function () {
        Route::get('/', [ProcessController::class, 'process_master_index'])->name('process_master.index')->middleware('permission:process_master,view');
        Route::get('create', [ProcessController::class, 'process_master_create'])->name('process_master.create')->middleware('permission:process_master,add');
        Route::post('/store', [ProcessController::class, 'process_master_store'])->name('process_master.store')->middleware('permission:process_master,add');
        Route::get('/edit/{id}', [ProcessController::class, 'process_master_edit'])->name('process_master.edit')->middleware('permission:process_master,edit');
        Route::put('/update/{id}', [ProcessController::class, 'process_master_update'])->name('process_master.update')->middleware('permission:process_master,edit');
        Route::get('/destroy/{id}', [ProcessController::class, 'process_master_destroy'])->name('process_master.destroy')->middleware('permission:process_master,delete');
    });

    Route::prefix('employee')->group(function () {
        Route::get('/', [EmployeeController::class, 'index'])->name('employee.index')->middleware('permission:employee_master,view');
        Route::get('create', [EmployeeController::class, 'create'])->name('employee.create')->middleware('permission:employee_master,add');
        Route::post('/store', [EmployeeController::class, 'store'])->name('employee.store')->middleware('permission:employee_master,add');
        Route::get('/edit/{id}', [EmployeeController::class, 'edit'])->name('employee.edit')->middleware('permission:employee_master,edit');
        Route::put('/update/{id}', [EmployeeController::class, 'update'])->name('employee.update')->middleware('permission:employee_master,edit');
        Route::get('/destroy/{id}', [EmployeeController::class, 'destroy'])->name('employee.destroy')->middleware('permission:employee_master,delete');
    });

    Route::prefix('machine')->group(function () {
        Route::get('/', [MachineController::class, 'index'])->name('machine.index')->middleware('permission:machine_master,view');
        Route::get('create', [MachineController::class, 'create'])->name('machine.create')->middleware('permission:machine_master,add');
        Route::post('/store', [MachineController::class, 'store'])->name('machine.store')->middleware('permission:machine_master,add');
        Route::get('/edit/{id}', [MachineController::class, 'edit'])->name('machine.edit')->middleware('permission:machine_master,edit');
        Route::put('/update/{id}', [MachineController::class, 'update'])->name('machine.update')->middleware('permission:machine_master,edit');
        Route::get('/destroy/{id}', [MachineController::class, 'destroy'])->name('machine.destroy')->middleware('permission:machine_master,delete');
    });

    Route::prefix('sales_order')->group(function () {
        Route::get('/', [SalesOrderController::class, 'index'])->name('sales_order.index');
        Route::get('create', [SalesOrderController::class, 'create'])->name('sales_order.create');
        Route::post('/store', [SalesOrderController::class, 'store'])->name('sales_order.store');
        Route::get('/edit/{id}', [SalesOrderController::class, 'edit'])->name('sales_order.edit');
        Route::put('/update/{id}', [SalesOrderController::class, 'update'])->name('sales_order.update');
        Route::get('/destroy/{id}', [SalesOrderController::class, 'destroy'])->name('sales_order.destroy');
    });
    Route::get('get-item/{id}', [SalesOrderController::class, 'getItem'])->name('item.get');

    Route::prefix('supplier')->group(function () {
        Route::get('/', [SupplierController::class, 'index'])->name('supplier.index');
        Route::get('create', [SupplierController::class, 'create'])->name('supplier.create');
        Route::post('/store', [SupplierController::class, 'store'])->name('supplier.store');
        Route::get('/edit/{id}', [SupplierController::class, 'edit'])->name('supplier.edit');
        Route::put('/update/{id}', [SupplierController::class, 'update'])->name('supplier.update');
        Route::get('/destroy/{id}', [SupplierController::class, 'destroy'])->name('supplier.destroy');
    });
    Route::get('/get-unit-no/{customer}', [SupplierController::class, 'getUnitNo'])->name('get.unit_no');
    Route::get('/get-salesorder-by-id/{id}', [SupplierController::class, 'getSalesOrderById'])
        ->name('get.salesorder.by.id');
    Route::get('/get-item-details/{id}', [SupplierController::class, 'getItemDetails'])->name('get.item.by.id');

    Route::prefix('grn')->group(function () {
        Route::get('/', [GrnController::class, 'index'])->name('grn.index');
        Route::get('create', [GrnController::class, 'create'])->name('grn.create');
        Route::post('/store', [GrnController::class, 'store'])->name('grn.store');
        Route::get('/edit/{id}', [GrnController::class, 'edit'])->name('grn.edit');
        Route::put('/update/{id}', [GrnController::class, 'update'])->name('grn.update');
        Route::get('/destroy/{id}', [GrnController::class, 'destroy'])->name('grn.destroy');
    });
    Route::get('/get-parties', [GrnController::class, 'getParties'])->name('get.parties.by.category');
    Route::get('/get-po', [GrnController::class, 'getPoNumbers'])->name('get.po.by.party');
    Route::get('/grn/get-po-details', [GrnController::class, 'getPoDetails'])->name('grn.getPoDetails');
    Route::get('/grn/get-po-items', [GrnController::class, 'getPoItems'])->name('grn.getPoItems');
    Route::get('/grn/get-item-by-unit', [GrnController::class, 'getItemByUnit'])->name('grn.getItemByUnit');
    Route::get('/grn/get-item-by-part', [GrnController::class, 'getItemByPart'])->name('grn.getItemByPart');
});
