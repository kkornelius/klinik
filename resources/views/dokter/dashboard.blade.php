@extends('layouts.app')
@section('title', 'Dashboard Dokter')
@section('page-title', 'Dashboard')

@push('styles')
<style>
.stat-icon.blue   { background:#dbeafe; color:#2563eb; }
.stat-icon.green  { background:#dcfce7; color:#16a34a; }
.stat-icon.orange { background:#ffedd5; color:#ea580c; }
.stat-icon.purple { background:#f3e8ff; color:#9333ea; }
.queue-number { width:40px;height:40px;border-radius:10px;background:#f1f5f9;display:grid;place-items:center;font-weight:700;font-size:.9rem;color:#475569;flex-shrink:0; }
</style>
@endpush

@section('content')
<div class="d-flex align-items-center gap-3 mb-4 p-3 rounded-3" style="background:#f8fafc;border:1px solid #e2e8f0">
    <div style="width:52px;height:52px;background:#2563eb;border-radius:12px;display:grid;place-items:center;color:#fff;font-size:1.4rem;flex-shrink:0">
        <i class="bi bi-person-circle"></i>
    </div>
    <div>
        <div class="fw-700" style="font-weight:700;font-size:1rem">{{ auth()->user()->name }}</div>
        <div class="text-muted" style="font-size:.83rem">{{ $dokter->spesialisasi }} &mdash; No. STR: {{ $dokter->no_str }}</div>
    </div>
    <div class="ms-auto text-muted" style="font-size:.83rem">
        {{ now()->isoFormat('dddd, D MMMM YYYY') }}
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon orange"><i class="bi bi-calendar-day"></i></div>
            <div>
                <div class="stat-value">{{ $stats['appointment_hari_ini'] }}</div>
                <div class="stat-label">Antrian Hari Ini</div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon blue"><i class="bi bi-hourglass-split"></i></div>
            <div>
                <div class="stat-value">{{ $stats['menunggu'] }}</div>
                <div class="stat-label">Menunggu Konfirmasi</div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon green"><i class="bi bi-check-circle"></i></div>
            <div>
                <div class="stat-value">{{ $stats['selesai_bulan_ini'] }}</div>
                <div class="stat-label">Selesai Bulan Ini</div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon purple"><i class="bi bi-people"></i></div>
            <div>
                <div class="stat-value">{{ $stats['total_pasien'] }}</div>
                <div class="stat-label">Total Pasien Ditangani</div>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header px-4 py-3 d-flex align-items-center justify-content-between">
        <span class="fw-600" style="font-weight:600;font-size:.9rem">Antrian Hari Ini</span>
        <a href="{{ route('dokter.appointments.index') }}" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
    </div>
    <div class="card-body p-0">
        @forelse($appointmentsHariIni as $apt)
        <div class="d-flex align-items-center gap-3 px-4 py-3 border-bottom">
            <div class="queue-number">
                {{ $apt->no_antrian ?? '—' }}
            </div>
            <div class="flex-grow-1">
                <div class="fw-500" style="font-weight:500;font-size:.9rem">{{ $apt->pasien->user->name }}</div>
                <div class="text-muted" style="font-size:.78rem">
                    <i class="bi bi-clock me-1"></i>{{ substr($apt->jadwal->jam_mulai, 0, 5) }} –
                    {{ substr($apt->jadwal->jam_selesai, 0, 5) }}
                    &nbsp;·&nbsp; Keluhan: {{ Str::limit($apt->keluhan, 50) }}
                </div>
            </div>
            <div class="d-flex align-items-center gap-2">
                <span class="badge bg-{{ $apt->status_badge }}-subtle text-{{ $apt->status_badge }} rounded-pill" style="font-size:.72rem">
                    {{ $apt->status_label }}
                </span>
                <a href="{{ route('dokter.appointments.show', $apt) }}" class="btn btn-sm btn-primary">
                    Tangani <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
        @empty
        <div class="text-center text-muted py-5">
            <i class="bi bi-calendar-check fs-2 d-block mb-2 text-success"></i>
            Tidak ada antrian hari ini
        </div>
        @endforelse
    </div>
</div>
@endsection
