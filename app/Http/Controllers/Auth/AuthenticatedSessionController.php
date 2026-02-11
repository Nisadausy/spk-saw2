<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        // Breeze authenticate (akan pakai password_hash karena model User Anda sudah override getAuthPasswordName())
        $request->authenticate();

        $request->session()->regenerate();

        $user = Auth::user();

        // Ambil nama role berdasarkan role_id pada users (aman untuk roles PK id / role_id)
        $roleName = DB::table('roles')
            ->where(function ($q) use ($user) {
                if (DB::getSchemaBuilder()->hasColumn('roles', 'role_id')) {
                    $q->where('role_id', $user->role_id);
                } else {
                    $q->where('id', $user->role_id);
                }
            })
            ->value('nama_role');

        // Redirect sesuai role:
        // - siswa => home (dashboard siswa)
        // - admin => admin.dashboard
        // - guru_bk => bk.dashboard
        // Fallback: dashboard default
        if ($roleName === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        if ($roleName === 'guru_bk') {
            return redirect()->route('bk.dashboard');
        }

        // Default siswa
        return redirect()->route('home');
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
