<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;
use App\Models\Location;
use App\Models\Packet;
use App\Models\Invoice;

class Cart extends Model {
    protected $guarded = [];

    public function user(): BelongsTo {
        return $this->belongsTo(User::class, 'userId');
    }

    public function location(): BelongsTo {
        return $this->belongsTo(Location::class, 'locationId');
    }

    public function packet(): BelongsTo {
        return $this->belongsTo(Packet::class, 'packetId');
    }

    public function invoice(): BelongsTo {
        return $this->belongsTo(Invoice::class, 'invoiceId');
    }
}
