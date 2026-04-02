{{-- resources/views/admin/pasien/index.blade.php --}}
@extends('layouts.app')
@section('title', 'Data Pasien')
@section('page-title', 'Pasien')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="fw-700 mb-0" style="font-weight:700">Daftar Pasien</h5>
        <small class="text-muted">Total: {{ $pasiens->total() }} pasien terdaftar</small>
    </div>
</div>

<div class="card">
    <div class="card-header px-4 py-3">
        <form class="d-flex gap-2" method="GET">
            <input type="text" name="search" value="{{ request('search') }}" class="form-control form-control-sm" placeholder="Cari nama / No. RM...">
            <button class="btn btn-sm btn-outline-secondary px-3">Cari</button>
        </form>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th class="ps-4">Pasien</th>
                        <th>No. RM</th>
                        <th>Usia</th>
                        <th>Jenis Kelamin</th>
                        <th>No. HP</th>
                        <th class="pe-4 text-end">Detail</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pasiens as $pasien)
                    <tr>
                        <td class="ps-4">
                            <div class="fw-500" style="font-weight:500">{{ $pasien->user->name }}</div>
                            <small class="text-muted">{{ $pasien->user->email }}</small>
                        </td>
                        <td><code>{{ $pasien->no_rm }}</code></td>
                        <td>{{ $pasien->umur ? $pasien->umur . ' th' : '-' }}</td>
                        <td>{{ $pasien->jenis_kelamin_label }}</td>
                        <td>{{ $pasien->no_hp ?? '-' }}</td>
                        <td class="pe-4 text-end">
                            <a href="{{ route('admin.pasien.show', $pasien) }}" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-eye"></i>
                            </a>
                            <form action="{{ route('admin.pasien.destroy', $pasien) }}" method="POST" class="d-inline js-confirm"
                                  data-confirm-title="Hapus pasien"
                                  data-confirm-message="Hapus pasien {{ $pasien->user->name }}? Data tidak dapat dikembalikan."
                                  data-confirm-danger="1"
                                  data-confirm-ok="Ya, hapus">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger ms-1">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-5">Belum ada data pasien</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($pasiens->hasPages())
    <div class="card-footer px-4 py-3">{{ $pasiens->links() }}</div>
    @endif
</div>
@endsection
