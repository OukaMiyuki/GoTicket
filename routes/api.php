<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Payment\PaymentConfirmController;
use App\Http\Controllers\Auth\Access\Operator\OperatorTransactionController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/payment/update', [PaymentConfirmController::class, 'updatePayment'])->name('api.payment.update');

Route::get('/test', [PaymentConfirmController::class, 'test']);