<?php

use App\Http\Controllers\Admin\CompanyController;
use App\Http\Controllers\Admin\CourceTypeController;
use App\Http\Controllers\Admin\DocumentTypeController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\VisitTypeController;
use App\Http\Controllers\Admin\SettingController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\DashboardController;

// Public routes
Route::middleware('guest')->group(function () {
    // Login routes
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);

    // Registration routes
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);

    // Password reset routes
    Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
});

// Protected routes
Route::middleware('auth')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Company import/export routes
    Route::get('/companies/export', [CompanyController::class, 'export'])->name('companies.export');
    Route::post('/companies/import', [CompanyController::class, 'import'])->name('companies.import');
    Route::get('/companies/template', [CompanyController::class, 'downloadTemplate'])->name('companies.template');

    Route::resource('companies', CompanyController::class);

    // User import/export routes
    Route::get('/users/export', [UserController::class, 'export'])->name('users.export');
    Route::post('/users/import', [UserController::class, 'import'])->name('users.import');
    Route::get('/users/template', [UserController::class, 'downloadTemplate'])->name('users.template');

    //Cource Type routes
    Route::resource('course-types', CourceTypeController::class);

    //Document Type routes
    Route::resource('document-types', DocumentTypeController::class);

    //Visit Type routes
    Route::resource('visit-types', VisitTypeController::class);

    // Settings routes
    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::put('/settings', [SettingController::class, 'update'])->name('settings.update');
    Route::post('/settings/test', [SettingController::class, 'testSmtp'])->name('settings.test');

    Route::resource('users', UserController::class);

    // API routes for company selection
    Route::get('/api/companies', [DashboardController::class, 'getCompanies'])->name('api.companies');
    Route::post('/api/select-company', [DashboardController::class, 'selectCompany'])->name('api.select-company');

    // Selected company detail route
    Route::get('/selected-company/detail', [CompanyController::class, 'showSelectedCompany'])->name('selected-company.detail');
});
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
