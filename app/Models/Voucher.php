<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\User;
use App\Models\Location;

class Voucher extends Model {
    use HasFactory;

    protected $guarded = [];

    public function owner(): BelongsTo {
        return $this->belongsTo(User::class, 'userId');
    }

    public function redemptions(): HasMany {
        return $this->hasMany(VoucherRedemption::class, 'voucherId');
    }

    public function locations(): BelongsToMany {
        return $this->belongsToMany(Location::class, 'voucher_locations', 'voucherId', 'locationId');
    }

}
