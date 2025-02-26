<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller {
    /**
     * Display the login view.
     */
    public function create(): View {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse {
        $request->authenticate();
        $request->session()->regenerate();
    
        // if (!Auth::user()->hasVerifiedEmail()) {
        //     Auth::logout();
        //     return redirect()->route('verification.notice')->with('error', 'Please verify your email before logging in.');
        // }
    
        $role = Auth::user()->role;
    
        return match ($role) {
            'super_user' => redirect()->route('super.admin.dashboard'),
            'administrator' => redirect()->route('admin.dashboard'),
            'playground_owner' => redirect()->route('tenant.dashboard'),
            'playground_supervisor' => redirect()->route('supervisor.dashboard'),
            'playground_operator' => redirect()->route('operator.dashboard'),
            'visitor_member' => redirect()->route('visitor.dashboard'),
            default => redirect()->route('dashboard'),
        };
    }
    


    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
