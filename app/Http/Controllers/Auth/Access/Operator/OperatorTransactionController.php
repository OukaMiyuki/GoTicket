<?php

namespace App\Http\Controllers\Auth\Access\Operator;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Packet;
use App\Models\Cart;

class OperatorTransactionController extends Controller {

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

        $packets = Packet::with('galleries')
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
        $userId = Auth::id();
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
            'cartItems' => $cartData, 
            'total' => $cartData->sum('sub_total'),
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
    
}
