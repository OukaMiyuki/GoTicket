<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Ticket;

class TicketDetail extends Model {
    use HasFactory;

    protected $guarded = [];

    public function ticket(): BelongsTo {
        return $this->belongsTo(Ticket::class, 'ticketId');
    }
}
