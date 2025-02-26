<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\Access\DashboardController;
use App\Http\Controllers\Auth\Access\Operator\OperatorTransactionController;

Route::middleware(['role:playground_operator'])->group( function(){
    Route::get('/operator', [DashboardController::class, 'operator'])->name('operator.dashboard');
    Route::get('/operator/transaction', [OperatorTransactionController::class, 'index'])->name('operator.transaction');
    Route::get('/operator/transaction-data', [OperatorTransactionController::class, 'fetchData']);
});
