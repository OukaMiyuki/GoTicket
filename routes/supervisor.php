<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\Access\DashboardController;

Route::middleware(['role:playground_supervisor'])->get('/supervisor', [DashboardController::class, 'supervisor'])->name('supervisor.dashboard');
