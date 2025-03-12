<?php

namespace App\Http\Controllers\Api\Payment;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\PaymentCallbackRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Models\ApiAccess;
use Illuminate\Http\Request;
use App\Models\Invoice;
use Carbon\Carbon;

class PaymentConfirmController extends Controller {
    public function updatePayment(PaymentCallbackRequest $request){
        try{
            $paymentData = $request->validated();
            $apiKeyData = ApiAccess::first();
            if(Hash::check($paymentData['password'], $apiKeyData->key)){
                if($paymentData['api_key'] == $apiKeyData->secret_key){
                    $invoice = Invoice::where('invoice_number', $paymentData['partnerTransactionNo'])->first();

                    if(is_null($invoice)){
                        throw new HttpResponseException(
                            response()->json([
                                'message' => 'Transaction Error!',
                                'errors' => 'Invalid transaction or data not found!!',
                            ], 404)
                        );
                    }

                    $this->updateInvoiceProcess($invoice);

                    return response()->json([
                        'message' => 'Transaction Success',
                        'status' => 200
                    ], 200);

                } else {
                    throw new HttpResponseException(
                        response()->json([
                            'message' => 'Unauthorized!',
                            'errors' => 'Wrong API Key!',
                        ], 401)
                    );
                }
            } else {
                throw new HttpResponseException(
                    response()->json([
                        'message' => 'Unauthorized!',
                        'errors' => 'Wrong Password!',
                    ], 401)
                );
            }
        } catch (ValidationException $e) {
            Log::error('Payment callback validation failed', $e->errors());
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        }

    }

    private function updateInvoiceProcess($invoice) {
        $invoice->update([
            'payment_timestamp'     => Carbon::now(),
            'payment_status'        => 1,
            'payment_status_detail' => 'paid'
        ]);
    }
}
