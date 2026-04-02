<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Dokter;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $tampil = $request->boolean('tampil');

        $bulan = (int) ($request->bulan ?? now()->month);
        $tahun = (int) ($request->tahun ?? now()->year);

        if (! $tampil) {
            return view('admin.laporan.index', [
                'tampil'       => false,
                'appointments' => collect(),
                'statistik'    => [
                    'total'        => 0,
                    'menunggu'     => 0,
                    'dikonfirmasi' => 0,
                    'selesai'      => 0,
                    'dibatalkan'   => 0,
                ],
                'perDokter' => collect(),
                'bulan'     => $bulan,
                'tahun'     => $tahun,
            ]);
        }

        $appointments = Appointment::with(['pasien.user', 'dokter.user', 'jadwal'])
            ->whereMonth('tanggal_appointment', $bulan)
            ->whereYear('tanggal_appointment', $tahun)
            ->latest()
            ->get();

        $statistik = [
            'total'        => $appointments->count(),
            'menunggu'     => $appointments->where('status', 'menunggu')->count(),
            'dikonfirmasi' => $appointments->where('status', 'dikonfirmasi')->count(),
            'selesai'      => $appointments->where('status', 'selesai')->count(),
            'dibatalkan'   => $appointments->where('status', 'dibatalkan')->count(),
        ];

        // Filter setelah query: SQLite tidak mengizinkan HAVING pada alias withCount tanpa GROUP BY
        $perDokter = Dokter::with('user')
            ->withCount([
                'appointments as jumlah_appointment' => fn ($q) =>
                    $q->whereMonth('tanggal_appointment', $bulan)
                      ->whereYear('tanggal_appointment', $tahun),
                'appointments as jumlah_selesai' => fn ($q) =>
                    $q->where('status', 'selesai')
                      ->whereMonth('tanggal_appointment', $bulan)
                      ->whereYear('tanggal_appointment', $tahun),
            ])
            ->get()
            ->filter(fn (Dokter $d) => $d->jumlah_appointment > 0)
            ->values();

        return view('admin.laporan.index', compact(
            'tampil', 'appointments', 'statistik', 'perDokter', 'bulan', 'tahun'
        ));
    }

    public function exportPdf(Request $request)
    {
        $bulan = (int) ($request->bulan ?? now()->month);
        $tahun = (int) ($request->tahun ?? now()->year);

        $appointments = Appointment::with(['pasien.user', 'dokter.user'])
            ->whereMonth('tanggal_appointment', $bulan)
            ->whereYear('tanggal_appointment', $tahun)
            ->orderBy('tanggal_appointment')
            ->get();

        $statistik = [
            'total'     => $appointments->count(),
            'selesai'   => $appointments->where('status', 'selesai')->count(),
            'dibatalkan'=> $appointments->where('status', 'dibatalkan')->count(),
        ];

        $namaBulan = \Carbon\Carbon::create()->month($bulan)->isoFormat('MMMM');

        $pdf = Pdf::loadView('pdf.laporan', compact('appointments', 'statistik', 'bulan', 'tahun', 'namaBulan'))
            ->setPaper('a4', 'landscape');

        return $pdf->download("laporan-klinik-{$namaBulan}-{$tahun}.pdf");
    }
}
