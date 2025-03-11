<?php

namespace App\Http\Controllers\Api\Payment;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\PaymentCallbackRequest;
use App\Models\ApiAccess;
use Illuminate\Http\Request;
use App\Models\Invoice;

class PaymentConfirmController extends Controller {
    public function updatePayment(PaymentCallbackRequest $request){
        $paymentData = $request->validated();
        $apiKeyData = ApiAccess::first();

        if(!Hash::check($paymentData['password'], $apiKeyData->key)){
            if($paymentData['api_key'] == $apiKeyData->secret_key){
                Log::info('MASUK BOSSSSS');
                echo "MASUK";
            } else {
                Log::info("WRONG SECRET KEY");
                echo "SA:AH SECRET KEY";
            }
        } else {
            Log::info('WRONG PASSWORD');
            echo "SALAH PASSWORD";
        }

    }
}
