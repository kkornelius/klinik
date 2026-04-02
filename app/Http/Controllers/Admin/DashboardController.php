<?php
// ─── Admin/DashboardController.php ─────────────────────────────────────────
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Dokter;
use App\Models\Pasien;

class DashboardController extends Controller
{
    public function index()
    {
        // Appointment hari ini + mendatang (belum selesai) — bukan hanya tanggal hari ini
        $appointmentMendatangQuery = fn () => Appointment::query()
            ->whereDate('tanggal_appointment', '>=', today())
            ->where('status', '!=', 'selesai');

        $stats = [
            'total_dokter'               => Dokter::where('is_active', true)->count(),
            'total_pasien'               => Pasien::count(),
            'appointment_hari_ini'       => $appointmentMendatangQuery()->count(),
            'appointment_bulan_ini'      => Appointment::whereMonth('tanggal_appointment', now()->month)->count(),
            'menunggu_konfirmasi'        => Appointment::where('status', 'menunggu')->count(),
            'selesai_bulan_ini'          => Appointment::where('status', 'selesai')
                                            ->whereMonth('tanggal_appointment', now()->month)->count(),
        ];

        // Data chart 7 hari terakhir
        $chartData = collect(range(6, 0))->map(function ($ago) {
            $date = now()->subDays($ago);
            return [
                'label' => $date->isoFormat('D MMM'),
                'count' => Appointment::whereDate('tanggal_appointment', $date)->count(),
            ];
        });

        $appointmentsMendatang = Appointment::with(['pasien.user', 'dokter.user', 'jadwal'])
            ->whereDate('tanggal_appointment', '>=', today())
            ->where('status', '!=', 'selesai')
            ->orderBy('tanggal_appointment')
            ->orderBy('status')
            ->take(25)
            ->get();

        return view('admin.dashboard', compact('stats', 'chartData', 'appointmentsMendatang'));
    }
}
