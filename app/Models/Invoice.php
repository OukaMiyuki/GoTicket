<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Log;
use App\Services\Payment\Qris\QrisPaymentService;
use App\Models\User;
use App\Models\Ticket;
use App\Models\VoucherRedemption;
use App\Models\Cart;
use App\Models\InvoiceDetail;
use Exception;

class Invoice extends Model {
    use HasFactory;

    protected $guarded = [];

    public function user(): BelongsTo {
        return $this->belongsTo(User::class, 'userId');
    }

    public function tickets(): HasMany {
        return $this->hasMany(Ticket::class, 'invoiceId');
    }

    public function voucherRedemption(): HasOne {
        return $this->hasOne(VoucherRedemption::class);
    }

    public function cart(): HasMany {
        return $this->hasMany(Cart::class, 'invoiceId');
    }

    public function detail(): HasMany {
        return $this->hasMany(InvoiceDetail::class, 'invoiceId');
    }

    public static function boot() {
        parent::boot();

        static::created(function ($model) {
            $invoice_code = "GTX";
            $date = date('dmY');
            $index_number = (int) Invoice::max('id') + 1;
            $generate_nomor_invoice = $invoice_code . $date . str_pad($index_number, 15, '0', STR_PAD_LEFT);
            $model->invoice_number = $generate_nomor_invoice;
            $model->save();
            $invoiceDetail                  = new InvoiceDetail();
            $invoiceDetail->invoiceId       = $model->id;
            $invoiceDetail->name            = request()->fullName;
            $invoiceDetail->email           = request()->email;
            $invoiceDetail->phone_number    = request()->mobileNumber;
            $invoiceDetail->address         = request()->address;
            $invoiceDetail->note            = request()->note;
            $invoiceDetail->save();
            self::generatePayment($model);
        });
    }
    

    private static function generatePayment($model){
        if(request()->paymentMethod === "qris") {
            $model->payment_method = 'Qris';
            $model->payment_status = 0;
            $model->payment_status_detail = 'pending';
            $qris = self::generateQris($model);
            if(!is_null($qris)){
                $responseMessage = $qris['message'];
                $responseStatus = $qris['status'];

                if(($responseMessage == "Transaction Success" || $responseMessage == "Transaction Updated") &&
                    ($responseStatus == 20011 || $responseStatus == 20012)
                ){
                    $transactionData = $qris['transactionData'];
                    $qrisData = $transactionData['qris_data'];
                    $model->payment_reference = $qrisData;
                } else {
                    Log::channel('qris_response_error')->error("Payment request failed! Result:\n" . json_encode($qris, JSON_PRETTY_PRINT));
                    throw new Exception('Payment processing failed');
                }
            } else {
                Log::channel('qris_response_error')->error("There's an error processing Qris Payment : Null Response.");
                throw new Exception('Payment processing failed');
            }
        } else if(request()->paymentMethod === "tunai"){
            $model->payment_method = 'Tunai';
            $model->payment_status = 1;
            $model->payment_status_detail = 'paid';
        }

        $model->save();
    }

    private static function generateQris($model) {
        $qris = new QrisPaymentService();
        $amountPay = $model->total_payment_amount;
        $invoice_number = $model->invoice_number;
        $monthYear = now()->format('mY');
        $ref = 'TRX'.$monthYear;

        $qrisResult = $qris->generateQRIS([
            'amount' => $amountPay,
            'partnerTransactionNo' => $invoice_number,
            'partnerReferenceNo' => $ref,
            'customReference' => "Ticket Online",
            'validTime' => "900"
        ]);

        if(!is_null($qrisResult)){
            return $qrisResult;
        } else {
            return null;
        }
    }

    private static function generateVA($model){

    }
}
