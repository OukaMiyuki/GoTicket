<?php

namespace App\Http\Controllers\Api\Payment;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\PaymentCallbackRequest;
use App\Models\ApiAccess;
use Illuminate\Http\Request;
use App\Models\Invoice;

class PaymentConfirmController extends Controller {
    public function updatePayment(PaymentCallbackRequest $request){
        try{
            $paymentData = $request->validated();
            $apiKeyData = ApiAccess::first();
            if(Hash::check($paymentData['password'], $apiKeyData->key)){
                if($paymentData['api_key'] == $apiKeyData->secret_key){
                    Log::info('MASUK BOSSSSS');
                    return response()->json([
                        'success' => "OKE"
                    ]);
                } else {
                    Log::info("WRONG SECRET KEY");
                    return response()->json([
                        'error' => "WRONG SECRET KEY"
                    ]);
                }
            } else {
                Log::info('WRONG PASSWORD');
                return response()->json([
                    'error' => "WRONG Password"
                ]);
            }
        } catch (ValidationException $e) {
            Log::error('Payment callback validation failed', $e->errors());
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        }

    }

    // public function updatePayment(PaymentCallbackRequest $request){
    //     $paymentData = $request->validated();
    //     $apiKeyData = ApiAccess::first();

    //     if(!Hash::check($paymentData['password'], $apiKeyData->key)){
    //         if($paymentData['api_key'] == $apiKeyData->secret_key){
    //             Log::info('MASUK BOSSSSS');
    //             return response()->json([
    //                 'success' => "OKE"
    //             ]);
    //         } else {
    //             Log::info("WRONG SECRET KEY");
    //             return response()->json([
    //                 'error' => "WRONG SECRET KEY"
    //             ]);
    //         }
    //     } else {
    //         Log::info('WRONG PASSWORD');
    //         return response()->json([
    //             'error' => "WRONG Password"
    //         ]);
    //     }

    // }

    public function test(){
        return response()->json(
            [
                'status' => "success"
            ]
        );
    }
}
