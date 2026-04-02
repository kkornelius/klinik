{{-- resources/views/pasien/appointments/index.blade.php --}}
@extends('layouts.app')
@section('title', 'Riwayat Appointment')
@section('page-title', 'Riwayat Appointment')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="fw-700 mb-0" style="font-weight:700">Appointment Saya</h5>
    </div>
    <a href="{{ route('pasien.appointments.create') }}" class="btn btn-primary btn-sm">
        <i class="bi bi-plus-lg me-1"></i>Buat Baru
    </a>
</div>

<div class="card">
    <div class="card-header px-4 py-3">
        <form class="d-flex gap-2" method="GET">
            <select name="status" class="form-select form-select-sm" style="width:auto">
                <option value="">Semua Status</option>
                @foreach(['menunggu','dikonfirmasi','selesai','dibatalkan'] as $s)
                    <option value="{{ $s }}" @selected(request('status') === $s)>{{ ucfirst($s) }}</option>
                @endforeach
            </select>
            <button class="btn btn-sm btn-outline-secondary">Filter</button>
        </form>
    </div>
    <div class="card-body p-0">
        @forelse($appointments as $apt)
        <div class="d-flex align-items-center gap-3 px-4 py-3 border-bottom">
            <div style="width:52px;text-align:center;flex-shrink:0;padding:8px;background:#f8fafc;border-radius:10px">
                <div style="font-size:1.3rem;font-weight:700;color:#2563eb;line-height:1">{{ $apt->tanggal_appointment->format('d') }}</div>
                <div style="font-size:.68rem;color:#94a3b8;text-transform:uppercase">{{ $apt->tanggal_appointment->isoFormat('MMM YYYY') }}</div>
            </div>
            <div class="flex-grow-1">
                <div class="fw-600" style="font-weight:600;font-size:.9rem">{{ $apt->dokter->user->name }}</div>
                <div class="text-muted" style="font-size:.78rem">
                    {{ $apt->dokter->spesialisasi }}
                    &nbsp;·&nbsp;
                    {{ substr($apt->jadwal->jam_mulai, 0, 5) }} – {{ substr($apt->jadwal->jam_selesai, 0, 5) }}
                </div>
                <div style="font-size:.78rem;color:#64748b;margin-top:2px">{{ Str::limit($apt->keluhan, 60) }}</div>
            </div>
            <div class="d-flex flex-column align-items-end gap-2">
                <span class="badge bg-{{ $apt->status_badge }}-subtle text-{{ $apt->status_badge }} rounded-pill" style="font-size:.72rem">
                    {{ $apt->status_label }}
                </span>
                <div class="d-flex gap-1">
                    <a href="{{ route('pasien.appointments.show', $apt) }}" class="btn btn-sm btn-outline-primary">
                        <i class="bi bi-eye"></i>
                    </a>
                    @if($apt->canBeCancelled())
                    <form action="{{ route('pasien.appointments.cancel', $apt) }}" method="POST"
                          class="js-confirm"
                          data-confirm-title="Batalkan appointment"
                          data-confirm-message="Batalkan appointment ini?"
                          data-confirm-warn="1"
                          data-confirm-ok="Ya, batalkan">
                        @csrf @method('PATCH')
                        <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bi bi-x-circle"></i></button>
                    </form>
                    @endif
                </div>
            </div>
        </div>
        @empty
        <div class="text-center text-muted py-5">
            <i class="bi bi-calendar-x fs-2 d-block mb-2"></i>
            Belum ada appointment.
            <a href="{{ route('pasien.appointments.create') }}" class="d-block mt-2 text-primary">Buat appointment pertama Anda</a>
        </div>
        @endforelse
    </div>
    @if($appointments->hasPages())
    <div class="card-footer px-4 py-3">{{ $appointments->links() }}</div>
    @endif
</div>
@endsection
