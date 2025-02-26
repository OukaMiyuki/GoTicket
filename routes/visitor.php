<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\Access\DashboardController;

Route::middleware(['role:visitor_member'])->get('/member-area', [DashboardController::class, 'visitor'])->name('visitor.dashboard');
