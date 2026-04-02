<?php

namespace App\Http\Controllers\Dokter;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\RekamMedis;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RekamMedisController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'appointment_id' => 'required|exists:appointments,id',
            'subjective'     => 'required|string',
            'objective'      => 'required|string',
            'assessment'     => 'required|string',
            'plan'           => 'required|string',
            'diagnosa_kode'  => 'nullable|string|max:20',
            'resep'          => 'nullable|string',
        ]);

        $appointment = Appointment::findOrFail($request->appointment_id);

        // Guard: hanya dokter yang menangani appointment ini
        if ($appointment->dokter_id !== Auth::user()->dokter->id) {
            abort(403);
        }

        // Guard: jangan buat duplikasi
        if ($appointment->rekamMedis) {
            return back()->with('error', 'Rekam medis untuk appointment ini sudah ada.');
        }

        RekamMedis::create([
            'appointment_id' => $appointment->id,
            'pasien_id'      => $appointment->pasien_id,
            'dokter_id'      => $appointment->dokter_id,
            'subjective'     => $request->subjective,
            'objective'      => $request->objective,
            'assessment'     => $request->assessment,
            'plan'           => $request->plan,
            'diagnosa_kode'  => $request->diagnosa_kode,
            'resep'          => $request->resep,
        ]);

        // Set status appointment ke selesai
        $appointment->update(['status' => 'selesai']);

        return redirect()->route('dokter.appointments.show', $appointment)
            ->with('success', 'Rekam medis berhasil disimpan.');
    }

    public function show(RekamMedis $rekamMedis)
    {
        if ($rekamMedis->dokter_id !== Auth::user()->dokter->id) {
            abort(403);
        }

        $rekamMedis->load(['pasien.user', 'appointment.jadwal']);

        return view('dokter.rekam-medis.show', compact('rekamMedis'));
    }

    public function update(Request $request, RekamMedis $rekamMedis)
    {
        $request->validate([
            'subjective'     => 'required|string',
            'objective'      => 'required|string',
            'assessment'     => 'required|string',
            'plan'           => 'required|string',
            'diagnosa_kode'  => 'nullable|string|max:20',
            'resep'          => 'nullable|string',
        ]);

        if ($rekamMedis->dokter_id !== Auth::user()->dokter->id) {
            abort(403);
        }

        $rekamMedis->update([
            'subjective'     => $request->subjective,
            'objective'      => $request->objective,
            'assessment'     => $request->assessment,
            'plan'           => $request->plan,
            'diagnosa_kode'  => $request->diagnosa_kode,
            'resep'          => $request->resep,
        ]);

        return redirect()
            ->route('dokter.appointments.show', $rekamMedis->appointment)
            ->with('success', 'Rekam medis berhasil diperbarui.');
    }
}
