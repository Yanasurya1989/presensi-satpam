<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AjaxController;
use Illuminate\Support\Facades\Route;

Route::get('/login', [AuthController::class, 'login'])->name('login')->middleware('guest');


Route::post('/login', [AuthController::class, 'authenticate']);

Route::get('/logout', [AuthController::class, 'logout'])->middleware('auth');

Route::get('/', function () {
    return view('welcome');
})->middleware('auth');

Route::get('/insert', [ReportController::class, 'index']);

Route::get('/users', [UserController::class, 'index']);
Route::post('/users/store', [UserController::class, 'store']);
Route::get('/users/delete/{id}', [UserController::class, 'delete']);


Route::get('/user/edit/{user}', [UserController::class, 'edit']);



Route::post('/user/update/{user}', [UserController::class, 'update']);

Route::get('/report', [ReportController::class, 'view']);
Route::post('/report/store', [ReportController::class, 'store']);

//presensi handling
Route::post('/submit', [AjaxController::class, 'submit'])->name('ajax.submit');
