<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ForgetPasswordManager;
//use Illuminate\Http\Request;
use Stevebauman\Location\Facades\Location;

Route::get('/', function (Request $request) {
    //return $request->ip();
    request()->ip();
    $position = Location::get('ip()');
    return $position->countryName;
    return view('welcome');
});



Route::post('/register', [AuthController::class, 'registerUser'])->name('registerUser');
Route::post('/login', [AuthController::class, 'loginUser'])->name('loginUser');
Route::get('/logout', [AuthController::class, 'logoutUser'])->name('logout');



Route::get('/adminhome', [AdminController::class, 'adminHome'])->name('admin.adminhome');





// Define home route with name
Route::get('/home', [HomeController::class, 'index'])->name('home');

Route::get('/login', [HomeController::class, 'login'])->name('login');
Route::get('/register', [HomeController::class, 'register'])->name('register');

Route::get('/forget-password', [ForgetPasswordManager::class, 'forgetPassword'])
    ->name('forget.password');

Route::post('/forget-password', [ForgetPasswordManager::class, 'forgetPasswordPost'])
    ->name('forget.password.post');

Route::get('/reset-password/{token}', [ForgetPasswordManager::class, 'resetPassword'])
    ->name('reset.password');
Route::post('/reset-password', [ForgetPasswordManager::class, 'resetPasswordPost'])
    ->name('reset.password.post');