<?php

use App\Models\Kehadiran;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AjaxController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Rekapcontroller;
use App\Http\Controllers\ShiftController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\PresensiController;
use App\Http\Controllers\KehadiranController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\PresensiScController;

Route::get('/login', [AuthController::class, 'login'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'authenticate']);
Route::get('/logout', [AuthController::class, 'logout'])->middleware('auth');

Route::get('/', function () {
    return view('welcome');
})->middleware('auth');

Route::get('/insert', [ReportController::class, 'index']);
Route::get('/report', [ReportController::class, 'view']);
Route::post('/report/store', [ReportController::class, 'store']);

// User
Route::get('/users', [UserController::class, 'index']);
Route::post('/users/store', [UserController::class, 'store']);
Route::get('/users/delete/{id}', [UserController::class, 'delete']);
Route::get('/user/edit/{user}', [UserController::class, 'edit']);
Route::post('/user/update/{user}', [UserController::class, 'update']);

// Report admin
Route::get('/report-admin', [Rekapcontroller::class, 'index']);

// Presensi SC
// Route::get('/presensi-sc', [PresensiScController::class, 'index']);
Route::get('/presensi-sc', [KehadiranController::class, 'index']);
Route::post('/simpan-masuk', [KehadiranController::class, 'store']);

Route::get('/presensi-keluar', [KehadiranController::class, 'out_sc']);
Route::post('/presensi-keluar/proses', [KehadiranController::class, 'outsc']);
Route::get('/rekap_presensi', [KehadiranController::class, 'rekap_presensi']);

Route::get('/filter-data', [KehadiranController::class, 'halamanrekap']);
Route::get('filter-data/{tglawal}/{tglakhir}', [KehadiranController::class, 'tampildatakeseluruhan']);

Route::get('/jam', [KehadiranController::class, 'viewjam']);
Route::post('/save-timestamp', [KehadiranController::class, 'create'])->name('save.timestamp');

Route::get('/shifts', [ShiftController::class, 'index']);
Route::get('/shiftsforschedule', [ShiftController::class, 'siftforschedule']);

// Attendance
Route::middleware('auth:sanctum')->post('/attendance', [AttendanceController::class, 'store']);
Route::middleware('auth:sanctum')->get('/attendance/report', [AttendanceController::class, 'report']);

Route::middleware(['auth:sanctum', 'admin'])->get('/admin/attendance', [AdminController::class, 'index']);

// Presensi image validation
Route::post('/kehadiran/sendpresensi', [KehadiranController::class, 'sendpresensi']);
Route::post('/presensi/kirim_hadir', [KehadiranController::class, 'kirim_hadir']);

//presensi handling
Route::post('/submit', [AjaxController::class, 'submit'])->name('ajax.submit');
