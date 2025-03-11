<?php

namespace App\Http\Controllers\Auth\Access\Operator;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Services\Payment\Qris\QrisPaymentService;
use App\Services\Ticketing\TicketingService;
use Carbon\Carbon;
use App\Models\Packet;
use App\Models\Cart;
use App\Models\Tax;
use App\Models\Location;
use App\Models\User;
use App\Models\Invoice;
use Exception;

class OperatorTransactionController extends Controller {

    protected $visiposQrisService;

    public function __construct(QrisPaymentService $vsiposQrisService) {
        $this->visiposQrisService = $vsiposQrisService;
    }

    public function index(){
        if (! Auth::check()) {
            return redirect()->route('login');
        } else {
            $user = Auth::user();
            if ($user && !in_array($user->role, ['playground_operator'])) {
                abort(403);
            }
            return view('auth.operator.page.transaction');
        }
    }
    public function fetchData(Request $request) {

        $search = $request->query('search', '');
        $page = $request->query('page', 1);

        $packets = Packet::with(['galleries', 'location'])
                        ->where('packet_name', 'like', "%{$search}%")
                        ->paginate(10);

        return response()->json($packets);
    }

    public function addToCart(Request $request) {
        $userId = Auth::id();
        $packet = Packet::find($request->packetId);

        if (!$packet) {
            return response()->json(['message' => 'Packet not found.'], 404);
        }

        $cartItem = Cart::where('userId', $userId)
                    ->where('packetId', $request->packetId)
                    ->whereNull('invoiceId')
                    ->first();

        if ($cartItem) {
            return response()->json(['message' => 'This item is already in your cart.'], 200);
        }

        $cart = Cart::create([
            'userId' => $userId,
            'packetId' => $request->packetId,
            'locationId' => $request->locationId,
            'invoiceId' => NULL,
            'qty' => $request->qty,
            'price' => $packet->price,
            'sub_total' => $request->qty * $packet->price,
        ]);

        return response()->json(['message' => 'Item added to cart', 'cart' => $cart]);
    }

    public function getCartItems(Request $request) {
        $tax_value = 0;
        $userId = Auth::id();
        $user = User::with('location')->find($userId);
        $tenantId = collect($user->location)->pluck('userId')->first();
        $tax = Tax::where('userId', $tenantId)->first();

        if(!is_null($tax)){
            if($tax->status == 1){
                $tax_value = $tax->tax_value;
            }
        }

        $cartData = Cart::with([
                                'user',
                                'location',
                                'packet' => function($query){
                                    $query->with(['galleries'])->get();
                                },
                        ])
                        ->whereNull('invoiceId')
                        ->where('userId', $userId)
                        ->latest()
                        ->get();

        return response()->json([
            'cartItems'     => $cartData,
            'tax_value'     => $tax_value,
            'total'         => $cartData->sum('sub_total'),
        ]);
    }

    public function updateCartItemQuantity(Request $request, $id) {
        $userId = Auth::id();
        $cartItem = Cart::where('userId', $userId)->whereNull('invoiceId')->where('id', $id)->first();

        if (!$cartItem) {
            return response()->json(['message' => 'Cart item not found'], 404);
        }

        $validated = $request->validate([
            'qty' => 'required|integer|min:1',
        ]);

        $cartItem->qty = $validated['qty'];
        $cartItem->sub_total = $cartItem->price * $cartItem->qty;

        $cartItem->save();

        return response()->json(['message' => 'Cart item updated successfully', 'cart' => $cartItem]);
    }

