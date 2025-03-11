<?php

// app/Http/Requests/PaymentCallbackRequest.php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

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
    public function messages()
    {
        return [
            'paymentStatus.in' => 'The payment status must be either "PAID" or "FAILED".',
            'transactionTimestamp.date' => 'The transaction timestamp must be a valid date.',
            'paymentTimeStamp.date' => 'The payment timestamp must be a valid date.',
            // Add custom messages for other rules if needed
        ];
    }
}
