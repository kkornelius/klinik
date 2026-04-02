@extends('layouts.app')
@section('title', 'Detail Pasien')
@section('page-title', 'Detail Pasien')

@section('content')
<div class="mb-3">
    <a href="{{ route('admin.pasien.index') }}" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i>Kembali
    </a>
    <form action="{{ route('admin.pasien.destroy', $pasien) }}" method="POST" class="d-inline js-confirm"
          data-confirm-title="Hapus pasien"
          data-confirm-message="Hapus pasien {{ $pasien->user->name }}? Data tidak dapat dikembalikan."
          data-confirm-danger="1"
          data-confirm-ok="Ya, hapus">
        @csrf @method('DELETE')
        <button type="submit" class="btn btn-sm btn-outline-danger ms-2">
            <i class="bi bi-trash"></i>
        </button>
    </form>
</div>

<div class="row g-4">
    <div class="col-lg-4">
        <div class="card h-100">
            <div class="card-header px-4 py-3">
                <span class="fw-600" style="font-weight:600;font-size:.9rem">Data Pasien</span>
            </div>
            <div class="card-body px-4 py-3">
                <div class="mb-3">
                    <div class="text-muted" style="font-size:.72rem;font-weight:600;text-transform:uppercase;letter-spacing:.06em">Nama</div>
                    <div class="fw-600" style="font-weight:600">{{ $pasien->user->name }}</div>
                </div>
                <div class="mb-3">
                    <div class="text-muted" style="font-size:.72rem;font-weight:600;text-transform:uppercase;letter-spacing:.06em">Email</div>
                    <div style="font-size:.9rem">{{ $pasien->user->email }}</div>
                </div>
                <div class="mb-3">
                    <div class="text-muted" style="font-size:.72rem;font-weight:600;text-transform:uppercase;letter-spacing:.06em">No. RM</div>
                    <div><code>{{ $pasien->no_rm }}</code></div>
                </div>
                <div class="mb-3">
                    <div class="text-muted" style="font-size:.72rem;font-weight:600;text-transform:uppercase;letter-spacing:.06em">Tanggal Lahir</div>
                    <div style="font-size:.9rem">{{ $pasien->tanggal_lahir?->isoFormat('D MMMM YYYY') ?? '-' }}</div>
                </div>
                <div class="mb-3">
                    <div class="text-muted" style="font-size:.72rem;font-weight:600;text-transform:uppercase;letter-spacing:.06em">Usia / JK</div>
                    <div style="font-size:.9rem">{{ $pasien->umur ? $pasien->umur . ' th' : '-' }} · {{ $pasien->jenis_kelamin_label }}</div>
                </div>
                <div class="mb-3">
                    <div class="text-muted" style="font-size:.72rem;font-weight:600;text-transform:uppercase;letter-spacing:.06em">No. HP</div>
                    <div style="font-size:.9rem">{{ $pasien->no_hp ?? '-' }}</div>
                </div>
                <div class="mb-0">
                    <div class="text-muted" style="font-size:.72rem;font-weight:600;text-transform:uppercase;letter-spacing:.06em">Alamat</div>
                    <div style="font-size:.88rem;line-height:1.45">{{ $pasien->alamat ?? '-' }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="card mb-4">
            <div class="card-header px-4 py-3">
                <span class="fw-600" style="font-weight:600;font-size:.9rem">Riwayat Appointment</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th class="ps-4">Tanggal</th>
                                <th>Dokter</th>
                                <th>Jam</th>
                                <th class="pe-4">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pasien->appointments->sortByDesc('tanggal_appointment') as $apt)
                            <tr>
                                <td class="ps-4" style="font-size:.85rem">{{ $apt->tanggal_appointment->isoFormat('D MMM YYYY') }}</td>
                                <td style="font-size:.85rem">{{ $apt->dokter->user->name }}</td>
                                <td style="font-size:.85rem">{{ substr($apt->jadwal->jam_mulai, 0, 5) }} – {{ substr($apt->jadwal->jam_selesai, 0, 5) }}</td>
                                <td class="pe-4">
                                    <span class="badge bg-{{ $apt->status_badge }}-subtle text-{{ $apt->status_badge }} rounded-pill" style="font-size:.72rem">
                                        {{ $apt->status_label }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="4" class="text-center text-muted py-4">Belum ada appointment</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header px-4 py-3">
                <span class="fw-600" style="font-weight:600;font-size:.9rem">Rekam Medis</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th class="ps-4">Tanggal</th>
                                <th>Dokter</th>
                                <th class="pe-4">Diagnosa</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pasien->rekamMedis->sortByDesc('created_at') as $rm)
                            <tr>
                                <td class="ps-4" style="font-size:.85rem">
                                    {{ $rm->appointment?->tanggal_appointment?->isoFormat('D MMM YYYY') ?? '-' }}
                                </td>
                                <td style="font-size:.85rem">{{ $rm->dokter->user->name }}</td>
                                <td class="pe-4" style="font-size:.85rem">{{ Str::limit($rm->assessment ?? $rm->diagnosa_kode ?? '-', 48) }}</td>
                            </tr>
                            @empty
                            <tr><td colspan="3" class="text-center text-muted py-4">Belum ada rekam medis</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
