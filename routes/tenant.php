<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\Access\DashboardController;
use App\Http\Controllers\Auth\Access\Tenant\TenantLocationController;
use App\Http\Controllers\Auth\Access\Tenant\TenantEmployeeController;
use App\Http\Controllers\Auth\Access\Tenant\TenantPacketController;
use App\Http\Controllers\Auth\Access\Tenant\TenantVoucherController;
use App\Http\Controllers\Auth\Access\Tenant\TenantTaxController;

Route::middleware(['role:playground_owner'])->group(function () {
    Route::get('/tenant', [DashboardController::class, 'tenant'])->name('tenant.dashboard');
    Route::get('/tenant/lokasi', [TenantLocationController::class, 'index'])->name('tenant.dashboard.location');
    Route::get('/tenant/lokasi-data', [TenantLocationController::class, 'getLocationData'])->name('tenant.dashboard.location-data');
    Route::post('/tenant/lokasi/insert', [TenantLocationController::class, 'locationDataInsert'])->name('tenant.dashboard.location.insert');
    Route::post('/tenant/lokasi/update', [TenantLocationController::class, 'locationDataUpdate'])->name('tenant.dashboard.location.update');

    Route::get('/tenant/employee', [TenantEmployeeController::class, 'index'])->name('tenant.dashboard.employee');
    Route::get('/tenant/employee-data', [TenantEmployeeController::class, 'getEmployeeData'])->name('tenant.dashboard.employee-data');
    Route::post('/tenant/employee/insert', [TenantEmployeeController::class, 'userDataInsert'])->name('tenant.dashboard.employee.insert');
    Route::get('/tenant/employee/detail/{userId}', [TenantEmployeeController::class, 'userDataDetail'])->name('tenant.dashboard.employee.detail');
    Route::get('/tenant/employee/update/status/{userId}', [TenantEmployeeController::class, 'userDataStatusUpdate'])->name('tenant.dashboard.employee.status');

    Route::post('/tenant/employee/user/update', [TenantEmployeeController::class, 'userDataUpdate'])->name('tenant.dashboard.employee.user.update');
    Route::post('/tenant/employee/user-detail/update', [TenantEmployeeController::class, 'userDetailUpdate'])->name('tenant.dashboard.employee.userDetail.update');

    Route::get('/tenant/packet', [TenantPacketController::class, 'index'])->name('tenant.dashboard.packet');
    Route::get('/tenant/packet-data', [TenantPacketController::class, 'getPacketData'])->name('tenant.dashboard.packet-data');
    Route::get('/tenant/packet/add', [TenantPacketController::class, 'addPacketData'])->name('tenant.dashboard.packet.add');
    Route::post('/tenant/packet/insert', [TenantPacketController::class, 'addPacketDataInsert'])->name('tenant.dashboard.packet.insert');
    Route::get('/tenant/packet/edit/{packetId}', [TenantPacketController::class, 'editPacketData'])->name('tenant.dashboard.packet.edit');
    Route::post('/tenant/packet/update', [TenantPacketController::class, 'editPacketDataUpdate'])->name('tenant.dashboard.packet.update');
    Route::get('/tenant/packet/update/status/{packetId}', [TenantPacketController::class, 'editPacketDataStatusUpdate'])->name('tenant.dashboard.packet.status');
    Route::get('/tenant/packet/delete/{packetId}', [TenantPacketController::class, 'deletePacketData'])->name('tenant.dashboard.packet.delete');

    Route::get('/tenant/voucher', [TenantVoucherController::class, 'index'])->name('tenant.dashboard.voucher');
    Route::get('/tenant/voucher-data', [TenantVoucherController::class, 'getVoucherData'])->name('tenant.dashboard.voucher-data');
    Route::post('/tenant/voucher/insert', [TenantVoucherController::class, 'addVoucherDataInsert'])->name('tenant.dashboard.voucher.insert');
    Route::post('/tenant/voucher/update', [TenantVoucherController::class, 'addVoucherDataUpdate'])->name('tenant.dashboard.voucher.update');
    Route::get('/tenant/voucher/update/status/{voucherId}', [TenantVoucherController::class, 'addVoucherDataStatusUpdate'])->name('tenant.dashboard.voucher.update.status');
    Route::get('/tenant/voucher/delete/{voucherId}', [TenantVoucherController::class, 'deleteVoucherData'])->name('tenant.dashboard.voucher.delete');

    Route::get('/tenant/tax-setup', [TenantTaxController::class, 'index'])->name('tenant.dashboard.tax');
    Route::post('/tenant/tax-setup/insert', [TenantTaxController::class, 'taxInsert'])->name('tenant.dashboard.tax.insert');
});
