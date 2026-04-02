<?php

namespace App\Http\Controllers\Dokter;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AppointmentController extends Controller
{
    private function dokter()
    {
        return Auth::user()->dokter;
    }

    public function index(Request $request)
    {
        $dokter = $this->dokter();

        $appointments = Appointment::with(['pasien.user', 'jadwal', 'rekamMedis'])
            ->where('dokter_id', $dokter->id)
            ->when($request->status, fn ($q) => $q->where('status', $request->status))
            ->when($request->tanggal, fn ($q) => $q->whereDate('tanggal_appointment', $request->tanggal))
            ->latest('tanggal_appointment')
            ->paginate(15);

        return view('dokter.appointments.index', compact('appointments'));
    }

    public function show(Appointment $appointment)
    {
        $this->authorizeAppointment($appointment);

        $appointment->load(['pasien.user', 'jadwal', 'rekamMedis']);

        return view('dokter.appointments.show', compact('appointment'));
    }

    public function updateStatus(Request $request, Appointment $appointment)
    {
        $this->authorizeAppointment($appointment);

        $request->validate([
            'status' => 'required|in:dikonfirmasi,selesai,dibatalkan',
        ]);

        $appointment->update(['status' => $request->status]);

        return back()->with('success', 'Status appointment diperbarui.');
    }

    private function authorizeAppointment(Appointment $appointment): void
    {
        if ($appointment->dokter_id !== $this->dokter()->id) {
            abort(403);
        }
    }
}
