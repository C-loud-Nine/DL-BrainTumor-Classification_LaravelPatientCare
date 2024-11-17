<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ForgetPasswordManager;

Route::get('/', function (Request $request) {
    $position = Location::get('ip()');
    return $position->countryName;
    return view('welcome');
});

// Define routes for authentication
Route::post('/register', [AuthController::class, 'registerUser'])->name('registerUser');
Route::post('/login', [AuthController::class, 'loginUser'])->name('loginUser');
Route::get('/logout', [AuthController::class, 'logoutUser'])->name('logout');



// Define routes for admin
Route::get('/adminhome', [AdminController::class, 'adminHome'])->name('admin.adminhome');
Route::get('/userlist', [AdminController::class, 'userlist'])->name('admin.userlist');
Route::get('/promote/{id}/{role}', [AdminController::class, 'add_promotion'])->name('admin.promote');
Route::get('/delete/{id}', [AdminController::class, 'deleteUser'])->name('admin.deleteUser');
Route::get('/adminlist', [AdminController::class, 'adminlist'])->name('admin.adminlist');
Route::get('/doctorlist', [AdminController::class, 'doctorlist'])->name('admin.doctorlist');



// Define home route with name
Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::get('/login', [HomeController::class, 'login'])->name('login');
Route::get('/register', [HomeController::class, 'register'])->name('register');



// Define routes for forget password
Route::get('/forget-password', [ForgetPasswordManager::class, 'forgetPassword'])->name('forget-password');
Route::post('/forget-password', [ForgetPasswordManager::class, 'forgetPasswordPost'])->name('forget.password.post');
Route::get('/reset-password/{token}', [ForgetPasswordManager::class, 'resetPassword'])->name('reset.password');
Route::post('/reset-password', [ForgetPasswordManager::class, 'resetPasswordPost'])->name('reset.password.post');
