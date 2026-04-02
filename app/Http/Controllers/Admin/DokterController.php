<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dokter;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DokterController extends Controller
{
    public function index(Request $request)
    {
        $dokters = Dokter::with('user')
            ->when($request->search, fn ($q) => $q->whereHas('user', fn ($q) =>
                $q->where('name', 'like', "%{$request->search}%")
            ))
            ->latest()
            ->paginate(12);

        return view('admin.dokter.index', compact('dokters'));
    }

    public function create()
    {
        return view('admin.dokter.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'         => 'required|string|max:255',
            'email'        => 'required|email|unique:users',
            'password'     => 'required|min:8',
            'spesialisasi' => 'required|string|max:100',
            'no_str'       => 'required|string|max:50|unique:dokters',
            'no_hp'        => 'nullable|string|max:20',
            'bio'          => 'nullable|string',
        ]);

        DB::transaction(function () use ($request) {
            $user = User::create([
                'name'      => $request->name,
                'email'     => $request->email,
                'password'  => Hash::make($request->password),
                'role'      => 'dokter',
                'is_active' => true,
            ]);

            Dokter::create([
                'user_id'      => $user->id,
                'spesialisasi' => $request->spesialisasi,
                'no_str'       => $request->no_str,
                'no_hp'        => $request->no_hp,
                'bio'          => $request->bio,
            ]);
        });

        return redirect()->route('admin.dokter.index')
            ->with('success', 'Dokter berhasil ditambahkan.');
    }

    public function edit(Dokter $dokter)
    {
        return view('admin.dokter.edit', compact('dokter'));
    }

    public function update(Request $request, Dokter $dokter)
    {
        $request->validate([
            'name'         => 'required|string|max:255',
            'email'        => 'required|email|unique:users,email,' . $dokter->user_id,
            'spesialisasi' => 'required|string|max:100',
            'no_str'       => 'required|string|max:50|unique:dokters,no_str,' . $dokter->id,
            'no_hp'        => 'nullable|string|max:20',
            'bio'          => 'nullable|string',
            'password'     => 'nullable|min:8',
        ]);

        DB::transaction(function () use ($request, $dokter) {
            $userData = ['name' => $request->name, 'email' => $request->email];
            if ($request->filled('password')) {
                $userData['password'] = Hash::make($request->password);
            }
            $dokter->user->update($userData);

            $dokter->update([
                'spesialisasi' => $request->spesialisasi,
                'no_str'       => $request->no_str,
                'no_hp'        => $request->no_hp,
                'bio'          => $request->bio,
            ]);
        });

        return redirect()->route('admin.dokter.index')
            ->with('success', 'Data dokter berhasil diperbarui.');
    }

    public function destroy(Dokter $dokter)
    {
        $dokter->user->delete(); // cascade deletes dokter
        return redirect()->route('admin.dokter.index')
            ->with('success', 'Dokter berhasil dihapus.');
    }

    public function toggle(Dokter $dokter)
    {
        $active = ! $dokter->user->is_active;
        $dokter->user->update(['is_active' => $active]);
        $label = $active ? 'diaktifkan' : 'dinonaktifkan';
        return back()->with('success', "Akun dokter berhasil {$label}.");
    }
}
