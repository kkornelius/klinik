@extends('layouts.app')
@section('title', 'Dashboard Admin')
@section('page-title', 'Dashboard')

@push('styles')
<style>
.stat-icon.blue   { background:#dbeafe; color:#2563eb; }
.stat-icon.green  { background:#dcfce7; color:#16a34a; }
.stat-icon.orange { background:#ffedd5; color:#ea580c; }
.stat-icon.purple { background:#f3e8ff; color:#9333ea; }
</style>
@endpush

@section('content')
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon blue"><i class="bi bi-person-badge"></i></div>
            <div>
                <div class="stat-value">{{ $stats['total_dokter'] }}</div>
                <div class="stat-label">Dokter Aktif</div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon green"><i class="bi bi-people"></i></div>
            <div>
                <div class="stat-value">{{ $stats['total_pasien'] }}</div>
                <div class="stat-label">Total Pasien</div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon orange"><i class="bi bi-calendar-day"></i></div>
            <div>
                <div class="stat-value">{{ $stats['appointment_hari_ini'] }}</div>
                <div class="stat-label">Appointment Mendatang (Belum Selesai)</div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon purple"><i class="bi bi-hourglass-split"></i></div>
            <div>
                <div class="stat-value">{{ $stats['menunggu_konfirmasi'] }}</div>
                <div class="stat-label">Menunggu Konfirmasi</div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    {{-- Chart --}}
    <div class="col-lg-7">
        <div class="card h-100">
            <div class="card-header d-flex align-items-center justify-content-between py-3 px-4">
                <span class="fw-600" style="font-weight:600;font-size:.9rem">Appointment 7 Hari Terakhir</span>
            </div>
            <div class="card-body p-4">
                <canvas id="chartAppointment" height="120"></canvas>
            </div>
        </div>
    </div>

    {{-- Appointment hari ini + mendatang (belum selesai), max 25 --}}
    <div class="col-lg-5">
        <div class="card h-100">
            <div class="card-header d-flex align-items-center justify-content-between py-3 px-4">
                <span class="fw-600" style="font-weight:600;font-size:.9rem">Appointment Mendatang</span>
                <span class="badge bg-primary rounded-pill">{{ $appointmentsMendatang->count() }}</span>
            </div>
            <div class="card-body p-0">
                @forelse($appointmentsMendatang as $apt)
                    <div class="d-flex align-items-center gap-3 px-4 py-3 border-bottom">
                        <div class="flex-shrink-0">
                            <div style="width:36px;height:36px;background:#f1f5f9;border-radius:50%;display:grid;place-items:center;font-weight:700;font-size:.8rem;color:#475569">
                                {{ strtoupper(substr($apt->pasien->user->name, 0, 1)) }}
                            </div>
                        </div>
                        <div class="flex-grow-1 overflow-hidden min-w-0">
                            <div class="fw-500 text-truncate" style="font-size:.85rem;font-weight:500">{{ $apt->pasien->user->name }}</div>
                            <div class="text-muted text-truncate" style="font-size:.75rem">{{ $apt->dokter->user->name }}</div>
                            <div class="text-muted" style="font-size:.72rem">
                                {{ $apt->tanggal_appointment->isoFormat('D MMM YYYY') }}
                                @if($apt->tanggal_appointment->isToday())
                                    <span class="badge bg-primary-subtle text-primary ms-1" style="font-size:.65rem">Hari ini</span>
                                @endif
                            </div>
                        </div>
                        <span class="badge bg-{{ $apt->status_badge }} rounded-pill flex-shrink-0" style="font-size:.7rem">{{ $apt->status_label }}</span>
                    </div>
                @empty
                    <div class="text-center text-muted py-5" style="font-size:.85rem">
                        <i class="bi bi-calendar-x fs-3 d-block mb-2"></i>
                        Tidak ada appointment mendatang (belum selesai)
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
<script>
const ctx = document.getElementById('chartAppointment').getContext('2d');
new Chart(ctx, {
    type: 'bar',
    data: {
        labels: {!! $chartData->pluck('label')->toJson() !!},
        datasets: [{
            label: 'Appointment',
            data: {!! $chartData->pluck('count')->toJson() !!},
            backgroundColor: 'rgba(37,99,235,.15)',
            borderColor: '#2563eb',
            borderWidth: 2,
            borderRadius: 6,
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
            y: { beginAtZero: true, ticks: { stepSize: 1 }, grid: { color: '#f1f5f9' } },
            x: { grid: { display: false } }
        }
    }
});
</script>
@endpush
