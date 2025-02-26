<?php

namespace App\Http\Controllers\Auth\Access\Operator;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class OperatorTransactionController extends Controller {
    public function index(){
        if (! Auth::check()) {
            return redirect()->route('login');
        } else {
            $user = Auth::user();
            if ($user && !in_array($user->role, ['playground_operator'])) {
                abort(403);
            }
            return view('auth.operator.transaction');
        }
    }
}
