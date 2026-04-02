<?php

namespace App\Http\Controllers\Dokter;

use App\Http\Controllers\Controller;
use App\Models\JadwalDokter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JadwalController extends Controller
{
    public function index()
    {
        $dokter  = Auth::user()->dokter;
        $jadwals = $dokter->jadwals()->orderByRaw(
            "CASE hari "
            . "WHEN 'Senin' THEN 1 WHEN 'Selasa' THEN 2 WHEN 'Rabu' THEN 3 "
            . "WHEN 'Kamis' THEN 4 WHEN 'Jumat' THEN 5 WHEN 'Sabtu' THEN 6 "
            . "WHEN 'Minggu' THEN 7 ELSE 8 END"
        )->get();

        $HARI = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];

        return view('dokter.jadwal.index', compact('jadwals', 'HARI'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'hari'        => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu,Minggu',
            'jam_mulai'   => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
            'kuota'       => 'required|integer|min:1|max:50',
        ]);

        $dokter = Auth::user()->dokter;

        // Cek duplikasi hari + jam overlap
        $overlap = JadwalDokter::where('dokter_id', $dokter->id)
            ->where('hari', $request->hari)
            ->where('is_active', true)
            ->where('jam_mulai', '<', $request->jam_selesai)
            ->where('jam_selesai', '>', $request->jam_mulai)
            ->exists();

        if ($overlap) {
            return back()->with('error', 'Jadwal pada hari dan jam tersebut sudah ada / bentrok.')
                         ->withInput();
        }

        JadwalDokter::create([
            'dokter_id'   => $dokter->id,
            'hari'        => $request->hari,
            'jam_mulai'   => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,
            'kuota'       => $request->kuota,
        ]);

        return back()->with('success', 'Jadwal berhasil ditambahkan.');
    }

    public function destroy(JadwalDokter $jadwal)
    {
        $this->authorize($jadwal);
        $jadwal->delete();

        return back()->with('success', 'Jadwal berhasil dihapus.');
    }

    public function toggle(JadwalDokter $jadwal)
    {
        $this->authorize($jadwal);
        $jadwal->update(['is_active' => ! $jadwal->is_active]);

        return back()->with('success', 'Status jadwal diperbarui.');
    }

    private function authorize(JadwalDokter $jadwal): void
    {
        if ($jadwal->dokter_id !== Auth::user()->dokter->id) {
            abort(403);
        }
    }
}
