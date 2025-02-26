<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\Access\DashboardController;

Route::middleware(['role:administrator'])->get('/admin', [DashboardController::class, 'admin'])->name('admin.dashboard');
