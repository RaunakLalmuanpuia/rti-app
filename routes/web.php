<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CitizenInformationController;
use App\Http\Controllers\PaymentCallbackController;

Route::get('/', function () {
    return Inertia::render('Home');
})->name('home');

//Auth
Route::group([], function () {
    Route::get('login', [AuthController::class, 'create'])->name('login');
    Route::get('forgot-password', [AuthController::class, 'forgotPassword'])->name('login.forgot');
    Route::post('forgot-password/send-otp', [AuthController::class, 'sendOtp'])->name('forgot.send');
    Route::post('forgot-password/verify-otp', [AuthController::class, 'verifyOtp'])->name('forgot.verify');
    Route::post('forgot-password/set-password', [AuthController::class, 'changePassword'])->name('forgot.password');
    Route::post('login', [AuthController::class, 'store'])->name('login.store');
    Route::delete('logout', [AuthController::class, 'destroy'])->name('login.destroy');
});

//Register
Route::group([], function () {
    Route::get('register', [RegisterController::class, 'create'])->name('register.create');
    Route::post('register/send-otp', [RegisterController::class, 'sendOtp'])->name('register.send-otp');
    Route::post('register/confirm-otp', [RegisterController::class, 'confirmOtp'])->name('register.confirm-otp');
});

//Dashboard
Route::group(['middleware' => 'auth', 'prefix' => 'dashboard'], function () {
    Route::get('',[DashboardController::class,'index'])->name('dashboard');
    Route::get('admin',[DashboardController::class,'admin'])->name('dashboard.admin');
    Route::get('citizen',[DashboardController::class,'citizen'])->name('dashboard.citizen');
    Route::get('official',[DashboardController::class,'official'])->name('dashboard.official');
});



//Citizen
Route::group(['middleware' => 'auth', 'prefix' => 'citizen'], function () {
    Route::get('create',[CitizenInformationController::class,'create'])->name('information.create');
    Route::get('search-department',[CitizenInformationController::class,'searchDepartment'])->name('information.search-department');
    Route::get('get-local_council',[CitizenInformationController::class,'getLocalCouncil'])->name('information.get-local_council');
    Route::post('store', [CitizenInformationController::class, 'store'])->name('information.store');
    Route::get('show', [CitizenInformationController::class, 'show'])->name('information.show');
});


//Payment Callback
Route::group(['prefix'=>'callback'], function () {
    Route::post('information', [PaymentCallbackController::class, 'callback'])->name('callback.information');
});


