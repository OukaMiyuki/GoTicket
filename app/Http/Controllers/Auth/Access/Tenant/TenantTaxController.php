<?php

namespace App\Http\Controllers\Auth\Access\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Tax;

class TenantTaxController extends Controller {
    public function index(){
        if (! Auth::check()) {
            return redirect()->route('login');
        } else {
            $user = Auth::user();
            if ($user && !in_array($user->role, ['playground_owner'])) {
                abort(403);
            }

            $tax = Tax::where('userId', $user->id)->first();

            return view('auth.tenant.page.tax', compact('tax'));
        }
    }

    public function taxInsert(Request $request){
        $validatedData = $request->validate([
            'tax'       => 'required|numeric',
            'status'    => 'required',
        ]);

        $userId = Auth::id();
        $tax = Tax::where('userId', $userId)->first();

        if(is_null($tax)){
            Tax::create([
                'userId'        => $userId,
                'tax_value'     => $validatedData['tax'],
                'status'        => $validatedData['status']
            ]);
        } else {
            $tax->update([
                'tax_value'     => $validatedData['tax'],
                'status'        => $validatedData['status']
            ]);
        }

        return redirect()->back()->with('success', 'Tax data updated successfully!');
    }
}
