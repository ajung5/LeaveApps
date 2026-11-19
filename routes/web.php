<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\LeaveRequestController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('/login',[LoginController::class,'create'])->name('login');
    Route::post('/login',[LoginController::class,'store'])->middleware('throttle:5,1')->name('login.store');
});
Route::middleware(['auth','active'])->group(function () {
    Route::post('/logout',[LoginController::class,'destroy'])->name('logout');
    Route::get('/',[DashboardController::class,'index'])->name('dashboard');
    Route::get('/pengajuan/baru',[LeaveRequestController::class,'create'])->name('leaves.create');
    Route::post('/pengajuan',[LeaveRequestController::class,'store'])->name('leaves.store');
    Route::get('/pengajuan/{leave}',[LeaveRequestController::class,'show'])->name('leaves.show');
    Route::get('/pengajuan/{leave}/revisi',[LeaveRequestController::class,'edit'])->name('leaves.edit');
    Route::put('/pengajuan/{leave}',[LeaveRequestController::class,'update'])->name('leaves.update');
    Route::post('/pengajuan/{leave}/batal',[LeaveRequestController::class,'cancel'])->name('leaves.cancel');
    Route::get('/pengajuan/{leave}/pdf/{kind}',[DocumentController::class,'pdf'])->name('leaves.pdf');
    Route::get('/pengajuan/{leave}/lampiran/{attachment}',[DocumentController::class,'attachment'])->name('leaves.attachment');
    Route::get('/pengajuan/{leave}/scan-bertandatangan',[DocumentController::class,'signed'])->name('leaves.signed');
    Route::prefix('admin')->name('admin.')->middleware('admin')->group(function () {
        Route::get('/laporan',[ReportController::class,'index'])->name('reports');
        Route::get('/laporan/csv',[ReportController::class,'csv'])->name('reports.csv');
        Route::get('/unit-kerja',[AdminController::class,'units'])->name('units');
        Route::post('/unit-kerja',[AdminController::class,'unitStore'])->name('units.store');
        Route::put('/unit-kerja/{unit}',[AdminController::class,'unitUpdate'])->name('units.update');
        Route::get('/pegawai',[AdminController::class,'employees'])->name('employees');
        Route::get('/pegawai/tambah',[AdminController::class,'employeeCreate'])->name('employees.create');
        Route::post('/pegawai',[AdminController::class,'employeeStore'])->name('employees.store');
        Route::get('/pegawai/{employee}/edit',[AdminController::class,'employeeEdit'])->name('employees.edit');
        Route::put('/pegawai/{employee}',[AdminController::class,'employeeUpdate'])->name('employees.update');
        Route::post('/pegawai/{employee}/saldo',[AdminController::class,'balanceUpdate'])->name('balances.update');
        Route::get('/jenis-cuti',[AdminController::class,'types'])->name('types');
        Route::put('/jenis-cuti/{type}',[AdminController::class,'typeUpdate'])->name('types.update');
        Route::get('/hari-libur',[AdminController::class,'holidays'])->name('holidays');
        Route::post('/hari-libur',[AdminController::class,'holidayStore'])->name('holidays.store');
        Route::delete('/hari-libur/{holiday}',[AdminController::class,'holidayDestroy'])->name('holidays.destroy');
        Route::get('/instansi',[AdminController::class,'settings'])->name('settings');
        Route::put('/instansi',[AdminController::class,'settingsUpdate'])->name('settings.update');
        Route::post('/pengajuan/{leave}/verifikasi',[AdminController::class,'verify'])->name('leaves.verify');
        Route::post('/pengajuan/{leave}/keputusan',[AdminController::class,'decision'])->name('leaves.decision');
        Route::post('/pengajuan/{leave}/terbitkan',[AdminController::class,'issue'])->name('leaves.issue');
        Route::post('/pengajuan/{leave}/unggah-scan',[AdminController::class,'signedUpload'])->name('leaves.signed.upload');
    });
});
