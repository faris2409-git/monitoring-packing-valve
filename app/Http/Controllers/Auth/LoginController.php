<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function showLoginForm(Request $request)
    {
        $role = $request->query('role', 'operator');
        return view('auth.login', compact('role'));
    }

    public function login(Request $request)
    {
        $role = $request->input('role', 'operator');

        if ($role === 'operator') {
            $request->validate([
                'username' => 'required|string',
                'npk' => 'required|string',
                'password' => 'required|string',
                'shift' => 'required|string',
            ], [
                'username.required' => 'Username operator wajib diisi.',
                'npk.required' => 'NPK wajib diisi.',
                'password.required' => 'Password wajib diisi.',
                'shift.required' => 'Pilih shift operasional Anda.',
            ]);

            $user = User::where('role', 'operator')
                ->where(function ($query) use ($request) {
                    $query->where('username', $request->username)
                          ->orWhere('email', $request->username);
                })
                ->where('npk', $request->npk)
                ->first();

            if (!$user || !Hash::check($request->password, $user->password)) {
                return back()->withInput()->withErrors([
                    'login' => 'Kombinasi Username, NPK, atau Password operator tidak sesuai.',
                ]);
            }

            Auth::login($user);
            $user->update(['shift' => $request->shift]);
            session(['active_shift' => $request->shift]);
            $request->session()->regenerate();

            return redirect()->route('operator.dashboard')
                ->with('success', 'Berhasil masuk ke Workstation ' . $request->shift);
        } else {
            // Supervisor login (by email or username)
            $request->validate([
                'login_id' => 'required|string',
                'password' => 'required|string',
            ], [
                'login_id.required' => 'User ID / Email supervisor wajib diisi.',
                'password.required' => 'Password wajib diisi.',
            ]);

            $user = User::where('role', 'supervisor')
                ->where(function ($query) use ($request) {
                    $query->where('email', $request->login_id)
                          ->orWhere('username', $request->login_id);
                })
                ->first();

            if (!$user || !Hash::check($request->password, $user->password)) {
                return back()->withInput()->withErrors([
                    'login' => 'Email / Username atau Password supervisor salah.',
                ]);
            }

            Auth::login($user);
            session(['active_shift' => 'Supervisor Shift Control']);
            $request->session()->regenerate();

            return redirect()->route('supervisor.dashboard')
                ->with('success', 'Selamat datang di Command Center Supervisor');
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('landing');
    }
}