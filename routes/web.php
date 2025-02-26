<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
// use App\Http\Controllers\Auth\Access\DashboardController;
// use App\Http\Controllers\Auth\Access\Tenant\TenantLocationController;
// use App\Http\Controllers\Auth\Access\Tenant\TenantEmployeeController;
// use App\Http\Controllers\Auth\Access\Tenant\TenantPacketController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Route::middleware(['auth'])->group(function () {
//     Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

//     Route::middleware(['role:super_user'])->get('/super-admin', [DashboardController::class, 'superAdmin'])->name('super.admin.dashboard');
//     Route::middleware(['role:administrator'])->get('/admin', [DashboardController::class, 'admin'])->name('admin.dashboard');

//     Route::middleware(['role:playground_owner'])->group(function () {
//         Route::get('/tenant', [DashboardController::class, 'tenant'])->name('tenant.dashboard');
//         Route::get('/tenant/lokasi', [TenantLocationController::class, 'index'])->name('tenant.dashboard.location');
//         Route::get('/tenant/lokasi-data', [TenantLocationController::class, 'getLocationData'])->name('tenant.dashboard.location-data');
//         Route::post('/tenant/lokasi/insert', [TenantLocationController::class, 'locationDataInsert'])->name('tenant.dashboard.location.insert');
//         Route::post('/tenant/lokasi/update', [TenantLocationController::class, 'locationDataUpdate'])->name('tenant.dashboard.location.update');

//         Route::get('/tenant/employee', [TenantEmployeeController::class, 'index'])->name('tenant.dashboard.employee');
//         Route::get('/tenant/employee-data', [TenantEmployeeController::class, 'getEmployeeData'])->name('tenant.dashboard.employee-data');
//         Route::post('/tenant/employee/insert', [TenantEmployeeController::class, 'userDataInsert'])->name('tenant.dashboard.employee.insert');
//         Route::get('/tenant/employee/detail/{userId}', [TenantEmployeeController::class, 'userDataDetail'])->name('tenant.dashboard.employee.detail');
//         Route::get('/tenant/employee/update/status/{userId}', [TenantEmployeeController::class, 'userDataStatusUpdate'])->name('tenant.dashboard.employee.status');

//         Route::post('/tenant/employee/user/update', [TenantEmployeeController::class, 'userDataUpdate'])->name('tenant.dashboard.employee.user.update');
//         Route::post('/tenant/employee/user-detail/update', [TenantEmployeeController::class, 'userDetailUpdate'])->name('tenant.dashboard.employee.userDetail.update');

//         Route::get('/tenant/packet', [TenantPacketController::class, 'index'])->name('tenant.dashboard.packet');
//         Route::get('/tenant/packet-data', [TenantPacketController::class, 'getPacketData'])->name('tenant.dashboard.packet-data');
//         Route::get('/tenant/packet/add', [TenantPacketController::class, 'addPacketData'])->name('tenant.dashboard.packet.add');
//         Route::post('/tenant/packet/insert', [TenantPacketController::class, 'addPacketDataInsert'])->name('tenant.dashboard.packet.insert');
//         Route::get('/tenant/packet/edit', [TenantPacketController::class, 'editPacketData'])->name('tenant.dashboard.packet.edit');
//         Route::post('/tenant/packet/update', [TenantPacketController::class, 'editPacketDataUpdate'])->name('tenant.dashboard.packet.update');
//         Route::get('/tenant/packet/update/status/{packetId}', [TenantPacketController::class, 'editPacketDataStatusUpdate'])->name('tenant.dashboard.packet.status');
//         Route::get('/tenant/packet/delete/{packetId}', [TenantPacketController::class, 'deletePacketData'])->name('tenant.dashboard.packet.delete');
//     });

//     Route::middleware(['role:playground_supervisor'])->get('/supervisor', [DashboardController::class, 'supervisor'])->name('supervisor.dashboard');
//     Route::middleware(['role:playground_operator'])->get('/operator', [DashboardController::class, 'operator'])->name('operator.dashboard');
//     Route::middleware(['role:visitor_member'])->get('/member-area', [DashboardController::class, 'visitor'])->name('visitor.dashboard');
// });

require __DIR__.'/auth.php';
require __DIR__.'/access.php';
