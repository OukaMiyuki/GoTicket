<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Packet;

class PacketGallery extends Model {
    use HasFactory;

    protected $guarded = [];

    public function packet(): BelongsTo {
        return $this->belongsTo(Packet::class, 'packetId');
    }
}
