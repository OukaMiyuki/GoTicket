<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Models\User;
use App\Models\Location;
use App\Models\Packet;
use App\Models\Invoice;
use App\Models\TicketDetail;

class Ticket extends Model {
    use HasFactory;

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

    public function ticketDetail(): HasOne {
        return $this->hasOne(TicketDetail::class, 'ticketId');
    }

    public static function boot() {
        parent::boot();

        static::created(function ($model) {
            $code = "GTCX";
            $date = date('dmY');
            $index_number = (int) Ticket::max('id') + 1;
            $generate_ticket_id = $code . $date . str_pad($index_number, 15, '0', STR_PAD_LEFT);
            $ticketDetail = new TicketDetail();
            $ticketDetail->ticketId = $model->id;
            $ticketDetail->ticket_unique_id = $generate_ticket_id;
            $ticketDetail->save();
        });
    }
}
