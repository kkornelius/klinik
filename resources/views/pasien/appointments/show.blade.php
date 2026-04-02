{{-- resources/views/pasien/appointments/show.blade.php --}}
@extends('layouts.app')
@section('title', 'Detail Appointment')
@section('page-title', 'Detail Appointment')

@push('styles')
<style>
.info-label { font-size:.74rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.06em;margin-bottom:2px; }
.info-value  { font-size:.9rem;color:#1e293b;font-weight:500; }
.soap-badge  { display:inline-flex;align-items:center;justify-content:center;width:28px;height:28px;border-radius:8px;font-weight:700;font-size:.85rem;flex-shrink:0; }
.soap-s{background:#dbeafe;color:#2563eb} .soap-o{background:#dcfce7;color:#16a34a}
.soap-a{background:#ffedd5;color:#ea580c} .soap-p{background:#f3e8ff;color:#9333ea}
</style>
@endpush

@section('content')
<div class="row g-4">
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header px-4 py-3">
                <span class="fw-600" style="font-weight:600;font-size:.9rem">Informasi Appointment</span>
            </div>
            <div class="card-body px-4 py-3">
                <div class="row g-3">
                    <div class="col-12">
                        <div class="info-label">Dokter</div>
                        <div class="info-value">{{ $appointment->dokter->user->name }}</div>
                        <div class="text-muted" style="font-size:.78rem">{{ $appointment->dokter->spesialisasi }}</div>
                    </div>
                    <div class="col-12">
                        <div class="info-label">Tanggal</div>
                        <div class="info-value">{{ $appointment->tanggal_appointment->isoFormat('dddd, D MMMM YYYY') }}</div>
                    </div>
                    <div class="col-12">
                        <div class="info-label">Jam Praktik</div>
                        <div class="info-value">{{ substr($appointment->jadwal->jam_mulai,0,5) }} – {{ substr($appointment->jadwal->jam_selesai,0,5) }}</div>
                    </div>
                    @if($appointment->no_antrian)
                    <div class="col-12">
                        <div class="info-label">Nomor Antrian</div>
                        <div style="font-size:2rem;font-weight:800;color:#2563eb;line-height:1">{{ $appointment->no_antrian }}</div>
                    </div>
                    @endif
                    <div class="col-12">
                        <div class="info-label">Status</div>
                        <span class="badge bg-{{ $appointment->status_badge }}-subtle text-{{ $appointment->status_badge }} rounded-pill px-3 py-2 mt-1">
                            {{ $appointment->status_label }}
                        </span>
                    </div>
                    <div class="col-12">
                        <div class="info-label">Keluhan</div>
                        <div class="info-value">{{ $appointment->keluhan }}</div>
                    </div>
                </div>

                @if($appointment->canBeCancelled())
                <div class="mt-4 pt-3" style="border-top:1px solid #f1f5f9">
                    <form action="{{ route('pasien.appointments.cancel', $appointment) }}" method="POST"
                          class="js-confirm"
                          data-confirm-title="Batalkan appointment"
                          data-confirm-message="Yakin ingin membatalkan appointment ini?"
                          data-confirm-warn="1"
                          data-confirm-ok="Ya, batalkan">
                        @csrf @method('PATCH')
                        <button type="submit" class="btn btn-sm btn-outline-danger w-100">
                            <i class="bi bi-x-circle me-1"></i>Batalkan Appointment
                        </button>
                    </form>
                </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        @if($appointment->rekamMedis)
        <div class="card">
            <div class="card-header px-4 py-3">
                <span class="fw-600" style="font-weight:600;font-size:.9rem">Rekam Medis</span>
            </div>
            <div class="card-body p-4">
                @php $rm = $appointment->rekamMedis @endphp
                @foreach([
                    ['key'=>'S','label'=>'Subjective','class'=>'soap-s','value'=>$rm->subjective],
                    ['key'=>'O','label'=>'Objective', 'class'=>'soap-o','value'=>$rm->objective],
                    ['key'=>'A','label'=>'Assessment','class'=>'soap-a','value'=>$rm->assessment],
                    ['key'=>'P','label'=>'Plan',      'class'=>'soap-p','value'=>$rm->plan],
                ] as $row)
                <div class="d-flex gap-3 mb-4">
                    <div class="soap-badge {{ $row['class'] }}">{{ $row['key'] }}</div>
                    <div>
                        <div class="fw-600 mb-1" style="font-size:.82rem;font-weight:600">{{ $row['label'] }}</div>
                        <div style="font-size:.88rem;line-height:1.6;color:#374151">{{ $row['value'] }}</div>
                    </div>
                </div>
                @endforeach

                @if($rm->diagnosa_kode)
                <div class="d-flex gap-2 align-items-center p-3 rounded-2 mb-3" style="background:#f8fafc;border:1px solid #e2e8f0">
                    <i class="bi bi-tag text-muted"></i>
                    <span class="text-muted" style="font-size:.82rem">Kode ICD-10:</span>
                    <code>{{ $rm->diagnosa_kode }}</code>
                </div>
                @endif

                @if($rm->resep)
                <div class="p-3 rounded-2" style="background:#f0fdf4;border:1px solid #bbf7d0">
                    <div class="fw-600 mb-1" style="font-size:.82rem;font-weight:600;color:#16a34a">
                        <i class="bi bi-capsule me-1"></i>Resep Obat
                    </div>
                    <div style="font-size:.88rem;white-space:pre-line;color:#374151">{{ $rm->resep }}</div>
                </div>
                @endif
            </div>
        </div>
        @else
        <div class="card">
            <div class="card-body text-center py-5 text-muted">
                <i class="bi bi-journal-medical fs-2 d-block mb-2 opacity-50"></i>
                @if($appointment->status === 'selesai')
                    Rekam medis belum diisi oleh dokter.
                @elseif(in_array($appointment->status, ['menunggu','dikonfirmasi']))
                    Rekam medis akan tersedia setelah pemeriksaan selesai.
                @else
                    Tidak ada rekam medis untuk appointment ini.
                @endif
            </div>
        </div>
        @endif
    </div>
</div>

<div class="mt-3">
    <a href="{{ route('pasien.appointments.index') }}" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i>Kembali ke Daftar
    </a>
</div>
@endsection
