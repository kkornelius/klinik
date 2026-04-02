{{-- resources/views/admin/appointments/index.blade.php --}}
@extends('layouts.app')
@section('title', 'Appointments')
@section('page-title', 'Appointments')

@section('content')
<div class="card">
    <div class="card-header px-4 py-3">
        <form class="row g-2 align-items-center" method="GET">
            <div class="col-12 col-sm-6 col-md-auto">
                <input type="text" name="search" value="{{ request('search') }}" class="form-control form-control-sm" placeholder="Nama pasien...">
            </div>
            <div class="col-12 col-sm-6 col-md-auto">
                <select name="status" class="form-select form-select-sm">
                    <option value="">Semua Status</option>
                    @foreach(['menunggu','dikonfirmasi','selesai','dibatalkan'] as $s)
                        <option value="{{ $s }}" @selected(request('status') === $s)>{{ ucfirst($s) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-12 col-sm-6 col-md-auto">
                <input type="date" name="tanggal" value="{{ request('tanggal') }}" class="form-control form-control-sm">
            </div>
            <div class="col-12 col-sm-6 col-md-auto d-flex flex-wrap gap-2">
                <button type="submit" class="btn btn-sm btn-outline-secondary">Filter</button>
                <a href="{{ route('admin.appointments.index') }}" class="btn btn-sm btn-link text-muted px-0">Reset</a>
            </div>
        </form>
    </div>

    {{-- Desktop: tabel --}}
    <div class="card-body p-0 d-none d-lg-block">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th class="ps-4">Pasien</th>
                        <th>Dokter</th>
                        <th>Tanggal</th>
                        <th>Jam</th>
                        <th>Status</th>
                        <th class="pe-4 text-end">Ubah Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($appointments as $apt)
                    <tr>
                        <td class="ps-4">
                            <div class="fw-500" style="font-weight:500">{{ $apt->pasien->user->name }}</div>
                            <small class="text-muted">{{ $apt->pasien->no_rm }}</small>
                        </td>
                        <td>
                            <div>{{ $apt->dokter->user->name }}</div>
                            <small class="text-muted">{{ $apt->dokter->spesialisasi }}</small>
                        </td>
                        <td>{{ $apt->tanggal_appointment->isoFormat('D MMM YYYY') }}</td>
                        <td>{{ substr($apt->jadwal->jam_mulai, 0, 5) }} – {{ substr($apt->jadwal->jam_selesai, 0, 5) }}</td>
                        <td>
                            <span class="badge bg-{{ $apt->status_badge }}-subtle text-{{ $apt->status_badge }} rounded-pill px-2">
                                {{ $apt->status_label }}
                            </span>
                        </td>
                        <td class="pe-4 text-end">
                            <form action="{{ route('admin.appointments.status', $apt) }}" method="POST" class="d-inline-flex gap-1 flex-wrap justify-content-end">
                                @csrf @method('PATCH')
                                <select name="status" class="form-select form-select-sm" style="width:140px;font-size:.8rem">
                                    @foreach(['menunggu','dikonfirmasi','selesai','dibatalkan'] as $s)
                                        <option value="{{ $s }}" @selected($apt->status === $s)>{{ ucfirst($s) }}</option>
                                    @endforeach
                                </select>
                                <button type="submit" class="btn btn-sm btn-primary">Simpan</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center text-muted py-5">Belum ada appointment</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Mobile / tablet: kartu (tanpa geser horizontal untuk ubah status) --}}
    <div class="card-body p-3 d-lg-none">
        @forelse($appointments as $apt)
        <div class="border rounded-3 p-3 mb-3 bg-white" style="border-color:#e2e8f0!important">
            <div class="d-flex justify-content-between align-items-start gap-2 mb-2">
                <div>
                    <div class="fw-600" style="font-weight:600">{{ $apt->pasien->user->name }}</div>
                    <small class="text-muted">{{ $apt->pasien->no_rm }}</small>
                </div>
                <span class="badge bg-{{ $apt->status_badge }}-subtle text-{{ $apt->status_badge }} rounded-pill px-2 flex-shrink-0">
                    {{ $apt->status_label }}
                </span>
            </div>
            <div class="small text-muted mb-1">{{ $apt->dokter->user->name }} · {{ $apt->dokter->spesialisasi }}</div>
            <div class="small mb-3">
                <i class="bi bi-calendar3 me-1"></i>{{ $apt->tanggal_appointment->isoFormat('D MMM YYYY') }}
                <span class="mx-2">·</span>
                <i class="bi bi-clock me-1"></i>{{ substr($apt->jadwal->jam_mulai, 0, 5) }} – {{ substr($apt->jadwal->jam_selesai, 0, 5) }}
            </div>

            @if($apt->status === 'menunggu')
            <div class="d-grid gap-2 mb-2">
                <form action="{{ route('admin.appointments.status', $apt) }}" method="POST">
                    @csrf @method('PATCH')
                    <input type="hidden" name="status" value="dikonfirmasi">
                    <button type="submit" class="btn btn-primary btn-sm w-100">
                        <i class="bi bi-check2-circle me-1"></i>Konfirmasi appointment
                    </button>
                </form>
            </div>
            @endif

            <form action="{{ route('admin.appointments.status', $apt) }}" method="POST" class="row g-2 align-items-end">
                @csrf @method('PATCH')
                <div class="col-12">
                    <label class="form-label small text-muted mb-1">Ubah status</label>
                    <select name="status" class="form-select form-select-sm">
                        @foreach(['menunggu','dikonfirmasi','selesai','dibatalkan'] as $s)
                            <option value="{{ $s }}" @selected($apt->status === $s)>{{ ucfirst($s) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-sm btn-outline-primary w-100">Simpan status</button>
                </div>
            </form>
        </div>
        @empty
        <p class="text-center text-muted py-4 mb-0">Belum ada appointment</p>
        @endforelse
    </div>

    @if($appointments->hasPages())
    <div class="card-footer px-4 py-3">{{ $appointments->links() }}</div>
    @endif
</div>
@endsection
