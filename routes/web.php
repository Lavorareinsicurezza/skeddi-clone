<?php

use App\Http\Controllers\Admin\CompanyController;
use App\Http\Controllers\Admin\CompanyDocumentController;
use App\Http\Controllers\Admin\CompanyCourseTypeController;
use App\Http\Controllers\Admin\CourceTypeController;
use App\Http\Controllers\Admin\DocumentTypeController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\VisitTypeController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\WorkerController;
use App\Http\Controllers\Admin\TrainingPlanController;
use App\Http\Controllers\CompanyRenewalController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\CompanyVisitController;
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
    Route::get('/deadlines', [DashboardController::class, 'deadlines'])->name('deadlines');

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

    // Company documents routes
    Route::get('/company-documents/export', [CompanyDocumentController::class, 'export'])->name('company-documents.export');
    Route::resource('/company-documents', CompanyDocumentController::class);

    // Company workers routes
    Route::post('/company-workers/import', [WorkerController::class, 'import'])->name('company-workers.import');
    Route::resource('/company-workers', WorkerController::class);

    // Company course types routes
    Route::get('/company-course-types/export', [CompanyCourseTypeController::class, 'export'])->name('company-course-types.export');
    Route::resource('/company-course-types', CompanyCourseTypeController::class);

    // Training plan routes
    Route::get('/training-plan', [TrainingPlanController::class, 'index'])->name('training-plan.index');
    Route::post('/training-plan/save', [TrainingPlanController::class, 'save'])->name('training-plan.save');
    Route::post('/training-plan/renew', [TrainingPlanController::class, 'renew'])->name('training-plan.renew');
    Route::post('/training-plan/documents/get', [TrainingPlanController::class, 'getDocuments'])->name('training-plan.documents.get');
    Route::post('/training-plan/documents/store', [TrainingPlanController::class, 'storeDocument'])->name('training-plan.documents.store');
    Route::delete('/training-plan/documents/{id}', [TrainingPlanController::class, 'deleteDocument'])->name('training-plan.documents.delete');
    Route::get('/training-plan/documents/{id}/download', [TrainingPlanController::class, 'downloadDocument'])->name('training-plan.documents.download');

    //company-visit-type routes
    // Route::get('/company-visit-types/export', [CompanyVisitController::class, 'export'])->name('company-visit-types.export');
    Route::resource('/company-visit-types', CompanyVisitController::class);

    // Company renewal routes
    Route::get('/company-renewals', [CompanyRenewalController::class, 'index'])->name('company-renewals.index');
});
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
