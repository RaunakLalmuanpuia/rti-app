<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CitizenInformationController;
use App\Http\Controllers\CitizenPaymentController;
use App\Http\Controllers\PaymentCallbackController;
use App\Http\Controllers\AspioController;
use App\Http\Controllers\SpioController;
use App\Http\Controllers\DaaController;
use App\Http\Controllers\CicController;


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
    Route::get('register-citizen', [RegisterController::class, 'createCitizen'])->name('register.create-citizen');
    Route::get('register-official', [RegisterController::class, 'createOfficial'])->name('register.create-official');
    Route::get('register-local-council', [RegisterController::class, 'createLocalCouncil'])->name('register.create-local-council');
    Route::post('register/send-otp', [RegisterController::class, 'sendOtp'])->name('register.send-otp');
    Route::post('register/confirm-otp', [RegisterController::class, 'confirmOtp'])->name('register.confirm-otp');
    Route::get('search-department',[RegisterController::class,'searchDepartment'])->name('register.search-department');
    Route::post('register/store-citizen', [RegisterController::class, 'storeCitizen'])->name('register.store-citizen');
    Route::post('register/store-official', [RegisterController::class, 'storeOfficial'])->name('register.store-official');
    Route::post('register/store-local-council', [RegisterController::class, 'storeLocalCouncil'])->name('register.store-local-council');
});

//Dashboard
Route::group(['middleware' => 'auth', 'prefix' => 'dashboard'], function () {
    Route::get('',[DashboardController::class,'index'])->name('dashboard');
    Route::get('admin',[DashboardController::class,'admin'])->name('dashboard.admin');
    Route::get('citizen',[DashboardController::class,'citizen'])->name('dashboard.citizen');
    Route::get('official',[DashboardController::class,'official'])->name('dashboard.official');
});


//Citizen Information
Route::group(['middleware' => 'auth', 'prefix' => 'citizen'], function () {
    Route::get('index',[CitizenInformationController::class,'index'])->name('information.index');
    Route::get('index-json',[CitizenInformationController::class,'indexJson'])->name('information.json-index');
    Route::get('create',[CitizenInformationController::class,'create'])->name('information.create');
    Route::get('search-department',[CitizenInformationController::class,'searchDepartment'])->name('information.search-department');
    Route::get('get-local_council',[CitizenInformationController::class,'getLocalCouncil'])->name('information.get-local_council');
    Route::post('store', [CitizenInformationController::class, 'store'])->name('information.store');
    Route::get('show/{info}', [CitizenInformationController::class, 'show'])->name('information.show');
    Route::post('pay-attachment/{attachment}', [CitizenInformationController::class, 'payAttachment'])->name('information.pay-attachment');
    Route::post('first-appeal/{information}', [CitizenInformationController::class, 'firstAppeal'])->name('information.first-appeal');
    Route::post('second-appeal/{information}', [CitizenInformationController::class, 'secondAppeal'])->name('information.second-appeal');


    //Complain
    Route::get('complain/index',[CitizenInformationController::class,'complainJson'])->name('information.complain.json-index');
    Route::get('complain/create',[CitizenInformationController::class,'createComplain'])->name('citizen.complain.create');
    Route::post('complain/store', [CitizenInformationController::class, 'storeComplain'])->name('citizen.complain.store');
});

// Citizen Payment
Route::group(['middleware' => 'auth', 'prefix' => 'citizen'], function () {
    Route::get('payment',[CitizenPaymentController::class,'index'])->name('citizen.payment.index');
});

//Payment Callback
Route::group(['prefix'=>'callback'], function () {
    Route::post('information', [PaymentCallbackController::class, 'information'])->name('callback.information');
    Route::post('attachment', [PaymentCallbackController::class, 'attachment'])->name('callback.attachment');
});

//SPAIO
Route::middleware(['auth'])->prefix('sapio')->group(function () {
    Route::get('information', [AspioController::class,'index'])->name('sapio.information.index');
    Route::get('/information/pending', [AspioController::class, 'pendingJson'])->name('sapio.information.pending');
    Route::get('/information/commented', [AspioController::class, 'commentedJson'])->name('sapio.information.commented');
    Route::get('information/{information}/show', [AspioController::class,'show'])->name('sapio.information.show');
    Route::post('information/{information}/comment', [AspioController::class,'store'])->name('sapio.information.store');
});


//SPIO
Route::middleware(['auth'])->prefix('spio')->group(function () {
    Route::get('information', [SpioController::class,'index'])->name('spio.information.index');
    Route::get('/information/pending', [SpioController::class, 'pendingJson'])->name('spio.information.pending');
    Route::get('/information/answered', [SpioController::class, 'answeredJson'])->name('spio.information.answered');
    Route::get('/information/computer-generated', [SpioController::class, 'computerGeneratedJson'])->name('spio.information.computer-generated');
    Route::get('information/{information}/show', [SpioController::class,'show'])->name('spio.information.show');
    Route::post('information/{information}/answer', [SpioController::class,'store'])->name('spio.information.store');
    Route::post('information/{information}/transfer', [SpioController::class,'transfer'])->name('spio.information.transfer');
});


//DAA
Route::middleware(['auth'])->prefix('daa')->group(function () {
    Route::get('information', [DaaController::class,'index'])->name('daa.information.index');
    Route::get('/information/pending', [DaaController::class, 'pendingJson'])->name('daa.information.pending');
    Route::get('/information/answered', [DaaController::class, 'answeredJson'])->name('daa.information.answered');
    Route::get('/information/computer-generated', [DaaController::class, 'computerGeneratedJson'])->name('daa.information.computer-generated');
    Route::get('/information/all-application', [DaaController::class, 'allApplicationJson'])->name('daa.information.all-application');
    Route::get('information/{information}/show', [DaaController::class,'show'])->name('daa.information.show');
    Route::post('information/{information}/answer', [DaaController::class,'store'])->name('daa.information.store');
});


//CIC
Route::middleware(['auth'])->prefix('cic')->group(function () {
    Route::get('information', [CicController::class,'index'])->name('cic.information.index');
    Route::get('/information/pending', [CicController::class, 'pendingJson'])->name('cic.information.pending');
    Route::get('/information/answered', [CicController::class, 'answeredJson'])->name('cic.information.answered');
    Route::get('information/{information}/show', [CicController::class,'show'])->name('cic.information.show');
    Route::post('information/{information}/answer', [CicController::class,'store'])->name('cic.information.store');

    //Complain
    Route::get('complain', [CicController::class,'complainIndex'])->name('cic.complain.index');
    Route::get('/complain/json', [CicController::class, 'complainJson'])->name('cic.complain.json');
});

