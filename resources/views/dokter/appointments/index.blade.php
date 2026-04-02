@extends('layouts.app')
@section('title', 'Appointments Saya')
@section('page-title', 'Appointments')

@section('content')
<div class="card">
    <div class="card-header px-4 py-3">
        <form class="row g-2" method="GET">
            <div class="col-auto">
                <select name="status" class="form-select form-select-sm">
                    <option value="">Semua Status</option>
                    @foreach(['menunggu','dikonfirmasi','selesai','dibatalkan'] as $s)
                        <option value="{{ $s }}" @selected(request('status') === $s)>{{ ucfirst($s) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-auto">
                <input type="date" name="tanggal" value="{{ request('tanggal') }}" class="form-control form-control-sm">
            </div>
            <div class="col-auto">
                <button class="btn btn-sm btn-outline-secondary">Filter</button>
                <a href="{{ route('dokter.appointments.index') }}" class="btn btn-sm btn-link text-muted">Reset</a>
            </div>
        </form>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th class="ps-4">Pasien</th>
                        <th>Tanggal</th>
                        <th>Jam</th>
                        <th>Keluhan</th>
                        <th>Status</th>
                        <th class="pe-4 text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($appointments as $apt)
                    <tr>
                        <td class="ps-4">
                            <div class="fw-500" style="font-weight:500">{{ $apt->pasien->user->name }}</div>
                            <small class="text-muted">{{ $apt->pasien->no_rm }}</small>
                        </td>
                        <td>{{ $apt->tanggal_appointment->isoFormat('D MMM YYYY') }}</td>
                        <td>{{ substr($apt->jadwal->jam_mulai, 0, 5) }}</td>
                        <td><span style="font-size:.83rem">{{ Str::limit($apt->keluhan, 45) }}</span></td>
                        <td>
                            <span class="badge bg-{{ $apt->status_badge }}-subtle text-{{ $apt->status_badge }} rounded-pill" style="font-size:.72rem">
                                {{ $apt->status_label }}
                            </span>
                        </td>
                        <td class="pe-4 text-end">
                            <a href="{{ route('dokter.appointments.show', $apt) }}" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-5">
                            <i class="bi bi-calendar-x fs-2 d-block mb-2"></i>Belum ada appointment
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($appointments->hasPages())
    <div class="card-footer px-4 py-3">{{ $appointments->links() }}</div>
    @endif
</div>
@endsection
