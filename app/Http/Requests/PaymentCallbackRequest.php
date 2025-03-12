<?php

// app/Http/Requests/PaymentCallbackRequest.php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Log;

class PaymentCallbackRequest extends FormRequest {
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize() {
        // You can add authorization logic here if needed, e.g. checking for API key or user roles
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() {
        return [
            'login'                         => 'required|string',
            'password'                      => 'required|string',
            'api_key'                       => 'required|string',
            'partnerTransactionNo'          => 'required|string',
            'partnerReferenceNo'            => 'nullable|string',
            'amount'                        => 'required|numeric',
            'cleanAmount'                   => 'nullable|numeric',
            'mdrPaymentAmount'              => 'nullable|numeric',
            'paymentStatus'                 => 'required|string|in:PAID,FAILED',
            'invoice_number'                => 'required|string',
            'transactionTimestamp'          => 'required|date',
            'paymentTimeStamp'              => 'required|date',
            'responseStatus'                => 'required|string',
            'invoiceInfo'                   => 'nullable|array',
            'invoiceInfo.transactionDate'   => 'nullable|date',
            'invoiceInfo.paymentDate'       => 'nullable|date',
            'invoiceInfo.paymentType'       => 'nullable|string',
            'invoiceInfo.paymentStatus'     => 'nullable|string',
            'invoiceInfo.tax'               => 'nullable|numeric',
            'invoiceInfo.discount'          => 'nullable|numeric',
            'invoiceInfo.mdr'               => 'nullable|numeric',
            'invoiceInfo.mdrAmount'         => 'nullable|numeric',
            'invoiceInfo.cleanAmount'       => 'nullable|numeric',
            'invoiceInfo.aditionalInformation'                          => 'nullable|array',
            'invoiceInfo.aditionalInformation.customReference'          => 'nullable|string',
            'invoiceInfo.aditionalInformation.issuerId'                 => 'nullable|string',
            'invoiceInfo.aditionalInformation.retrievalReferenceNo'     => 'nullable|string',
            'invoiceInfo.aditionalInformation.paymentReferenceNo'       => 'nullable|string',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages() {
        return [
            'paymentStatus.in' => 'The payment status must be either "PAID" or "FAILED".',
            'transactionTimestamp.date' => 'The transaction timestamp must be a valid date.',
            'paymentTimeStamp.date' => 'The payment timestamp must be a valid date.',
            'login.required' => 'The login field is required.',
            'login.string' => 'The login field must be a string.',
            'password.required' => 'The password field is required.',
            'password.string' => 'The password field must be a string.',
            'api_key.required' => 'The API key field is required.',
            'api_key.string' => 'The API key field must be a string.',
            'partnerTransactionNo.required' => 'The partner transaction number is required.',
            'partnerTransactionNo.string' => 'The partner transaction number must be a string.',
            'amount.required' => 'The amount field is required.',
            'amount.numeric' => 'The amount must be a valid number.',
            'cleanAmount.numeric' => 'The clean amount must be a valid number.',
            'mdrPaymentAmount.numeric' => 'The MDR payment amount must be a valid number.',
            'invoice_number.required' => 'The invoice number is required.',
            'invoice_number.string' => 'The invoice number must be a string.',
            'responseStatus.required' => 'The response status is required.',
            'responseStatus.string' => 'The response status must be a string.',
            'invoiceInfo.array' => 'The invoice info must be an array.',
            'invoiceInfo.transactionDate.date' => 'The transaction date must be a valid date.',
            'invoiceInfo.paymentDate.date' => 'The payment date must be a valid date.',
            'invoiceInfo.paymentType.string' => 'The payment type must be a string.',
            'invoiceInfo.paymentStatus.string' => 'The payment status must be a string.',
            'invoiceInfo.tax.numeric' => 'The tax must be a valid number.',
            'invoiceInfo.discount.numeric' => 'The discount must be a valid number.',
            'invoiceInfo.mdr.numeric' => 'The MDR must be a valid number.',
            'invoiceInfo.mdrAmount.numeric' => 'The MDR amount must be a valid number.',
            'invoiceInfo.cleanAmount.numeric' => 'The clean amount must be a valid number.',
            'invoiceInfo.aditionalInformation.array' => 'The additional information must be an array.',
            'invoiceInfo.aditionalInformation.customReference.string' => 'The custom reference must be a string.',
            'invoiceInfo.aditionalInformation.issuerId.string' => 'The issuer ID must be a string.',
            'invoiceInfo.aditionalInformation.retrievalReferenceNo.string' => 'The retrieval reference number must be a string.',
            'invoiceInfo.aditionalInformation.paymentReferenceNo.string' => 'The payment reference number must be a string.',
        ];
    }

    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator) {
        // Log validation errors
        Log::error('Payment callback validation failed', $validator->errors()->toArray());
        Log::info('Payment status Value is', [
            'invoiceInfo_paymentStatus' => $this->input('invoiceInfo.paymentStatus'),
        ]);

        // Return a custom JSON response with validation errors
        throw new HttpResponseException(
            response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422)
        );
    }
}
