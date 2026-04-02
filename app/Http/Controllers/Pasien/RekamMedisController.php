<?php

namespace App\Http\Controllers\Pasien;

use App\Http\Controllers\Controller;
use App\Models\RekamMedis;
use Illuminate\Support\Facades\Auth;

class RekamMedisController extends Controller
{
    public function index()
    {
        $pasien    = Auth::user()->pasien;
        $rekamMedis = RekamMedis::with(['dokter.user', 'appointment'])
            ->where('pasien_id', $pasien->id)
            ->latest()
            ->paginate(10);

        return view('pasien.rekam-medis.index', compact('rekamMedis'));
    }

    public function show(RekamMedis $rekamMedis)
    {
        if ($rekamMedis->pasien_id !== Auth::user()->pasien->id) {
            abort(403);
        }

        $rekamMedis->load(['dokter.user', 'appointment.jadwal']);

        return view('pasien.rekam-medis.show', compact('rekamMedis'));
    }
}
