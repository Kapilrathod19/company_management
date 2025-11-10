<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\CompanyDashboardController;
use App\Http\Controllers\CompanyUsersController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SettingController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;


Route::get('/clear', function () {
    Artisan::call('config:clear');
    Artisan::call('cache:clear');
    Artisan::call('route:clear');
    return 'Route cache cleared successfully!';
});

Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
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
    });
});

Route::middleware(['auth', 'checkRole:company'])->group(function () {
    Route::prefix('company')->group(function () {
        Route::get('dashboard', [CompanyDashboardController::class, 'index'])->name('company.dashboard');

        Route::prefix('company_profile')->group(function () {
            Route::get('/', [CompanyDashboardController::class, 'profile'])->name('company.profile');
            Route::post('store', [CompanyDashboardController::class, 'profile_store'])->name('company_profile.store');
            Route::post('change_password', [CompanyDashboardController::class, 'change_password'])->name('company_profile.change_password');
        });
        
        Route::prefix('users')->group(function () {
            Route::get('/', [CompanyUsersController::class, 'users'])->name('company.users');
            Route::get('create_user', [CompanyUsersController::class, 'create_user'])->name('company.create_user');
            Route::post('/store', [CompanyUsersController::class, 'store_user'])->name('company.store_user');
            Route::get('/edit/{id}', [CompanyUsersController::class, 'edit_user'])->name('company.edit_user');
            Route::put('/update/{id}', [CompanyUsersController::class, 'update_user'])->name('company.update_user');
            Route::get('/destroy/{id}', [CompanyUsersController::class, 'destroy_user'])->name('company.destroy_user');
        });

    });
});
