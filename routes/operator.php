<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\Access\DashboardController;
use App\Http\Controllers\Auth\Access\Operator\OperatorTransactionController;

Route::middleware(['role:playground_operator'])->group( function(){
    Route::get('/operator', [DashboardController::class, 'operator'])->name('operator.dashboard');
    Route::get('/operator/transaction', [OperatorTransactionController::class, 'index'])->name('operator.transaction');
    Route::get('/operator/transaction-data', [OperatorTransactionController::class, 'fetchData'])->name('operator.transaction-iten');
    Route::post('/operator/add-to-cart', [OperatorTransactionController::class, 'addToCart'])->name('operator.transaction-add-to-cart');
    Route::get('/operator/cart-item', [OperatorTransactionController::class, 'getCartItems'])->name('operator.cart-item');
    Route::put('/operator/cart/update/{id}', [OperatorTransactionController::class, 'updateCartItemQuantity'])->name('operator.cart-item-update-qty');
    Route::delete('/operator/cart/delete/{itemId}', [OperatorTransactionController::class, 'removeFromCart'])->name('operator.cart.delete');
});
