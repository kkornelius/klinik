@extends('layouts.app')
@section('title', 'Detail Appointment')
@section('page-title', 'Detail Appointment')

@push('styles')
<style>
.info-label { font-size:.75rem; font-weight:600; color:#94a3b8; text-transform:uppercase; letter-spacing:.06em; margin-bottom:2px; }
.info-value  { font-size:.9rem; color:#1e293b; font-weight:500; }
.soap-badge  { display:inline-flex;align-items:center;justify-content:center;width:28px;height:28px;border-radius:8px;font-weight:700;font-size:.85rem;flex-shrink:0; }
.soap-s { background:#dbeafe; color:#2563eb; }
.soap-o { background:#dcfce7; color:#16a34a; }
.soap-a { background:#ffedd5; color:#ea580c; }
.soap-p { background:#f3e8ff; color:#9333ea; }
</style>
@endpush

@section('content')
<div class="row g-4">
    {{-- Kiri: info pasien + status --}}
    <div class="col-lg-4">
        <div class="card mb-3">
            <div class="card-header px-4 py-3">
                <span class="fw-600" style="font-weight:600;font-size:.9rem">Info Pasien</span>
            </div>
            <div class="card-body px-4 py-3">
                <div class="d-flex align-items-center gap-3 mb-3 pb-3" style="border-bottom:1px solid #f1f5f9">
                    <div style="width:44px;height:44px;background:#2563eb;border-radius:10px;display:grid;place-items:center;color:#fff;font-weight:700;font-size:1.1rem">
                        {{ strtoupper(substr($appointment->pasien->user->name, 0, 1)) }}
                    </div>
                    <div>
                        <div class="fw-700" style="font-weight:700">{{ $appointment->pasien->user->name }}</div>
                        <div class="text-muted" style="font-size:.78rem">{{ $appointment->pasien->no_rm }}</div>
                    </div>
                </div>

                @php $p = $appointment->pasien @endphp
                <div class="row g-3">
                    <div class="col-6">
                        <div class="info-label">Usia</div>
                        <div class="info-value">{{ $p->umur ?? '-' }} th</div>
                    </div>
                    <div class="col-6">
                        <div class="info-label">Jenis Kelamin</div>
                        <div class="info-value">{{ $p->jenis_kelamin_label }}</div>
                    </div>
                    <div class="col-6">
                        <div class="info-label">Gol. Darah</div>
                        <div class="info-value">{{ $p->golongan_darah ?? '-' }}</div>
                    </div>
                    <div class="col-6">
                        <div class="info-label">No. HP</div>
                        <div class="info-value">{{ $p->no_hp ?? '-' }}</div>
                    </div>
                    @if($p->alergi)
                    <div class="col-12">
                        <div class="info-label">Alergi</div>
                        <div class="info-value text-danger">⚠ {{ $p->alergi }}</div>
                    </div>
                    @endif
                    <div class="col-12">
                        <div class="info-label">Keluhan</div>
                        <div class="info-value">{{ $appointment->keluhan }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body px-4 py-3">
                <div class="info-label mb-1">Tanggal</div>
                <div class="info-value mb-3">{{ $appointment->tanggal_appointment->isoFormat('dddd, D MMMM YYYY') }}</div>
                <div class="info-label mb-1">Jam</div>
                <div class="info-value mb-3">{{ substr($appointment->jadwal->jam_mulai,0,5) }} – {{ substr($appointment->jadwal->jam_selesai,0,5) }}</div>
                <div class="info-label mb-1">Status</div>
                <span class="badge bg-{{ $appointment->status_badge }}-subtle text-{{ $appointment->status_badge }} rounded-pill px-3 py-2">
                    {{ $appointment->status_label }}
                </span>

                @if($appointment->status === 'menunggu')
                <form action="{{ route('dokter.appointments.status', $appointment) }}" method="POST" class="mt-3">
                    @csrf @method('PATCH')
                    <input type="hidden" name="status" value="dikonfirmasi">
                    <button class="btn btn-sm btn-success w-100">
                        <i class="bi bi-check2 me-1"></i>Konfirmasi Appointment
                    </button>
                </form>
                @endif
            </div>
        </div>
    </div>

    {{-- Kanan: rekam medis --}}
    <div class="col-lg-8">
        @if($appointment->rekamMedis)
        {{-- Tampilkan rekam medis yang sudah ada --}}
        <div class="card">
            <div class="card-header px-4 py-3 d-flex align-items-center justify-content-between">
                <span class="fw-600" style="font-weight:600;font-size:.9rem">Rekam Medis SOAP</span>
                <span class="badge bg-success-subtle text-success rounded-pill px-3">Sudah diisi</span>
            </div>
            <div class="card-body p-4">
                @php $rm = $appointment->rekamMedis @endphp
                @foreach([
                    ['key'=>'S','label'=>'Subjective','class'=>'soap-s','desc'=>'Keluhan pasien','value'=>$rm->subjective],
                    ['key'=>'O','label'=>'Objective', 'class'=>'soap-o','desc'=>'Hasil pemeriksaan','value'=>$rm->objective],
                    ['key'=>'A','label'=>'Assessment','class'=>'soap-a','desc'=>'Diagnosa','value'=>$rm->assessment],
                    ['key'=>'P','label'=>'Plan',      'class'=>'soap-p','desc'=>'Rencana pengobatan','value'=>$rm->plan],
                ] as $row)
                <div class="d-flex gap-3 mb-4">
                    <div class="soap-badge {{ $row['class'] }}">{{ $row['key'] }}</div>
                    <div class="flex-grow-1">
                        <div class="fw-600 mb-1" style="font-size:.82rem;font-weight:600">{{ $row['label'] }} <span class="text-muted fw-400">— {{ $row['desc'] }}</span></div>
                        <div style="font-size:.88rem;line-height:1.6;color:#374151">{{ $row['value'] }}</div>
                    </div>
                </div>
                @endforeach

                @if($rm->diagnosa_kode)
                <div class="d-flex gap-2 align-items-center mb-3 p-3 rounded-2" style="background:#f8fafc;border:1px solid #e2e8f0">
                    <i class="bi bi-tag text-muted"></i>
                    <span class="text-muted" style="font-size:.82rem">Kode ICD-10:</span>
                    <code>{{ $rm->diagnosa_kode }}</code>
                </div>
                @endif

                @if($rm->resep)
                <div class="p-3 rounded-2" style="background:#f0fdf4;border:1px solid #bbf7d0">
                    <div class="fw-600 mb-1" style="font-size:.82rem;font-weight:600;color:#16a34a"><i class="bi bi-capsule me-1"></i>Resep Obat</div>
                    <div style="font-size:.88rem;white-space:pre-line;color:#374151">{{ $rm->resep }}</div>
                </div>
                @endif

                <details class="mt-3">
                    <summary class="btn btn-sm btn-outline-primary w-100 text-start">
                        <i class="bi bi-pencil me-1"></i>Edit Rekam Medis
                    </summary>
                    <div class="mt-3">
                        <form action="{{ route('dokter.rekam-medis.update', $rm) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="appointment_id" value="{{ $appointment->id }}">

                            @foreach([
                                ['name'=>'subjective','key'=>'S','class'=>'soap-s','label'=>'Subjective','hint'=>'Keluhan utama & riwayat penyakit yang disampaikan pasien'],
                                ['name'=>'objective', 'key'=>'O','class'=>'soap-o','label'=>'Objective', 'hint'=>'Hasil pemeriksaan fisik: tanda vital, inspeksi, palpasi, dll'],
                                ['name'=>'assessment','key'=>'A','class'=>'soap-a','label'=>'Assessment','hint'=>'Diagnosa kerja berdasarkan data S dan O'],
                                ['name'=>'plan',      'key'=>'P','class'=>'soap-p','label'=>'Plan',      'hint'=>'Rencana tatalaksana: terapi, rujukan, edukasi, follow-up'],
                            ] as $field)
                                <div class="mb-4">
                                    <div class="d-flex align-items-center gap-2 mb-2">
                                        <div class="soap-badge {{ $field['class'] }}">{{ $field['key'] }}</div>
                                        <div>
                                            <span class="fw-600" style="font-weight:600;font-size:.88rem">{{ $field['label'] }}</span>
                                            <div class="text-muted" style="font-size:.76rem">{{ $field['hint'] }}</div>
                                        </div>
                                    </div>
                                    <textarea name="{{ $field['name'] }}" rows="3"
                                        class="form-control @error($field['name']) is-invalid @enderror"
                                        style="font-size:.88rem"
                                        required>{{ old($field['name'], $rm->{$field['name']}) }}</textarea>
                                    @error($field['name'])<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            @endforeach

                            <div class="row g-3 mb-4">
                                <div class="col-md-4">
                                    <label class="form-label" style="font-size:.83rem;font-weight:500">
                                        Kode ICD-10 <span class="text-muted">(opsional)</span>
                                    </label>
                                    <input type="text" name="diagnosa_kode"
                                        value="{{ old('diagnosa_kode', $rm->diagnosa_kode) }}"
                                        class="form-control form-control-sm"
                                        placeholder="mis. J06.9">
                                </div>
                                <div class="col-md-8">
                                    <label class="form-label" style="font-size:.83rem;font-weight:500">
                                        Resep Obat <span class="text-muted">(opsional)</span>
                                    </label>
                                    <textarea name="resep" rows="2"
                                        class="form-control form-control-sm"
                                        placeholder="Nama obat, dosis, aturan pakai...">{{ old('resep', $rm->resep) }}</textarea>
                                </div>
                            </div>

                            <div class="d-flex gap-2 pt-2" style="border-top:1px solid #f1f5f9">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-save me-1"></i>Simpan Perubahan
                                </button>
                                <button type="button" class="btn btn-outline-secondary" onclick="this.closest('details').removeAttribute('open')">
                                    Batal
                                </button>
                            </div>
                        </form>
                    </div>
                </details>
            </div>
        </div>

        @elseif(in_array($appointment->status, ['dikonfirmasi', 'menunggu']))
        {{-- Form isi rekam medis SOAP --}}
        <div class="card">
            <div class="card-header px-4 py-3">
                <span class="fw-600" style="font-weight:600;font-size:.9rem">Isi Rekam Medis SOAP</span>
            </div>
            <div class="card-body p-4">
                @if($errors->any())
                    <div class="alert alert-danger mb-4">
                        <ul class="mb-0 ps-3">@foreach($errors->all() as $e)<li style="font-size:.85rem">{{ $e }}</li>@endforeach</ul>
                    </div>
                @endif

                <form action="{{ route('dokter.rekam-medis.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="appointment_id" value="{{ $appointment->id }}">

                    @foreach([
                        ['name'=>'subjective','key'=>'S','class'=>'soap-s','label'=>'Subjective','hint'=>'Keluhan utama & riwayat penyakit yang disampaikan pasien'],
                        ['name'=>'objective', 'key'=>'O','class'=>'soap-o','label'=>'Objective', 'hint'=>'Hasil pemeriksaan fisik: tanda vital, inspeksi, palpasi, dll'],
                        ['name'=>'assessment','key'=>'A','class'=>'soap-a','label'=>'Assessment','hint'=>'Diagnosa kerja berdasarkan data S dan O'],
                        ['name'=>'plan',      'key'=>'P','class'=>'soap-p','label'=>'Plan',      'hint'=>'Rencana tatalaksana: terapi, rujukan, edukasi, follow-up'],
                    ] as $field)
                    <div class="mb-4">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <div class="soap-badge {{ $field['class'] }}">{{ $field['key'] }}</div>
                            <div>
                                <span class="fw-600" style="font-weight:600;font-size:.88rem">{{ $field['label'] }}</span>
                                <div class="text-muted" style="font-size:.76rem">{{ $field['hint'] }}</div>
                            </div>
                        </div>
                        <textarea name="{{ $field['name'] }}" rows="3"
                            class="form-control @error($field['name']) is-invalid @enderror"
                            style="font-size:.88rem"
                            required>{{ old($field['name']) }}</textarea>
                        @error($field['name'])<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    @endforeach

                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <label class="form-label" style="font-size:.83rem;font-weight:500">Kode ICD-10 <span class="text-muted">(opsional)</span></label>
                            <input type="text" name="diagnosa_kode" value="{{ old('diagnosa_kode') }}" class="form-control form-control-sm" placeholder="mis. J06.9">
                        </div>
                        <div class="col-md-8">
                            <label class="form-label" style="font-size:.83rem;font-weight:500">Resep Obat <span class="text-muted">(opsional)</span></label>
                            <textarea name="resep" rows="2" class="form-control form-control-sm" placeholder="Nama obat, dosis, aturan pakai...">{{ old('resep') }}</textarea>
                        </div>
                    </div>

                    <div class="d-flex gap-2 pt-2" style="border-top:1px solid #f1f5f9">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-1"></i>Simpan Rekam Medis
                        </button>
                        <a href="{{ route('dokter.appointments.index') }}" class="btn btn-outline-secondary">Kembali</a>
                    </div>
                </form>
            </div>
        </div>

        @else
        <div class="card">
            <div class="card-body text-center py-5 text-muted">
                <i class="bi bi-journal-x fs-2 d-block mb-2"></i>
                Rekam medis tidak tersedia untuk appointment dengan status <strong>{{ $appointment->status }}</strong>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
