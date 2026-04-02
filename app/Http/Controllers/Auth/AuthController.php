<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Pasien;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()
                ->withErrors(['email' => 'Email atau password salah.'])
                ->withInput($request->only('email'));
        }

        $request->session()->regenerate();
        $user = Auth::user();

        if (! $user->is_active) {
            Auth::logout();
            return back()->with('error', 'Akun Anda dinonaktifkan. Hubungi administrator.');
        }

        return redirect()->intended(match ($user->role) {
            'admin'  => route('admin.dashboard'),
            'dokter' => route('dokter.dashboard'),
            default  => route('pasien.dashboard'),
        });
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name'          => 'required|string|max:255',
            'email'         => 'required|email|unique:users',
            'password'      => 'required|min:8|confirmed',
            'tanggal_lahir' => 'required|date|before:today',
            'jenis_kelamin' => 'required|in:L,P',
            'golongan_darah'=> 'nullable|in:A,B,AB,O',
            'no_hp'         => 'required|string|max:20',
            'alamat'        => 'required|string',
        ]);

        $user = User::create([
            'name'      => $request->name,
            'email'     => $request->email,
            'password'  => Hash::make($request->password),
            'role'      => 'pasien',
            'is_active' => true,
        ]);

        Pasien::create([
            'user_id'       => $user->id,
            'no_rm'         => 'RM-' . str_pad($user->id, 6, '0', STR_PAD_LEFT),
            'tanggal_lahir' => $request->tanggal_lahir,
            'jenis_kelamin' => $request->jenis_kelamin,
            'golongan_darah'=> $request->golongan_darah,
            'no_hp'         => $request->no_hp,
            'alamat'        => $request->alamat,
        ]);

        Auth::login($user);

        return redirect()->route('pasien.dashboard')
            ->with('success', 'Registrasi berhasil! Selamat datang, ' . $user->name . '.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
