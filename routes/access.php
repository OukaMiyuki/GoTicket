<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\Access\DashboardController;

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    require __DIR__.'/superuser.php';
    require __DIR__.'/administrator.php';
    require __DIR__.'/tenant.php';
    require __DIR__.'/supervisor.php';
    require __DIR__.'/operator.php';
    require __DIR__.'/visitor.php';
});
