{{-- =========================================================
     resources/views/admin/dokter/index.blade.php
     ========================================================= --}}
@extends('layouts.app')
@section('title', 'Manajemen Dokter')
@section('page-title', 'Dokter')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="fw-700 mb-0" style="font-weight:700">Daftar Dokter</h5>
        <small class="text-muted">Kelola data dokter klinik</small>
    </div>
    <a href="{{ route('admin.dokter.create') }}" class="btn btn-primary btn-sm">
        <i class="bi bi-plus-lg me-1"></i>Tambah Dokter
    </a>
</div>

<div class="card">
    <div class="card-header px-4 py-3">
        <form class="d-flex gap-2" method="GET">
            <input type="text" name="search" value="{{ request('search') }}" class="form-control form-control-sm" placeholder="Cari nama dokter...">
            <button class="btn btn-sm btn-outline-secondary px-3">Cari</button>
        </form>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th class="ps-4">Dokter</th>
                        <th>Spesialisasi</th>
                        <th>No. STR</th>
                        <th>No. HP</th>
                        <th>Status</th>
                        <th class="pe-4 text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($dokters as $dokter)
                    <tr>
                        <td class="ps-4">
                            <div class="fw-500" style="font-weight:500">{{ $dokter->user->name }}</div>
                            <small class="text-muted">{{ $dokter->user->email }}</small>
                        </td>
                        <td>{{ $dokter->spesialisasi }}</td>
                        <td><code>{{ $dokter->no_str }}</code></td>
                        <td>{{ $dokter->no_hp ?? '-' }}</td>
                        <td>
                            @if($dokter->user->is_active)
                                <span class="badge bg-success-subtle text-success rounded-pill px-2">Aktif</span>
                            @else
                                <span class="badge bg-danger-subtle text-danger rounded-pill px-2">Nonaktif</span>
                            @endif
                        </td>
                        <td class="pe-4 text-end">
                            <a href="{{ route('admin.dokter.edit', $dokter) }}" class="btn btn-sm btn-outline-primary me-1">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('admin.dokter.toggle', $dokter) }}" method="POST" class="d-inline">
                                @csrf @method('PATCH')
                                <button class="btn btn-sm btn-outline-{{ $dokter->user->is_active ? 'warning' : 'success' }}" title="{{ $dokter->user->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                                    <i class="bi bi-{{ $dokter->user->is_active ? 'pause' : 'play' }}-circle"></i>
                                </button>
                            </form>
                            <form action="{{ route('admin.dokter.destroy', $dokter) }}" method="POST" class="d-inline js-confirm"
                                  data-confirm-title="Hapus dokter"
                                  data-confirm-message="Hapus dokter {{ $dokter->user->name }}? Data tidak dapat dikembalikan."
                                  data-confirm-danger="1"
                                  data-confirm-ok="Ya, hapus">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger ms-1"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-5">
                            <i class="bi bi-person-badge fs-2 d-block mb-2"></i>Belum ada data dokter
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($dokters->hasPages())
    <div class="card-footer px-4 py-3">
        {{ $dokters->links() }}
    </div>
    @endif
</div>
@endsection
