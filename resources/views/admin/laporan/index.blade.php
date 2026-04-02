@extends('layouts.app')
@section('title', 'Laporan')
@section('page-title', 'Laporan')

@section('content')
{{-- Filter --}}
<div class="card mb-4">
    <div class="card-body p-4">
        <form class="row g-3 align-items-end" method="GET">
            <div class="col-auto">
                <label class="form-label mb-1" style="font-size:.82rem;font-weight:500">Bulan</label>
                <select name="bulan" class="form-select form-select-sm">
                    @for($m = 1; $m <= 12; $m++)
                        <option value="{{ $m }}" @selected($m === $bulan)>
                            {{ \Carbon\Carbon::create()->month($m)->isoFormat('MMMM') }}
                        </option>
                    @endfor
                </select>
            </div>
            <div class="col-auto">
                <label class="form-label mb-1" style="font-size:.82rem;font-weight:500">Tahun</label>
                <select name="tahun" class="form-select form-select-sm">
                    @for($y = now()->year; $y >= now()->year - 3; $y--)
                        <option value="{{ $y }}" @selected($y === $tahun)>{{ $y }}</option>
                    @endfor
                </select>
            </div>
            <div class="col-auto">
                <button type="submit" name="tampil" value="1" class="btn btn-sm btn-primary">Tampilkan</button>
            </div>
            <div class="col-auto ms-auto">
                @if($tampil)
                <a href="{{ route('admin.laporan.pdf', ['bulan' => $bulan, 'tahun' => $tahun]) }}"
                   class="btn btn-sm btn-outline-danger">
                    <i class="bi bi-file-earmark-pdf me-1"></i>Export PDF
                </a>
                @else
                <span class="btn btn-sm btn-outline-secondary disabled" style="pointer-events:none;opacity:.65" title="Muat laporan terlebih dahulu">
                    <i class="bi bi-file-earmark-pdf me-1"></i>Export PDF
                </span>
                @endif
            </div>
        </form>
    </div>
</div>

@if(!$tampil)
<div class="alert alert-info border-0 shadow-sm mb-4" style="font-size:.9rem">
    Pilih bulan dan tahun, lalu klik <strong>Tampilkan</strong> untuk memuat ringkasan dan detail appointment.
</div>
@endif

@if($tampil)
{{-- Statistik --}}
<div class="row g-3 mb-4">
    @php
        $cards = [
            ['label' => 'Total Appointment', 'value' => $statistik['total'],        'color' => 'blue',   'icon' => 'calendar2-check'],
            ['label' => 'Menunggu',           'value' => $statistik['menunggu'],     'color' => 'orange', 'icon' => 'hourglass-split'],
            ['label' => 'Dikonfirmasi',        'value' => $statistik['dikonfirmasi'],'color' => 'purple', 'icon' => 'check2-circle'],
            ['label' => 'Selesai',             'value' => $statistik['selesai'],     'color' => 'green',  'icon' => 'check-circle'],
            ['label' => 'Dibatalkan',          'value' => $statistik['dibatalkan'],  'color' => 'red',    'icon' => 'x-circle'],
        ];
    @endphp
    @foreach($cards as $c)
    <div class="col-6 col-xl">
        <div class="stat-card">
            <div class="stat-icon" style="background:{{ match($c['color']) { 'blue'=>'#dbeafe','green'=>'#dcfce7','orange'=>'#ffedd5','purple'=>'#f3e8ff','red'=>'#fee2e2' } }};color:{{ match($c['color']) { 'blue'=>'#2563eb','green'=>'#16a34a','orange'=>'#ea580c','purple'=>'#9333ea','red'=>'#dc2626' } }}">
                <i class="bi bi-{{ $c['icon'] }}"></i>
            </div>
            <div>
                <div class="stat-value">{{ $c['value'] }}</div>
                <div class="stat-label">{{ $c['label'] }}</div>
            </div>
        </div>
    </div>
    @endforeach
</div>

{{-- Per Dokter --}}
<div class="row g-4 mb-4">
    <div class="col-lg-5">
        <div class="card h-100">
            <div class="card-header px-4 py-3">
                <span class="fw-600" style="font-weight:600;font-size:.9rem">Statistik per Dokter</span>
            </div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th class="ps-4">Dokter</th>
                            <th class="text-center">Total</th>
                            <th class="pe-4 text-center">Selesai</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($perDokter as $d)
                        <tr>
                            <td class="ps-4">
                                <div style="font-size:.85rem;font-weight:500">{{ $d->user->name }}</div>
                                <small class="text-muted">{{ $d->spesialisasi }}</small>
                            </td>
                            <td class="text-center">{{ $d->jumlah_appointment }}</td>
                            <td class="pe-4 text-center">
                                <span class="badge bg-success-subtle text-success">{{ $d->jumlah_selesai }}</span>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="3" class="text-center text-muted py-4">Tidak ada data</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Detail Appointments --}}
    <div class="col-lg-7">
        <div class="card h-100">
            <div class="card-header px-4 py-3">
                <span class="fw-600" style="font-weight:600;font-size:.9rem">
                    Detail Appointment — {{ \Carbon\Carbon::create()->month($bulan)->isoFormat('MMMM') }} {{ $tahun }}
                </span>
            </div>
            <div class="card-body p-0" style="max-height:400px;overflow-y:auto">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th class="ps-4">Pasien</th>
                            <th>Dokter</th>
                            <th>Tanggal</th>
                            <th class="pe-4">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($appointments as $apt)
                        <tr>
                            <td class="ps-4" style="font-size:.83rem">{{ $apt->pasien->user->name }}</td>
                            <td style="font-size:.83rem">{{ $apt->dokter->user->name }}</td>
                            <td style="font-size:.83rem">{{ $apt->tanggal_appointment->isoFormat('D MMM') }}</td>
                            <td class="pe-4">
                                <span class="badge bg-{{ $apt->status_badge }}-subtle text-{{ $apt->status_badge }} rounded-pill" style="font-size:.7rem">
                                    {{ $apt->status_label }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="text-center text-muted py-4">Tidak ada data</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endif
@endsection
