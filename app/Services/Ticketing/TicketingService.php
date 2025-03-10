<?php

namespace App\Services\Ticketing;

use Illuminate\Support\Facades\Log;
use App\Models\Cart;
use App\Models\Location;
use App\Models\Ticket;
use App\Models\Invoice;
use Carbon\Carbon;
use Exception;

class TicketingService {
    public function checkAvailability($cartItems) {
        try {
            $locationIds = $cartItems->pluck('locationId')->unique();

            foreach ($locationIds as $locationId) {
                $location = Location::where('id', $locationId)->first();

                if (!$location) {
                    return ['status' => false, 'message' => 'Location not found'];
                }

                $soldTickets = Ticket::where('locationId', $locationId)
                                    ->whereDate('created_at', now()->toDateString())
                                    ->count();

                $newTickets = $cartItems->where('locationId', $locationId)->sum('qty');

                if (($soldTickets + $newTickets) > $location->max_ticket_quota) {
                    return ['status' => false, 'message' => 'Not enough tickets available for location: ' . $location->name];
                }
            }

            return ['status' => true, 'message' => 'Tickets available'];

        } catch (Exception $e) {
            return ['status' => false, 'message' => $e->getMessage()];
        }
    }

    public function generateTickets($userId, $cartItems, $invoice) {
        $now = Carbon::now();
        $end_date = $now->addDays(3);

        try {
            $locationTicketCount = [];

            foreach ($cartItems as $cartItem) {
                $locationId = $cartItem->packet->locationId;
                $qty = $cartItem->qty;

                if (!isset($locationTicketCount[$locationId])) {
                    $locationTicketCount[$locationId] = 0;
                }

                $locationTicketCount[$locationId] += $qty;

                for ($i = 0; $i < $qty; $i++) {
                    Ticket::create([
                        'userId'            => $userId,
                        'locationId'        => $cartItem->locationId,
                        'packetId'          => $cartItem->packetId,
                        'invoiceId'         => $invoice->id,
                        'start_date'        => $now,
                        'end_date'          => $end_date,
                    ]);
                }

                $cartItem->update(['invoiceId' => $invoice->id]);
            }

            foreach ($locationTicketCount as $locationId => $ticketCount) {
                Location::where('id', $locationId)->decrement('max_ticket_quota', $ticketCount);
            }

            return ['status' => true, 'message' => 'Tickets generated successfully'];

        } catch (Exception $e) {
            // return ['status' => false, 'message' => $e->getMessage()];
            Log::error('Ticket generation failed: ' . $e->getMessage());
            throw $e;
        }
    }
}
