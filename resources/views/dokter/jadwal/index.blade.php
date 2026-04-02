{{-- resources/views/dokter/jadwal/index.blade.php --}}
@extends('layouts.app')
@section('title', 'Jadwal Praktik')
@section('page-title', 'Jadwal Saya')

@section('content')
<div class="row g-4">
    {{-- Form tambah jadwal --}}
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header px-4 py-3">
                <span class="fw-600" style="font-weight:600;font-size:.9rem">Tambah Jadwal</span>
            </div>
            <div class="card-body p-4">
                @if(session('error'))
                    <div class="alert alert-danger py-2 mb-3" style="font-size:.85rem">{{ session('error') }}</div>
                @endif
                <form method="POST" action="{{ route('dokter.jadwal.store') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Hari</label>
                        <select name="hari" class="form-select @error('hari') is-invalid @enderror" required>
                            <option value="">Pilih hari...</option>
                            @foreach($HARI as $h)
                                <option value="{{ $h }}" @selected(old('hari') === $h)>{{ $h }}</option>
                            @endforeach
                        </select>
                        @error('hari')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-6">
                            <label class="form-label">Jam Mulai</label>
                            <input type="time" name="jam_mulai" value="{{ old('jam_mulai') }}" class="form-control @error('jam_mulai') is-invalid @enderror" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label">Jam Selesai</label>
                            <input type="time" name="jam_selesai" value="{{ old('jam_selesai') }}" class="form-control @error('jam_selesai') is-invalid @enderror" required>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Kuota Pasien</label>
                        <input type="number" name="kuota" value="{{ old('kuota', 10) }}" min="1" max="50" class="form-control @error('kuota') is-invalid @enderror" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-plus-lg me-1"></i>Tambah Jadwal
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- Daftar jadwal --}}
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header px-4 py-3">
                <span class="fw-600" style="font-weight:600;font-size:.9rem">Daftar Jadwal Praktik</span>
            </div>
            <div class="card-body p-0">
                @forelse($jadwals as $jadwal)
                <div class="d-flex align-items-center gap-3 px-4 py-3 border-bottom">
                    <div style="width:44px;height:44px;background:#f1f5f9;border-radius:10px;display:grid;place-items:center;flex-shrink:0">
                        <i class="bi bi-calendar3 text-primary"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="fw-600" style="font-weight:600;font-size:.9rem">{{ $jadwal->hari }}</div>
                        <div class="text-muted" style="font-size:.78rem">
                            {{ substr($jadwal->jam_mulai, 0, 5) }} – {{ substr($jadwal->jam_selesai, 0, 5) }}
                            &nbsp;·&nbsp; Kuota: {{ $jadwal->kuota }} pasien
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        @if($jadwal->is_active)
                            <span class="badge bg-success-subtle text-success rounded-pill" style="font-size:.72rem">Aktif</span>
                        @else
                            <span class="badge bg-secondary-subtle text-secondary rounded-pill" style="font-size:.72rem">Nonaktif</span>
                        @endif
                        <form action="{{ route('dokter.jadwal.toggle', $jadwal) }}" method="POST" class="d-inline">
                            @csrf @method('PATCH')
                            <button class="btn btn-sm btn-outline-{{ $jadwal->is_active ? 'warning' : 'success' }}" title="Toggle">
                                <i class="bi bi-{{ $jadwal->is_active ? 'pause' : 'play' }}-circle"></i>
                            </button>
                        </form>
                        <form action="{{ route('dokter.jadwal.destroy', $jadwal) }}" method="POST" class="d-inline js-confirm"
                              data-confirm-title="Hapus jadwal"
                              data-confirm-message="Hapus jadwal {{ $jadwal->hari }} ini?"
                              data-confirm-danger="1"
                              data-confirm-ok="Ya, hapus">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                        </form>
                    </div>
                </div>
                @empty
                <div class="text-center text-muted py-5">
                    <i class="bi bi-clock fs-2 d-block mb-2"></i>
                    Belum ada jadwal praktik
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
