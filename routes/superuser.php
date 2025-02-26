<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\Access\DashboardController;

Route::middleware(['role:super_user'])->get('/super-admin', [DashboardController::class, 'superAdmin'])->name('super.admin.dashboard');
