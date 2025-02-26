<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;
use App\Models\Voucher;
use App\Models\Invoice;

class VoucherRedemption extends Model {
    use HasFactory;

    protected $guarded = [];

    public function user(): BelongsTo {
        return $this->belongsTo(User::class, 'userId');
    }

    public function voucher(): BelongsTo {
        return $this->belongsTo(Voucher::class, 'voucherId');
    }

    public function invoice(): BelongsTo {
        return $this->belongsTo(Invoice::class, 'invoiceId');
    }
}
