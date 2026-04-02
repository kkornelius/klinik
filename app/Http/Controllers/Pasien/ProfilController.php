<?php

namespace App\Http\Controllers\Pasien;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfilController extends Controller
{
    public function edit()
    {
        $pasien = Auth::user()->pasien;
        return view('pasien.profil.edit', compact('pasien'));
    }

    public function update(Request $request)
    {
        $user   = Auth::user();
        $pasien = $user->pasien;

        $request->validate([
            'name'          => 'required|string|max:255',
            'email'         => 'required|email|unique:users,email,' . $user->id,
            'tanggal_lahir' => 'nullable|date|before:today',
            'jenis_kelamin' => 'nullable|in:L,P',
            'no_hp'         => 'nullable|string|max:20',
            'alamat'        => 'nullable|string',
            'golongan_darah'=> 'nullable|in:A,B,AB,O',
            'alergi'        => 'nullable|string|max:255',
            'password'      => 'nullable|min:8|confirmed',
        ]);

        $user->update(['name' => $request->name, 'email' => $request->email]);

        if ($request->filled('password')) {
            $user->update(['password' => Hash::make($request->password)]);
        }

        $pasien->update($request->only([
            'tanggal_lahir', 'jenis_kelamin', 'no_hp', 'alamat', 'golongan_darah', 'alergi',
        ]));

        return back()->with('success', 'Profil berhasil diperbarui.');
    }
}
