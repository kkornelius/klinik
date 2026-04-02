<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function index(Request $request)
    {
        $appointments = Appointment::with(['pasien.user', 'dokter.user', 'jadwal'])
            ->when($request->status, fn ($q) => $q->where('status', $request->status))
            ->when($request->tanggal, fn ($q) => $q->whereDate('tanggal_appointment', $request->tanggal))
            ->when($request->search, fn ($q) => $q->whereHas('pasien.user', fn ($q) =>
                $q->where('name', 'like', "%{$request->search}%")
            ))
            ->latest()
            ->paginate(15);

        return view('admin.appointments.index', compact('appointments'));
    }

    public function updateStatus(Request $request, Appointment $appointment)
    {
        $request->validate([
            'status' => 'required|in:menunggu,dikonfirmasi,selesai,dibatalkan',
        ]);

        $appointment->update(['status' => $request->status]);

        // Auto-generate nomor antrian saat dikonfirmasi
        if ($request->status === 'dikonfirmasi' && ! $appointment->no_antrian) {
            $count = Appointment::where('dokter_id', $appointment->dokter_id)
                ->whereDate('tanggal_appointment', $appointment->tanggal_appointment)
                ->where('status', 'dikonfirmasi')
                ->count();
            $appointment->update(['no_antrian' => str_pad($count, 3, '0', STR_PAD_LEFT)]);
        }

        return back()->with('success', 'Status appointment berhasil diperbarui.');
    }
}