    public function removeFromCart($itemId) {
        try {

            $userId = Auth::id();
            $cartItem = Cart::where('userId', $userId)->whereNull('invoiceId')->where('id', $itemId)->first();

            if (!$cartItem) {
                return response()->json(['message' => 'Item not found in cart.'], 404);
            }

            $cartItem->delete();

            return response()->json(['message' => 'Item removed from cart successfully.']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred while removing the item from the cart.'], 500);
        }
    }

    public function checkoutIndex(){
        if (! Auth::check()) {
            return redirect()->route('login');
        } else {
            $user = Auth::user();
            if ($user && !in_array($user->role, ['playground_operator'])) {
                abort(403);
            }
            return view('auth.operator.page.checkout');
        }
    }

    public function checkoutProcess(Request $request) {
        $validatedData = $request->validate([
            'fullName' => 'required|string|max:255',
            'mobileNumber' => 'nullable|numeric',
            'email' => 'nullable|email',
            'address' => 'nullable|string',
            'note' => 'nullable|string',
            'paymentMethod' => 'required|in:tunai,qris,va_nobu',
        ]);

        Log::info($request->all());

        $now = Carbon::now();
        $userId = Auth::id();
        $tax_value = 0;
        $tax_price = 0;
        $redirecting = "";

        $cartData = Cart::with([
                                'user',
                                'location',
                                'packet' => function($query){
                                    $query->with(['galleries'])->get();
                                },
                        ])
                        ->whereNull('invoiceId')
                        ->where('userId', $userId)
                        ->latest()
                        ->get();

        if($cartData->isEmpty()){
            return redirect()->back()->with('error', 'Your cart is empty!');
        }

        $ticketService = new TicketingService();

        $availabilityCheck = $ticketService->checkAvailability($cartData);
        if (!$availabilityCheck['status']) {
            return redirect()->back()->with('error', $availabilityCheck['message']);
        }

        $qtyTotal = $cartData->sum('qty');
        $priceAmount = $cartData->sum('sub_total');
        $user = User::with('location')->find($userId);
        $tenantId = collect($user->location)->pluck('userId')->first();
        $tax = Tax::where('userId', $tenantId)->first();

        if(!is_null($tax)){
            if($tax->status == 1){
                $tax_value = $tax->tax_value;
                $tax_price = ($tax_value/100)*$priceAmount;
                $total_payment_amount = $tax_price+$priceAmount;
            }
        }

        DB::beginTransaction();

        try {

            $invoice = Invoice::create([
                'userId'                    => $userId,
                'operatorId'                => $userId,
                'transaction_timestamp'     => $now,
                'payment_timestamp'         => $now,
                'qty'                       => $qtyTotal,
                'price'                     => $priceAmount,
                'tax'                       => $tax_value,
                'tax_value'                 => $tax_price,
                'discount'                  => 0,
                'discount_value'            => 0,
                'total_payment_amount'      => $total_payment_amount,
            ]);

            $ticketGeneration = $ticketService->generateTickets($userId, $cartData, $invoice);
            if (!$ticketGeneration['status']) {
                // return redirect()->back()->with('error', $ticketGeneration['message']);
                return response()->json([
                    'success' => false,
                    'message' => $ticketGeneration['message'],
                ], 500);
            }

            DB::commit();

            $paymentMethod = $validatedData['paymentMethod'];
            $redirectUrl = $this->handleRedirectionAfterCheckout($invoice, $paymentMethod);

            return response()->json([
                'success' => true,
                'message' => 'Payment processed successfully!',
                'redirectUrl' => $redirectUrl,
            ]);

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Checkout process failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'There was an issue processing your order: ' . $e->getMessage(),
            ], 500);
        }
    }

    private function handleRedirectionAfterCheckout($invoice, $paymentMethod){
        if(!is_null($invoice)){
            if($paymentMethod== 'tunai'){
                return url('/operator/transaction/invoice/ticket/' . $invoice->id);
            } else {
                return url('/operator/transaction/checkout/pay/' . $invoice->id);
            }
        }

        return response()->json([
            'success' => false,
            'message' => 'Checkout process error, please contact admin!'
        ], 500);
    }

    public function testQris(){
        $qris = $this->visiposQrisService->generateQRIS([
            'amount' => "1",
            'partnerTransactionNo' => "877986798789",
            'partnerReferenceNo' => "Ticket",
            'customReference' => "test",
            'validTime' => "900"
        ]);

        dd($qris);
    }
}
