@extends('layouts.app')
@section('title', 'Dashboard Pasien')
@section('page-title', 'Dashboard')

@push('styles')
<style>
.stat-icon.blue   { background:#dbeafe; color:#2563eb; }
.stat-icon.green  { background:#dcfce7; color:#16a34a; }
.stat-icon.orange { background:#ffedd5; color:#ea580c; }
</style>
@endpush

@section('content')
{{-- Greeting --}}
<div class="p-4 mb-4 rounded-3" style="background:linear-gradient(135deg,#1d4ed8,#2563eb);color:#fff">
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
        <div>
            <h5 class="mb-1 fw-700" style="font-weight:700">Halo, {{ auth()->user()->name }}! 👋</h5>
            <p class="mb-0" style="opacity:.85;font-size:.87rem">
                No. Rekam Medis: <strong>{{ $pasien->no_rm }}</strong>
            </p>
        </div>
        <a href="{{ route('pasien.appointments.create') }}" class="btn btn-light btn-sm fw-600" style="font-weight:600">
            <i class="bi bi-plus-lg me-1"></i>Buat Appointment
        </a>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-sm-4">
        <div class="stat-card">
            <div class="stat-icon blue"><i class="bi bi-calendar2-check"></i></div>
            <div>
                <div class="stat-value">{{ $stats['total_appointment'] }}</div>
                <div class="stat-label">Total Appointment</div>
            </div>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="stat-card">
            <div class="stat-icon orange"><i class="bi bi-hourglass-split"></i></div>
            <div>
                <div class="stat-value">{{ $stats['appointment_aktif'] }}</div>
                <div class="stat-label">Appointment Aktif</div>
            </div>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="stat-card">
            <div class="stat-icon green"><i class="bi bi-check-circle"></i></div>
            <div>
                <div class="stat-value">{{ $stats['appointment_selesai'] }}</div>
                <div class="stat-label">Pemeriksaan Selesai</div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    {{-- Appointment aktif --}}
    <div class="col-lg-6">
        <div class="card h-100">
            <div class="card-header px-4 py-3 d-flex justify-content-between align-items-center">
                <span class="fw-600" style="font-weight:600;font-size:.9rem">Appointment Mendatang</span>
                <a href="{{ route('pasien.appointments.index') }}" class="btn btn-sm btn-outline-primary">Semua</a>
            </div>
            <div class="card-body p-0">
                @forelse($appointmentsAktif as $apt)
                <a href="{{ route('pasien.appointments.show', $apt) }}" class="d-flex align-items-center gap-3 px-4 py-3 border-bottom text-decoration-none text-dark">
                    <div style="width:44px;text-align:center;flex-shrink:0">
                        <div style="font-size:1.3rem;font-weight:700;color:#2563eb;line-height:1">{{ $apt->tanggal_appointment->format('d') }}</div>
                        <div style="font-size:.7rem;color:#94a3b8;text-transform:uppercase">{{ $apt->tanggal_appointment->isoFormat('MMM') }}</div>
                    </div>
                    <div class="flex-grow-1">
                        <div class="fw-500" style="font-weight:500;font-size:.88rem">{{ $apt->dokter->user->name }}</div>
                        <div class="text-muted" style="font-size:.76rem">{{ $apt->dokter->spesialisasi }} · {{ substr($apt->jadwal->jam_mulai,0,5) }}</div>
                    </div>
                    <span class="badge bg-{{ $apt->status_badge }}-subtle text-{{ $apt->status_badge }} rounded-pill" style="font-size:.7rem">
                        {{ $apt->status_label }}
                    </span>
                </a>
                @empty
                <div class="text-center text-muted py-4" style="font-size:.85rem">
                    <i class="bi bi-calendar-plus fs-2 d-block mb-2 text-primary opacity-50"></i>
                    Belum ada appointment mendatang.<br>
                    <a href="{{ route('pasien.appointments.create') }}" class="text-primary">Buat appointment sekarang</a>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Riwayat --}}
    <div class="col-lg-6">
        <div class="card h-100">
            <div class="card-header px-4 py-3 d-flex justify-content-between align-items-center">
                <span class="fw-600" style="font-weight:600;font-size:.9rem">Riwayat Pemeriksaan</span>
                <a href="{{ route('pasien.rekam-medis.index') }}" class="btn btn-sm btn-outline-primary">Rekam Medis</a>
            </div>
            <div class="card-body p-0">
                @forelse($riwayat as $apt)
                <div class="d-flex align-items-center gap-3 px-4 py-3 border-bottom">
                    <div style="width:44px;text-align:center;flex-shrink:0">
                        <div style="font-size:1.3rem;font-weight:700;color:#64748b;line-height:1">{{ $apt->tanggal_appointment->format('d') }}</div>
                        <div style="font-size:.7rem;color:#94a3b8;text-transform:uppercase">{{ $apt->tanggal_appointment->isoFormat('MMM') }}</div>
                    </div>
                    <div class="flex-grow-1">
                        <div class="fw-500" style="font-weight:500;font-size:.88rem">{{ $apt->dokter->user->name }}</div>
                        <div class="text-muted" style="font-size:.76rem">
                            @if($apt->rekamMedis)
                                <i class="bi bi-journal-check me-1 text-success"></i>Rekam medis tersedia
                            @else
                                Tidak ada rekam medis
                            @endif
                        </div>
                    </div>
                    <span class="badge bg-{{ $apt->status_badge }}-subtle text-{{ $apt->status_badge }} rounded-pill" style="font-size:.7rem">
                        {{ $apt->status_label }}
                    </span>
                </div>
                @empty
                <div class="text-center text-muted py-4" style="font-size:.85rem">
                    <i class="bi bi-journal-x fs-2 d-block mb-2 opacity-50"></i>
                    Belum ada riwayat pemeriksaan
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
