{{-- resources/views/pasien/rekam-medis/index.blade.php --}}
@extends('layouts.app')
@section('title', 'Rekam Medis Saya')
@section('page-title', 'Rekam Medis')

@section('content')
<div class="card">
    <div class="card-header px-4 py-3">
        <span class="fw-600" style="font-weight:600;font-size:.9rem">Riwayat Rekam Medis</span>
    </div>
    <div class="card-body p-0">
        @forelse($rekamMedis as $rm)
        <a href="{{ route('pasien.rekam-medis.show', $rm) }}"
           class="d-flex align-items-center gap-3 px-4 py-3 border-bottom text-decoration-none text-dark">
            <div style="width:40px;height:40px;background:#f1f5f9;border-radius:10px;display:grid;place-items:center;flex-shrink:0">
                <i class="bi bi-journal-medical" style="color:#2563eb"></i>
            </div>
            <div class="flex-grow-1">
                <div class="fw-500" style="font-weight:500;font-size:.9rem">{{ $rm->dokter->user->name }}</div>
                <div class="text-muted" style="font-size:.76rem">
                    {{ $rm->created_at->isoFormat('D MMM YYYY') }}
                    @if($rm->diagnosa_kode)
                        &nbsp;·&nbsp; Kode: <code>{{ $rm->diagnosa_kode }}</code>
                    @endif
                </div>
                <div style="font-size:.78rem;color:#475569;margin-top:2px">
                    {{ Str::limit($rm->assessment, 70) }}
                </div>
            </div>
            <i class="bi bi-chevron-right text-muted"></i>
        </a>
        @empty
        <div class="text-center text-muted py-5">
            <i class="bi bi-journal-x fs-2 d-block mb-2 opacity-50"></i>
            Belum ada rekam medis
        </div>
        @endforelse
    </div>
    @if($rekamMedis->hasPages())
    <div class="card-footer px-4 py-3">{{ $rekamMedis->links() }}</div>
    @endif
</div>
@endsection
