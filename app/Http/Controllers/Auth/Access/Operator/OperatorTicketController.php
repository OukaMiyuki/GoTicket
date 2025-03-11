<?php

namespace App\Http\Controllers\Auth\Access\Operator;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Invoice;
use App\Models\Ticket;

class OperatorTicketController extends Controller {
    public function ticketInvoicePayment($invoiceId){
        $invoice = Invoice::find($invoiceId);

        if(is_null($invoice)){
            return redirect()->back()->with('error', 'Ticket data not found, please contact Admin!');
        }

        if ($invoice->payment_status == 1) {
            if ($invoice->payment_status_detail == "paid") {
                return redirect()->route('operator.transaction.invoice.ticket', ['invoiceId' => $invoiceId])
                                 ->with('warning', 'Transaction has already been finished!');
            }

            if ($invoice->payment_status_detail == "cancelled") {
                return redirect()->back()->with('error', 'Transaction is cancelled!');
            }
        }

        return view('auth.operator.page.invoicePay', compact(['invoice']));
    }

    public function ticketInvoiceList($invoiceId){
        if (! Auth::check()) {
            return redirect()->route('login');
        } else {

            $ticket = Invoice::with(['tickets.packet.galleries', 'tickets.packet.location', 'tickets.ticketDetail'])
                            ->find($invoiceId);

            if(is_null($ticket)){
                return redirect()->back()->with('error', 'Invoice data not found!');
            }

            if(($ticket->payment_status == 0) && ($ticket->payment_status_detail == "pending" || $ticket->payment_status_detail == "cancelled")){
                return redirect()->back()->with('error', 'Payment not yet finished!');
            }

            return view('auth.operator.page.ticketInvoice', compact('ticket'));
        }
    }

    public function ticketInvoiceInfoInsert(Request $request){
        $ticket = Ticket::with(['ticketDetail'])
                        ->where('userId', Auth::id())
                        ->whereHas('ticketDetail', function ($query) use ($request) {
                            $query->where('ticket_unique_id', $request->ticket_id);
                        })
                        ->where('id', $request->id)
                        ->first();
        if(is_null($ticket)){
            return redirect()->back()->with('error', 'Data not found!');
        }

        if(!is_null($ticket->ticketDetail->owner_name)){
            return redirect()->back()->with('error', 'Operation not allowed!');
        }

        $ticket->ticketDetail->update([
            'owner_name'            => $request->name,
            'id_number'             => $request->id_number,
            'owner_phone_number'    => $request->phone,
            'owner_email_address'   => $request->email,
            'owner_address'         => $request->address
        ]);

        return redirect()->back()->with('success', 'Ticket information has been successfully updated!');
    }
}
