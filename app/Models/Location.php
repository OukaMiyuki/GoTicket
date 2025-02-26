<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Models\User;
use App\Models\Packet;
use App\Models\LocationType;
use App\Models\Voucher;

class Location extends Model {
    use HasFactory;

    protected $guarded = [];

    public function user(): BelongsTo {
        return $this->belongsTo(User::class, 'userId');
    }

    public function packets(): HasMany {
        return $this->hasMany(Packet::class, 'locationId');
    }

    public function locationTypes(): BelongsToMany {
        return $this->belongsToMany(LocationType::class, 'locations_locations_types', 'locationId', 'locationTypeId');
    }

    public function users(): BelongsToMany {
        return $this->belongsToMany(User::class, 'locations_users', 'locationId', 'userId');
    }

    public function vouchers(): BelongsToMany {
        return $this->belongsToMany(Voucher::class, 'voucher_locations', 'locationId', 'voucherId');
    }
}
