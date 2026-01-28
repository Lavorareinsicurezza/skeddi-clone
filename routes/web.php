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
use App\Http\Controllers\Admin\OperatingLocationController;
use App\Http\Controllers\CompanyRenewalController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\PermissionController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\ChartController;
use App\Http\Controllers\CompanyVisitController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Artisan;
use PhpOffice\PhpSpreadsheet\Chart\Chart;

// Public routes
Route::middleware('guest')->group(function () {
    // Login routes
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);

    // Registration routes
    // Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    // Route::post('/register', [RegisterController::class, 'register']);

    // Password reset routes
    // Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    // Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
});

// Protected routes
Route::middleware('auth')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->middleware('ensure.permission:dashboard')->name('dashboard');
    Route::get('/deadlines', [DashboardController::class, 'deadlines'])->middleware('ensure.permission:deadlines')->name('deadlines');
    Route::get('/deadlines/export', [DashboardController::class, 'exportDeadlines'])->middleware('ensure.permission:deadlines,view')->name('deadlines.export');
    Route::post('/send-emails', [DashboardController::class, 'sendEmails'])->middleware('ensure.permission:dashboard')->name('send-emails');

    // Company import/export routes
    Route::get('/companies/export', [CompanyController::class, 'export'])->middleware('ensure.permission:companies,view')->name('companies.export');
    Route::get('/companies/{id}/export', [CompanyController::class, 'exportSingle'])->middleware('ensure.permission:companies,view')->name('companies.export.single');
    Route::post('/companies/import', [CompanyController::class, 'import'])->middleware('ensure.permission:companies,create')->name('companies.import');
    Route::get('/companies/template', [CompanyController::class, 'downloadTemplate'])->middleware('ensure.permission:companies,view')->name('companies.template');

    Route::resource('companies', CompanyController::class)->middleware('ensure.permission:companies');

    // User import/export routes
    Route::get('/users/export', [UserController::class, 'export'])->middleware('ensure.permission:users,view')->name('users.export');
    Route::post('/users/import', [UserController::class, 'import'])->middleware('ensure.permission:users,create')->name('users.import');
    Route::get('/users/template', [UserController::class, 'downloadTemplate'])->middleware('ensure.permission:users,view')->name('users.template');

    //Cource Type routes
    Route::resource('course-types', CourceTypeController::class)->middleware('ensure.permission:course-types');

    //Document Type routes
    Route::resource('document-types', DocumentTypeController::class)->middleware('ensure.permission:document-types');

    //Visit Type routes
    Route::resource('visit-types', VisitTypeController::class)->middleware('ensure.permission:visit-types');

    // Roles & Permissions
    Route::resource('roles', RoleController::class)->middleware('ensure.permission:roles');
    Route::resource('permissions', PermissionController::class)->middleware('ensure.permission:permissions');

    // Settings routes
    Route::get('/settings', [SettingController::class, 'index'])->middleware('ensure.permission:settings')->name('settings.index');
    Route::put('/settings', [SettingController::class, 'update'])->middleware('ensure.permission:settings,edit')->name('settings.update');
    Route::post('/settings/test', [SettingController::class, 'testSmtp'])->middleware('ensure.permission:settings,view')->name('settings.test');

    // User OTP and Password Reset
    Route::post('/users/send-otp', [UserController::class, 'sendOtp'])->name('users.send-otp');
    Route::post('/users/reset-password', [UserController::class, 'resetPassword'])->name('users.reset-password');

    Route::resource('users', UserController::class)->middleware('ensure.permission:users');

    // API routes for company selection
    Route::get('/api/companies', [DashboardController::class, 'getCompanies'])->name('api.companies');
    Route::post('/api/select-company', [DashboardController::class, 'selectCompany'])->name('api.select-company');

    // Selected company detail route
    Route::get('/selected-company/detail', [CompanyController::class, 'showSelectedCompany'])->middleware('ensure.permission:selected-company')->name('selected-company.detail');

    // Company documents routes
    Route::get('/company-documents/export', [CompanyDocumentController::class, 'export'])->middleware('ensure.permission:company-documents,view')->name('company-documents.export');
    Route::resource('/company-documents', CompanyDocumentController::class)->middleware('ensure.permission:company-documents');

    // Company workers routes
    Route::post('/company-workers/import', [WorkerController::class, 'import'])->middleware('ensure.permission:company-workers,create')->name('company-workers.import');
    Route::get('/company-workers/export', [WorkerController::class, 'export'])->middleware('ensure.permission:company-workers,view')->name('company-workers.export');
    Route::resource('/company-workers', WorkerController::class)->middleware('ensure.permission:company-workers');

    // Operating Locations routes
    Route::get('/operating-locations/export', [OperatingLocationController::class, 'export'])->middleware('ensure.permission:operating-locations,view')->name('operating-locations.export');
    Route::resource('operating-locations', OperatingLocationController::class)->middleware('ensure.permission:operating-locations');

    // Company course types routes
    Route::get('/company-course-types/export', [CompanyCourseTypeController::class, 'export'])->middleware('ensure.permission:company-course-types,view')->name('company-course-types.export');
    Route::resource('/company-course-types', CompanyCourseTypeController::class)->middleware('ensure.permission:company-course-types');

    // Training plan routes
    Route::get('/training-plan', [TrainingPlanController::class, 'index'])->middleware('ensure.permission:training-plan')->name('training-plan.index');
    Route::get('/training-plan/export', [TrainingPlanController::class, 'export'])->middleware('ensure.permission:training-plan,view')->name('training-plan.export');
    Route::post('/training-plan/save', [TrainingPlanController::class, 'save'])->middleware('ensure.permission:training-plan-edit,view')->name('training-plan.save');
    Route::post('/training-plan/renew', [TrainingPlanController::class, 'renew'])->middleware('ensure.permission:training-plan-edit,view')->name('training-plan.renew');
    Route::post('/training-plan/documents/get', [TrainingPlanController::class, 'getDocuments'])->middleware('ensure.permission:training-plan-edit,view')->name('training-plan.documents.get');
    Route::post('/training-plan/documents/store', [TrainingPlanController::class, 'storeDocument'])->middleware('ensure.permission:training-plan-edit,view')->name('training-plan.documents.store');
    Route::delete('/training-plan/documents/{id}', [TrainingPlanController::class, 'deleteDocument'])->middleware('ensure.permission:training-plan-edit,view')->name('training-plan.documents.delete');
    Route::get('/training-plan/documents/{id}/download', [TrainingPlanController::class, 'downloadDocument'])->middleware('ensure.permission:training-plan-edit,view')->name('training-plan.documents.download');

    //company-visit-type routes
    // Route::get('/company-visit-types/export', [CompanyVisitController::class, 'export'])->middleware('ensure.permission:company-visit-types,view')->name('company-visit-types.export');
    Route::resource('/company-visit-types', CompanyVisitController::class)->middleware('ensure.permission:company-visit-types');

    // Company renewal routes
    // Route::get('/company-renewals', [CompanyRenewalController::class, 'index'])->middleware('ensure.permission:company-renewals')->name('company-renewals.index');

    // Organizational Chart Routes
    Route::get('/organization-chart', [ChartController::class, 'index'])->middleware('ensure.permission:chart')->name('chart.index');
    Route::get('/organization-chart/chart', [ChartController::class, 'detail'])->middleware('ensure.permission:chart')->name('chart.detail');
    Route::get('/organization-chart/pdf', [ChartController::class, 'downloadPdf'])->middleware('ensure.permission:chart')->name('chart.pdf');

});
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::get('/run-expiry-comand', function(){
    Artisan::call('expiry:check-and-mail');
    return 'Done';
});
