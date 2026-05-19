<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AttendanceCorrectionRequestController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\AttendanceController as AdminAttendanceController;
use App\Http\Controllers\Admin\StaffController;
use App\Http\Controllers\Admin\StampCorrectionController;


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

Route::post('/logout', function () {

    Auth::logout();

    request()->session()->invalidate();

    request()->session()->regenerateToken();

    return redirect('/login');
})->name('logout');

Route::middleware('auth')->group(function () {

    // 打刻画面
    Route::get('/attendance', [AttendanceController::class, 'index']);

    Route::post('/attendance/clock-in', [AttendanceController::class, 'clockIn']);
    Route::post('/attendance/break-start', [AttendanceController::class, 'breakStart']);
    Route::post('/attendance/break-end', [AttendanceController::class, 'breakEnd']);
    Route::post('/attendance/clock-out', [AttendanceController::class, 'clockOut']);

    // 一覧
    Route::get('/attendance/list', [AttendanceController::class, 'list'])->middleware('auth');

    // 詳細
    Route::get('/attendance/detail/{id}', [AttendanceController::class, 'detail'])->middleware('auth');
    Route::post('/attendance/detail/{id}', [AttendanceController::class, 'update'])->middleware('auth');

    // 申請一覧
    Route::get('/stamp_correction_request/list', [AttendanceCorrectionRequestController::class, 'index']);
});

Route::get('/admin/login', [AuthController::class, 'showLogin']);

Route::post('/admin/login', [AuthController::class, 'login']);

// 管理者ログアウト
Route::post('/admin/logout', function () {

    Auth::guard('admin')->logout();

    request()->session()->invalidate();

    request()->session()->regenerateToken();

    return redirect('/admin/login');
})->name('admin.logout');


Route::middleware('auth:admin')->group(function () {

    // 勤怠一覧
    Route::get(
        '/admin/attendance/list',
        [AdminAttendanceController::class, 'list']
    );

    // 勤怠詳細
    Route::get(
        '/admin/attendance/detail/{id}',
        [AdminAttendanceController::class, 'detail']
    )->name('admin.attendance.detail');

    // 更新
    Route::put(
        '/admin/attendance/detail/{id}',
        [AdminAttendanceController::class, 'update']
    );

    // スタッフ一覧
    Route::get(
        '/admin/staff/list',
        [StaffController::class, 'index']
    )->name('admin.staff.list');

    // スタッフ別月次勤怠
    Route::get(
        '/admin/attendance/staff/{id}',
        [AdminAttendanceController::class, 'staff']
    )->name('admin.attendance.staff');

    // CSV出力
    Route::get(
        '/admin/attendance/staff/{id}/csv',
        [AdminAttendanceController::class, 'csv']
    )->name('admin.attendance.csv');

    Route::get(
        '/admin/stamp_correction_request/list',
        [StampCorrectionController::class, 'index']
    )->name('admin.request.list');

    // 申請詳細
    Route::get(
        '/admin/stamp_correction_request/approve/{id}',
        [StampCorrectionController::class, 'show']
    )->name('admin.request.show');

    // 承認処理
    Route::post(
        '/admin/stamp_correction_request/approve/{id}',
        [StampCorrectionController::class, 'approve']
    )->name('admin.request.approve');
});


