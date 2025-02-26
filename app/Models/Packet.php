<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Location;
use App\Models\PacketGallery;
use App\Models\Cart;

class Packet extends Model {
    use HasFactory;

    protected $guarded = [];

    public function location(): BelongsTo {
        return $this->belongsTo(Location::class, 'locationId');
    }

    public function galleries(): HasMany {
        return $this->hasMany(PacketGallery::class, 'packetId');
    }

    public function cart(): HasMany {
        return $this->hasMany(Cart::class, 'packetId');
    }
}
