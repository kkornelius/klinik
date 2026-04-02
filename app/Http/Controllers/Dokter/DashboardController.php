<?php

namespace App\Http\Controllers\Dokter;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $dokter = Auth::user()->dokter;

        $stats = [
            'appointment_hari_ini'  => Appointment::where('dokter_id', $dokter->id)
                                        ->whereDate('tanggal_appointment', today())
                                        ->whereIn('status', ['menunggu', 'dikonfirmasi'])
                                        ->count(),
            'selesai_bulan_ini'     => Appointment::where('dokter_id', $dokter->id)
                                        ->where('status', 'selesai')
                                        ->whereMonth('tanggal_appointment', now()->month)
                                        ->count(),
            'total_pasien'          => Appointment::where('dokter_id', $dokter->id)
                                        ->distinct('pasien_id')
                                        ->count('pasien_id'),
            'menunggu'              => Appointment::where('dokter_id', $dokter->id)
                                        ->where('status', 'menunggu')
                                        ->count(),
        ];

        $appointmentsHariIni = Appointment::with(['pasien.user', 'jadwal', 'rekamMedis'])
            ->where('dokter_id', $dokter->id)
            ->whereDate('tanggal_appointment', today())
            ->orderByRaw(
                "CASE status "
                . "WHEN 'dikonfirmasi' THEN 1 WHEN 'menunggu' THEN 2 "
                . "WHEN 'selesai' THEN 3 WHEN 'dibatalkan' THEN 4 ELSE 5 END"
            )
            ->get();

        return view('dokter.dashboard', compact('stats', 'appointmentsHariIni', 'dokter'));
    }
}
