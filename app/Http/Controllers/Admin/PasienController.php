<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pasien;
use Illuminate\Http\Request;

class PasienController extends Controller
{
    public function index(Request $request)
    {
        $pasiens = Pasien::with('user')
            ->when($request->search, fn ($q) => $q
                ->where('no_rm', 'like', "%{$request->search}%")
                ->orWhereHas('user', fn ($q) =>
                    $q->where('name', 'like', "%{$request->search}%")
                )
            )
            ->latest()
            ->paginate(15);

        return view('admin.pasien.index', compact('pasiens'));
    }

    public function show(Pasien $pasien)
    {
        $pasien->load([
            'user',
            'appointments.dokter.user',
            'appointments.jadwal',
            'rekamMedis.dokter.user',
            'rekamMedis.appointment',
        ]);

        return view('admin.pasien.show', compact('pasien'));
    }

    public function destroy(Pasien $pasien)
    {
        // Hapus user supaya cascade ke tabel pasiens/relasi berjalan
        $pasien->user?->delete();

        return redirect()->route('admin.pasien.index')
            ->with('success', 'Pasien berhasil dihapus.');
    }
}
