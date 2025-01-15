<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

Route::get('/login', [AuthController::class, 'login'])->name('login')->middleware('guest');


Route::post('/login', [AuthController::class, 'authenticate']);

Route::get('/', function () {
    return view('welcome');
})->middleware('auth');

Route::get('/insert', [ReportController::class, 'index']);
