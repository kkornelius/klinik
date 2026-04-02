<?php

namespace App\Http\Controllers\Pasien;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Dokter;
use App\Models\JadwalDokter;
use App\Models\User;
use App\Notifications\AdminNewAppointmentNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;


class AppointmentController extends Controller
{
    private function pasien()
    {
        return Auth::user()->pasien;
    }

    public function index(Request $request)
    {
        $pasien = $this->pasien();

        $appointments = Appointment::with(['dokter.user', 'jadwal', 'rekamMedis'])
            ->where('pasien_id', $pasien->id)
            ->when($request->status, fn ($q) => $q->where('status', $request->status))
            ->latest('tanggal_appointment')
            ->paginate(10);

        return view('pasien.appointments.index', compact('appointments'));
    }

    public function create()
    {
        $dokters = Dokter::with(['user', 'jadwals' => fn ($q) => $q->where('is_active', true)])
            ->where('is_active', true)
            ->get();

        return view('pasien.appointments.create', compact('dokters'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'dokter_id'           => 'required|exists:dokters,id',
            'jadwal_id'           => 'required|exists:jadwal_dokters,id',
            'tanggal_appointment' => 'required|date|after_or_equal:today',
            'keluhan'             => 'required|string|max:500',
        ]);

        $pasien = $this->pasien();
        $jadwal = JadwalDokter::findOrFail($request->jadwal_id);

        // Cek kuota
        if ($jadwal->sisaKuota($request->tanggal_appointment) <= 0) {
            return back()->with('error', 'Kuota pada jadwal ini sudah penuh.')
                         ->withInput();
        }

        // Cek duplikasi booking pasien di hari yang sama
        $existing = Appointment::where('pasien_id', $pasien->id)
            ->whereDate('tanggal_appointment', $request->tanggal_appointment)
            ->whereIn('status', ['menunggu', 'dikonfirmasi'])
            ->exists();

        if ($existing) {
            return back()->with('error', 'Anda sudah memiliki appointment pada tanggal ini.')
                         ->withInput();
        }

        $appointment = Appointment::create([
            'pasien_id'           => $pasien->id,
            'dokter_id'           => $request->dokter_id,
            'jadwal_id'           => $request->jadwal_id,
            'tanggal_appointment' => $request->tanggal_appointment,
            'keluhan'             => $request->keluhan,
            'status'              => 'menunggu',
        ]);

        $adminUsers = User::query()
            ->where('role', 'admin')
            ->where('is_active', true)
            ->get(['id', 'name', 'email']);

        if ($adminUsers->isNotEmpty()) {
            $payload = [
                'appointment_id' => $appointment->id,
                'pasien_nama' => $pasien->user->name ?? '-',
                'dokter_nama' => $appointment->dokter->user->name ?? '-',
                'tanggal' => $appointment->tanggal_appointment->format('d M Y'),
            ];

            // Kirim setelah response dikembalikan agar UX tetap cepat tanpa worker queue.
            dispatch(function () use ($adminUsers, $payload) {
                Notification::sendNow($adminUsers, new AdminNewAppointmentNotification($payload));
            })->afterResponse();
        }

        return redirect()->route('pasien.appointments.index')
            ->with('success', 'Appointment berhasil dibuat. Menunggu konfirmasi dari klinik.');
    }

    public function show(Appointment $appointment)
    {
        $this->authorizeAppointment($appointment);

        $appointment->load(['dokter.user', 'jadwal', 'rekamMedis.dokter.user']);

        return view('pasien.appointments.show', compact('appointment'));
    }

    public function cancel(Appointment $appointment)
    {
        $this->authorizeAppointment($appointment);

        if (! $appointment->canBeCancelled()) {
            return back()->with('error', 'Appointment tidak dapat dibatalkan pada status ini.');
        }

        $appointment->update(['status' => 'dibatalkan']);

        return back()->with('success', 'Appointment berhasil dibatalkan.');
    }

    /** AJAX: kembalikan jadwal aktif milik dokter beserta sisa kuota */
    public function getJadwal(Dokter $dokter, Request $request)
    {
        $tanggal  = $request->input('tanggal');
        $hariToIndex = [
            'Minggu' => 0,
            'Senin'  => 1,
            'Selasa' => 2,
            'Rabu'   => 3,
            'Kamis'  => 4,
            'Jumat'  => 5,
            'Sabtu'  => 6,
        ];

        $jadwals = JadwalDokter::where('dokter_id', $dokter->id)
            ->where('is_active', true)
            ->get()
            ->map(function ($j) use ($tanggal, $hariToIndex) {
                $computedDate = $tanggal;

                if (! $computedDate) {
                    $targetDow = $hariToIndex[$j->hari] ?? null;
                    $d = now()->startOfDay();

                    // Cari tanggal terdekat (>= hari ini) sesuai hari jadwal.
                    for ($i = 0; $i < 14; $i++) {
                        if ($targetDow !== null && (int) $d->dayOfWeek === $targetDow) {
                            break;
                        }
                        $d->addDay();
                    }

                    $computedDate = $d->toDateString();
                }

                $sisa = $j->sisaKuota($computedDate);

                return [
                    'id'                   => $j->id,
                    'hari'                 => $j->hari,
                    'tanggal_appointment' => $computedDate,
                    'jam_mulai'           => $j->jam_mulai,
                    'jam_selesai'         => $j->jam_selesai,
                    'kuota'               => $j->kuota,
                    'sisa'                => $sisa,
                ];
            });

        // Kalau tanggal tidak diberikan, tampilkan hanya jadwal yang masih tersedia.
        if (! $tanggal) {
            $jadwals = $jadwals->filter(fn ($j) => ($j['sisa'] ?? 0) > 0)->values();
        }

        return response()->json($jadwals);
    }

    private function authorizeAppointment(Appointment $appointment): void
    {
        if ($appointment->pasien_id !== $this->pasien()->id) {
            abort(403);
        }
    }
}
