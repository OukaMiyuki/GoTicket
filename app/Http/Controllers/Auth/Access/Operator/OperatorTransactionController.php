<?php

namespace App\Http\Controllers\Auth\Access\Operator;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Packet;

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

}
