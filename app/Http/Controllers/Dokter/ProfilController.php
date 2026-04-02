<?php

namespace App\Http\Controllers\Dokter;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfilController extends Controller
{
    public function edit()
    {
        $dokter = Auth::user()->dokter;
        return view('dokter.profil.edit', compact('dokter'));
    }

    public function update(Request $request)
    {
        $user   = Auth::user();
        $dokter = $user->dokter;

        $request->validate([
            'name'         => 'required|string|max:255',
            'email'        => 'required|email|unique:users,email,' . $user->id,
            'spesialisasi' => 'required|string|max:100',
            'no_hp'        => 'nullable|string|max:20',
            'bio'          => 'nullable|string',
            'password'     => 'nullable|min:8|confirmed',
        ]);

        $user->update(['name' => $request->name, 'email' => $request->email]);

        if ($request->filled('password')) {
            $user->update(['password' => Hash::make($request->password)]);
        }

        $dokter->update([
            'spesialisasi' => $request->spesialisasi,
            'no_hp'        => $request->no_hp,
            'bio'          => $request->bio,
        ]);

        return back()->with('success', 'Profil berhasil diperbarui.');
    }
}
