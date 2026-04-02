<?php

namespace App\Http\Controllers\Pasien;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $pasien = Auth::user()->pasien;

        $stats = [
            'total_appointment'    => Appointment::where('pasien_id', $pasien->id)->count(),
            'appointment_aktif'    => Appointment::where('pasien_id', $pasien->id)
                                        ->whereIn('status', ['menunggu', 'dikonfirmasi'])
                                        ->count(),
            'appointment_selesai'  => Appointment::where('pasien_id', $pasien->id)
                                        ->where('status', 'selesai')
                                        ->count(),
        ];

        $appointmentsAktif = Appointment::with(['dokter.user', 'jadwal'])
            ->where('pasien_id', $pasien->id)
            ->whereIn('status', ['menunggu', 'dikonfirmasi'])
            ->orderBy('tanggal_appointment')
            ->take(5)
            ->get();

        $riwayat = Appointment::with(['dokter.user', 'rekamMedis'])
            ->where('pasien_id', $pasien->id)
            ->whereIn('status', ['selesai', 'dibatalkan'])
            ->latest('tanggal_appointment')
            ->take(5)
            ->get();

        return view('pasien.dashboard', compact('stats', 'appointmentsAktif', 'riwayat', 'pasien'));
    }
}
