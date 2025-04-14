<?php

// use App\Models\Kehadiran;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AjaxController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
// use App\Http\Controllers\PhotoController;
use App\Http\Controllers\Rekapcontroller;
use App\Http\Controllers\ShiftController;
use App\Http\Controllers\ReportController;
// use App\Http\Controllers\PresensiController;
use App\Http\Controllers\KehadiranController;
use App\Http\Controllers\AttendanceController;
// use App\Http\Controllers\PresensiScController;
use App\Exports\UsersExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\ShiftAssignmentController;
use App\Http\Controllers\OvertimeController;
use App\Http\Controllers\PresensiController;

// ulangi proses presensi
use App\Http\Controllers\PresenceController;

Route::post('/presence', [PresenceController::class, 'store'])->name('presence.store');
// Route::get('/view_presence', [PresenceController::class, 'index']);
// Route::get('/view_presence', [ShiftAssignmentController::class, 'presensiForm'])->name('presence.form');
Route::get('/view_presence', [PresenceController::class, 'presensiForm'])->name('presence.form');
Route::get('/rekap-presensi', [PresenceController::class, 'rekap'])->name('presence.rekap');
Route::get('/rekap-admin', [PresenceController::class, 'rekapAdmin'])->name('presence.rekapAdmin');
Route::get('/rekap/export', [PresenceController::class, 'export'])->name('rekap.export');



// akhir pengulangan pembuatan presensi

Route::middleware(['auth'])->group(function () {
    // Route::post('/presensi/store', [PresensiController::class, 'store'])->name('attendance.store');
    Route::post('/attendance/store', [PresensiController::class, 'store'])->name('attendance.store');
    // Route::middleware('auth:sanctum')->post('/attendance', [PresensiController::class, 'store'])->name('attendance.store');
    // Route::middleware('auth:sanctum')->get('/attendance/report', [PresensiController::class, 'report']);
});


Route::middleware(['auth'])->group(function () {
    Route::get('/lembur', [OvertimeController::class, 'index'])->name('lembur.index');
    Route::get('/lembur/create', [OvertimeController::class, 'create'])->name('lembur.create');
    Route::post('/lembur', [OvertimeController::class, 'store'])->name('lembur.store');
});



Route::get('/cek-timezone', function () {
    return date('Y-m-d H:i:s') . ' - ' . date_default_timezone_get();
});


Route::get('/shift-assignment', [ShiftAssignmentController::class, 'index'])->name('shift.assignment');
Route::post('/shift-assign', [ShiftAssignmentController::class, 'assign'])->name('shift.assign');
Route::post('/shift-assign', [ShiftAssignmentController::class, 'store'])->name('shift.assign');
Route::delete('/shift-remove/{userId}/{shiftId}/{shiftDate}', [ShiftAssignmentController::class, 'remove'])->name('shift.remove');
// Route::delete('/shift-remove/{userId}/{shiftId}/{weekStart}/{weekEnd}', [ShiftController::class, 'remove'])->name('shift.remove');
Route::delete('/user-shifts/{id}', [ShiftAssignmentController::class, 'destroy'])->name('user-shifts.destroy');


Route::get('/export-users', function () {
    return Excel::download(new UsersExport, 'users.xlsx');
});


Route::get('/export', [ReportController::class, 'export'])->name('export');
// Route::get('/export-excel', [ReportController::class, 'export'])->name('report.export');

Route::get('/login', [AuthController::class, 'login'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'authenticate']);
Route::get('/logout', [AuthController::class, 'logout'])->middleware('auth');

Route::get('/', function () {
    return view('welcome');
})->middleware('auth');

Route::get('/insert', [ReportController::class, 'index']);
Route::get('/report', [ReportController::class, 'view']);
Route::get('/daily-report', [ReportController::class, 'daily_check']);
Route::get('/admin_view', [ReportController::class, 'admin']);
Route::get('/naon', [ReportController::class, 'report_all_user']);
Route::delete('/report/delete/{id}', [ReportController::class, 'delete'])->name('report.delete');

Route::middleware(['auth'])->group(function () {
    Route::post('/report/store', [ReportController::class, 'store']);
});


// Route::post('/report/store', [ReportController::class, 'store']);

// User
Route::get('/users', [UserController::class, 'index']);
Route::post('/users/store', [UserController::class, 'store']);
Route::get('/users/delete/{id}', [UserController::class, 'delete']);
Route::get('/user/edit/{user}', [UserController::class, 'edit']);
Route::post('/user/update/{user}', [UserController::class, 'update']);

// Report admin
Route::get('/report-admin', [Rekapcontroller::class, 'index']);
// Route::get('/rekap', [Rekapcontroller::class, 'index']);

// Presensi SC
// Route::get('/presensi-sc', [PresensiScController::class, 'index']);
Route::get('/presensi-sc', [KehadiranController::class, 'index'])->name('presensi_sc');
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



Route::middleware(['auth:sanctum', 'admin'])->get('/admin/attendance', [AdminController::class, 'index']);

// Presensi image validation
Route::post('/kehadiran/sendpresensi', [KehadiranController::class, 'sendpresensi']);
Route::post('/presensi/kirim_hadir', [KehadiranController::class, 'kirim_hadir']);

//presensi handling
Route::post('/submit', [AjaxController::class, 'submit'])->name('ajax.submit');

// Webcam Capture
// Route::get('/capture', function () {
//     return view('capture/capture');
// })->name('capture.form');

// Route::post('/capture', [PhotoController::class, 'capture']);
// Route::get('/report-admin', [ReportController::class, 'index'])->name('layout.admin.rekap_admin');
Route::get('/admin-rekap', [Rekapcontroller::class, 'index']);
