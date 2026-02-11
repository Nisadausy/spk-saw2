<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Siswa;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    // =========================
    // REGISTER
    // =========================
    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:120',
            'email' => 'required|email|unique:users,email',
            'password' => ['required', 'confirmed', Password::min(8)->letters()->numbers()],
            'sekolah_asal' => 'required|string|max:150',
            'jenis_kelamin' => 'required|in:L,P',
            'no_telepon' => 'required|string|max:30',
            'alamat' => 'required|string',
        ]);

        // role siswa harus ada di table roles
        $roleSiswa = Role::where('nama_role', 'siswa')->firstOrFail();

        DB::transaction(function () use ($validated, $roleSiswa) {
            $user = User::create([
                // Aman untuk schema ERD (role_id) dan juga jika migration pakai id
                'role_id' => $roleSiswa->role_id ?? $roleSiswa->id,

                'nama' => $validated['nama'],
                'email' => strtolower($validated['email']),
                'password_hash' => Hash::make($validated['password']),
                'is_active' => true,
                'must_change_password' => false,
            ]);

            // Aman untuk schema ERD (user_id) dan juga jika migration pakai id
            $userPk = $user->user_id ?? $user->id;

            Siswa::create([
                'user_id' => $userPk,
                'sekolah_asal' => $validated['sekolah_asal'],
                'jenis_kelamin' => $validated['jenis_kelamin'],
                'no_telepon' => $validated['no_telepon'],
                'alamat' => $validated['alamat'],
            ]);
        });

        return redirect()->route('login')->with('success', 'Registrasi berhasil, silakan login');
    }

    // =========================
    // LOGIN
    // =========================
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $email = strtolower($validated['email']);

        $user = User::where('email', $email)->first();

        if (!$user || !Hash::check($validated['password'], $user->password_hash)) {
            return back()->withInput()->with('error', 'Email atau password salah.');
        }

        if (!$user->is_active) {
            return back()->withInput()->with('error', 'Akun tidak aktif. Hubungi admin.');
        }

        // KUNCI: pastikan auth_user_id tidak null
        $userPk = $user->user_id ?? $user->id;

        $request->session()->put([
            'auth_user_id' => $userPk,
            'auth_role_id' => $user->role_id,
            'auth_user_name' => $user->nama,
        ]);

        // Security: ganti session id setelah login
        $request->session()->regenerate();

        return redirect()->route('home');
    }

    // =========================
    // LOGOUT
    // =========================
    public function logout(Request $request)
    {
        $request->session()->forget(['auth_user_id', 'auth_role_id', 'auth_user_name']);
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Berhasil logout.');
    }

    // =========================
    // HOME
    // =========================
    public function showHome(Request $request)
    {
        // Kalau value null, session()->has() akan false
        if (!$request->session()->has('auth_user_id')) {
            return redirect()->route('login')->with('error', 'Silakan login dulu.');
        }

        return view('dashboard.home');
    }
}
