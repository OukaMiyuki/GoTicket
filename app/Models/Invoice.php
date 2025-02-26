<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Models\User;
use App\Models\Ticket;
use App\Models\VoucherRedemption;
use App\Models\Cart;

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
}
