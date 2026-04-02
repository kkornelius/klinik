{{-- resources/views/pasien/rekam-medis/show.blade.php --}}
@extends('layouts.app')
@section('title', 'Detail Rekam Medis')
@section('page-title', 'Rekam Medis')

@push('styles')
<style>
    .info-label { font-size:.74rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.06em;margin-bottom:2px; }
    .info-value  { font-size:.92rem;color:#1e293b;font-weight:500;line-height:1.4; }
    .soap-badge  { display:inline-flex;align-items:center;justify-content:center;width:28px;height:28px;border-radius:8px;font-weight:700;font-size:.85rem;flex-shrink:0; }
    .soap-s{background:#dbeafe;color:#2563eb} .soap-o{background:#dcfce7;color:#16a34a}
    .soap-a{background:#ffedd5;color:#ea580c} .soap-p{background:#f3e8ff;color:#9333ea}
</style>
@endpush

@section('content')
<div class="mb-3">
    <a href="{{ route('pasien.rekam-medis.index') }}" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i>Kembali
    </a>
</div>

<div class="row g-4">
    <div class="col-lg-4">
        <div class="card h-100">
            <div class="card-header px-4 py-3">
                <span class="fw-600" style="font-weight:600;font-size:.9rem">Detail Appointment</span>
            </div>
            <div class="card-body px-4 py-3">
                <div class="mb-3">
                    <div class="info-label">Dokter</div>
                    <div class="info-value">{{ $rekamMedis->dokter->user->name }}</div>
                    <div class="text-muted" style="font-size:.78rem">{{ $rekamMedis->dokter->spesialisasi ?? '' }}</div>
                </div>

                <div class="mb-3">
                    <div class="info-label">Tanggal</div>
                    <div class="info-value">{{ $rekamMedis->appointment->tanggal_appointment->isoFormat('dddd, D MMMM YYYY') }}</div>
                </div>

                <div>
                    <div class="info-label">Jam</div>
                    <div class="info-value">
                        {{ substr($rekamMedis->appointment->jadwal->jam_mulai,0,5) }} – {{ substr($rekamMedis->appointment->jadwal->jam_selesai,0,5) }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="card">
            <div class="card-header px-4 py-3 d-flex align-items-center justify-content-between">
                <span class="fw-600" style="font-weight:600;font-size:.9rem">Rekam Medis SOAP</span>
                <span class="badge bg-success-subtle text-success rounded-pill px-3 py-2">Sudah diisi</span>
            </div>
            <div class="card-body p-4">
                @foreach([
                    ['key'=>'S','label'=>'Subjective','class'=>'soap-s','value'=>$rekamMedis->subjective],
                    ['key'=>'O','label'=>'Objective', 'class'=>'soap-o','value'=>$rekamMedis->objective],
                    ['key'=>'A','label'=>'Assessment','class'=>'soap-a','value'=>$rekamMedis->assessment],
                    ['key'=>'P','label'=>'Plan',      'class'=>'soap-p','value'=>$rekamMedis->plan],
                ] as $row)
                    <div class="d-flex gap-3 mb-4">
                        <div class="soap-badge {{ $row['class'] }}">{{ $row['key'] }}</div>
                        <div class="flex-grow-1">
                            <div class="fw-600 mb-1" style="font-size:.82rem;font-weight:600">{{ $row['label'] }}</div>
                            <div style="font-size:.9rem;line-height:1.6;color:#374151;white-space:pre-line">{{ $row['value'] }}</div>
                        </div>
                    </div>
                @endforeach

                @if($rekamMedis->diagnosa_kode)
                    <div class="d-flex gap-2 align-items-center mb-3 p-3 rounded-2" style="background:#f8fafc;border:1px solid #e2e8f0">
                        <i class="bi bi-tag text-muted"></i>
                        <span class="text-muted" style="font-size:.82rem">Kode ICD-10:</span>
                        <code>{{ $rekamMedis->diagnosa_kode }}</code>
                    </div>
                @endif

                @if($rekamMedis->resep)
                    <div class="p-3 rounded-2" style="background:#f0fdf4;border:1px solid #bbf7d0">
                        <div class="fw-600 mb-1" style="font-size:.82rem;font-weight:600;color:#16a34a">
                            <i class="bi bi-capsule me-1"></i>Resep Obat
                        </div>
                        <div style="font-size:.9rem;white-space:pre-line;color:#374151">{{ $rekamMedis->resep }}</div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

