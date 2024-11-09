<?php

use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ChangePasswordController;
use App\Http\Controllers\DoctorDetailController;
use App\Http\Controllers\HealthEvaluationController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InfoUserController;
use App\Http\Controllers\InvestigationReportController;
use App\Http\Controllers\InvestigationReportTypeController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\PatientDetailController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\PurchaseorderController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\ResetController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SessionsController;
use App\Http\Controllers\SourceCompanyController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\UomTypeController;
use App\Http\Controllers\UsersController;
use App\Models\HealthEvaluation;
use App\Models\InvestigationReportType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
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


Route::group(['middleware' => 'auth'], function () {

    Route::get('/', [HomeController::class, 'home']);
    Route::get('dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('billing', function () {
        return view('billing');
    })->name('billing');

    Route::get('profile', function () {
        return view('profile');
    })->name('profile');

    Route::get('rtl', function () {
        return view('rtl');
    })->name('rtl');

    Route::get('user-management', function () {
        return view('laravel-examples/user-management');
    })->name('user-management');

    Route::get('tables', function () {
        return view('tables');
    })->name('tables');

    Route::get('virtual-reality', function () {
        return view('virtual-reality');
    })->name('virtual-reality');

    Route::get('static-sign-in', function () {
        return view('static-sign-in');
    })->name('sign-in');

    Route::get('static-sign-up', function () {
        return view('static-sign-up');
    })->name('sign-up');

    Route::get('/logout', [SessionsController::class, 'destroy']);
    Route::get('/user-profile', [InfoUserController::class, 'create']);
    Route::post('/user-profile', [InfoUserController::class, 'store']);
    Route::get('/login', function () {
        return view('dashboard');
    })->name('sign-up');

    Route::get('/users', [UsersController::class, 'index'])->name('users.index');

    Route::get('permissions/create', [PermissionController::class, 'create'])->name('permissions.create');
    Route::post('permissions', [PermissionController::class, 'store'])->name('permissions.store');

    Route::get('roles/create', [RoleController::class, 'create'])->name('roles.create');
    Route::post('roles', [RoleController::class, 'store'])->name('roles.store');

    Route::get('users/create', [UsersController::class, 'create'])->name('users.create');
    Route::post('users', [UsersController::class, 'store'])->name('users.store');

    Route::post('users', [UsersController::class, 'store'])->name('users.store');


    Route::get('doctor/index', [DoctorDetailController::class, 'index'])->name('doctorDetail.index');
    Route::get('doctor/create', [DoctorDetailController::class, 'create'])->name('doctorDetail.create');
    Route::post('doctor/store', [DoctorDetailController::class, 'store'])->name('doctorDetail.store');

    Route::get('patient/index', [PatientDetailController::class, 'index'])->name('paitent.index');
    Route::put('patient/update/{id}', [PatientDetailController::class, 'update'])->name('paitent.edit');
    Route::get('patient/view/{id}', [PatientDetailController::class, 'show'])->name('patient.show');
    Route::get('patient/create', [PatientDetailController::class, 'create'])->name('patient.create');
    Route::post('patient/store', [PatientDetailController::class, 'store'])->name('patient.store');
    Route::get('patient/edit/{id}', [PatientDetailController::class, 'edit'])->name('patient.edit');
    Route::put('patient/update/{id}', [PatientDetailController::class, 'update'])->name('patient.update');



    Route::get('appointment/create', [AppointmentController::class, 'create'])->name('appointments.create');
    Route::get('appointment', [AppointmentController::class, 'index'])->name('appointments.index');
    Route::post('appointment/store', [AppointmentController::class, 'store'])->name('appointments.store');
    Route::get('appointment/edit', [AppointmentController::class, 'edit'])->name('appointments.edit');
    Route::put('appointment/update/{id}', [AppointmentController::class, 'update'])->name('appointments.update');
    Route::get('appointment/view/{id}', [AppointmentController::class, 'show'])->name('appointments.show');
    Route::get('appointment/calander', [AppointmentController::class, 'calender'])->name('appointments.calander');



    Route::get('health-evalution/create', [HealthEvaluationController::class, 'create'])->name('healthevalution.create');
    Route::get('health-evalution', [HealthEvaluationController::class, 'index'])->name('healthevalution.index');
    Route::post('health-evalution/store', [HealthEvaluationController::class, 'store'])->name('healthevalution.store');
    Route::get('health-evalution/view/{id}', [HealthEvaluationController::class, 'show'])->name('healthevalution.show');

    // Category
    Route::get('category/create', [CategoryController::class, 'create'])->name('category.create');
    Route::get('category', [CategoryController::class, 'index'])->name('category.index');
    Route::post('category/store', [CategoryController::class, 'store'])->name('category.store');
    Route::get('category/{category}/edit', [CategoryController::class, 'edit'])->name('category.edit');
    Route::put('category/{category}', [CategoryController::class, 'update'])->name('category.update');
    Route::delete('category/{category}', [CategoryController::class, 'destroy'])->name('category.destroy');


    // Source Company

    Route::get('source-company/create', [SourceCompanyController::class, 'create'])->name(name: 'source-company.create');
    Route::get('source-company', [SourceCompanyController::class, 'index'])->name('source-company.index');
    Route::post('source-company/store', [SourceCompanyController::class, 'store'])->name('source-company.store');
    Route::get('source-company/edit/{id}', [SourceCompanyController::class, 'edit'])->name('source-company.edit');
    Route::put('source-company/{id}', [SourceCompanyController::class, 'update'])->name('source-company.update');
    Route::delete('source-company/{id}', [SourceCompanyController::class, 'destroy'])->name('source-company.destroy');
    // Brand
    Route::get('brand/create', [BrandController::class, 'create'])->name('brand.create');
    Route::get('brand', [BrandController::class, 'index'])->name('brand.index');
    Route::post('brand/store', [BrandController::class, 'store'])->name('brand.store');
    Route::get('brand/{brand}/edit', [BrandController::class, 'edit'])->name('brand.edit');
    Route::put('brand/{brand}', [BrandController::class, 'update'])->name('brand.update');
    Route::delete('brand/{brand}', [BrandController::class, 'destroy'])->name('brand.destroy');
    // UOM
    Route::get('uomtype/create', [UomTypeController::class, 'create'])->name('uomtype.create');
    Route::get('uomtype', [UomTypeController::class, 'index'])->name('uomtype.index');
    Route::post('uomtype/store', [UomTypeController::class, 'store'])->name('uomtype.store');
    Route::get('uomtype/{uomType}/edit', [UomTypeController::class, 'edit'])->name('uomtype.edit');
    Route::put('uomtype/{uomType}', [UomTypeController::class, 'update'])->name('uomtype.update');
    Route::delete('uomtype/{uomType}', [UomTypeController::class, 'destroy'])->name('uomtype.destroy');

    // Route::get('uomtype/{uomType}/edit', [UomTypeController::class, 'edit'])->name('uomtype.edit');
    // Route::put('uomtype/{uomType}', [UomTypeController::class, 'update'])->name('uomtype.update');
    // Route::delete('uomtype/{uomType}', [UomTypeController::class, 'destroy'])->name('uomtype.destroy');
    // Investigation Report
    Route::get('investigationreporttype/create', [InvestigationReportTypeController::class, 'create'])->name('investigationreporttype.create');
    Route::get('investigationreporttype', [InvestigationReportTypeController::class, 'index'])->name('investigationreporttype.index');
    Route::post('investigationreporttype/store', [InvestigationReportTypeController::class, 'store'])->name('investigationreporttype.store');
    Route::get('investigationreporttype/{investigationReportType}/edit', [InvestigationReportTypeController::class, 'edit'])->name('investigationreporttype.edit');
    Route::put('investigationreporttype/{investigationReportType}', [InvestigationReportTypeController::class, 'update'])->name('investigationreporttype.update');
    Route::delete('investigationreporttype/{investigationReportType}', [InvestigationReportTypeController::class, 'destroy'])->name('investigationreporttype.destroy');


    // Investigation Report
    Route::get('investigationreport/create', [InvestigationReportController::class, 'create'])->name('investigationreport.create');
    Route::post('investigationreport/store', [InvestigationReportController::class, 'store'])->name('investigationreport.store');
    Route::delete('investigationreporttype/{investigationReportType}', [InvestigationReportTypeController::class, 'destroy'])->name('investigationreporttype.destroy');


    Route::resource('items', ItemController::class);


    // Puchase orders

    Route::get('purchaseorders/create', [PurchaseorderController::class, 'create'])->name('purchaseorders.create');
    Route::post('purchaseorders/store', [PurchaseorderController::class, 'store'])->name('purchaseorders.store');
});



Route::group(['middleware' => 'guest'], function () {
    Route::get('/register', [RegisterController::class, 'create']);
    Route::post('/register', [RegisterController::class, 'store']);
    Route::get('/login', [SessionsController::class, 'create']);
    Route::post('/session', [SessionsController::class, 'store']);
    Route::get('/login/forgot-password', [ResetController::class, 'create']);
    Route::post('/forgot-password', [ResetController::class, 'sendEmail']);
    Route::get('/reset-password/{token}', [ResetController::class, 'resetPass'])->name('password.reset');
    Route::post('/reset-password', [ChangePasswordController::class, 'changePassword'])->name('password.update');
});

Route::get('/login', function () {
    return view('session/login-session');
})->name('login');
Route::get('/patients/search', [AppointmentController::class, 'search'])->name('patients.search');
