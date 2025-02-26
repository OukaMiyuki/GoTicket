<?php

namespace App\Http\Controllers\Auth\Access;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class DashboardController extends Controller {
    public function index() {
        $role = Auth::user()->role;

        return match ($role) {
            'super_user' => redirect()->route('super.admin.dashboard'),
            'administrator' => redirect()->route('admin.dashboard'),
            'playground_owner' => redirect()->route('tenant.dashboard'),
            'playground_supervisor' => redirect()->route('supervisor.dashboard'),
            'playground_operator' => redirect()->route('operator.dashboard'),
            'visitor_member' => redirect()->route('visitor.dashboard'),
            default => abort(403),
        };
    }

    public function superAdmin() { return view('auth.superuser.dashboard'); }
    public function admin() { return view('auth.admin.dashboard'); }
    public function tenant() { return view('auth.tenant.dashboard'); }
    public function supervisor() { return view('auth.supervisor.dashboard'); }
    public function operator() { return view('auth.operator.dashboard'); }
    public function visitor() { return view('auth.visitor.dashboard'); }
}
