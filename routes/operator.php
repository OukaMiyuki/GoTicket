<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\Access\DashboardController;
use App\Http\Controllers\Auth\Access\Operator\OperatorTransactionController;
use App\Http\Controllers\Auth\Access\Operator\OperatorTicketController;

Route::middleware(['role:playground_operator'])->group( function(){
    Route::get('/operator', [DashboardController::class, 'operator'])->name('operator.dashboard');
    Route::get('/operator/transaction', [OperatorTransactionController::class, 'index'])->name('operator.transaction');
    Route::get('/operator/transaction-data', [OperatorTransactionController::class, 'fetchData'])->name('operator.transaction-iten');
    Route::post('/operator/add-to-cart', [OperatorTransactionController::class, 'addToCart'])->name('operator.transaction-add-to-cart');
    Route::get('/operator/cart-item', [OperatorTransactionController::class, 'getCartItems'])->name('operator.cart-item');
    Route::put('/operator/cart/update/{id}', [OperatorTransactionController::class, 'updateCartItemQuantity'])->name('operator.cart-item-update-qty');
    Route::delete('/operator/cart/delete/{itemId}', [OperatorTransactionController::class, 'removeFromCart'])->name('operator.cart.delete');
    Route::get('/operator/transaction/checkout', [OperatorTransactionController::class, 'checkoutIndex'])->name('operator.transaction.checkout');
    Route::post('/operator/transaction/checkout/process', [OperatorTransactionController::class, 'checkoutProcess'])->name('operator.transaction.checkout.process');

    Route::get('/operator/transaction/checkout/pay/{invoiceId}', [OperatorTicketController::class, 'ticketInvoicePayment'])->name('operator.transaction.invoice.checkout.pay');

    Route::get('/operator/transaction/invoice/ticket/{invoiceId}', [OperatorTicketController::class, 'ticketInvoiceList'])->name('operator.transaction.invoice.ticket');
    Route::post('/operator/transaction/invoice/ticket/info/insert', [OperatorTicketController::class, 'ticketInvoiceInfoInsert'])->name('operator.transaction.invoice.ticket.info.insert');

    Route::get('/operator/cekqris', [OperatorTransactionController::class, 'testQris']);

    Route::get('/test-broadcast', function () {
        broadcast(new \App\Events\PaymentUpdated(70));
        return 'Broadcast sent';
    });
});
