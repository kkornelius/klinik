@extends('layouts.app')
@section('title', 'Buat Appointment')
@section('page-title', 'Buat Appointment')

@section('content')
<div class="row justify-content-center">
<div class="col-lg-7">
<div class="card">
    <div class="card-header px-4 py-3">
        <span class="fw-600" style="font-weight:600;font-size:.9rem">Form Booking Appointment</span>
    </div>
    <div class="card-body p-4">
        @if($errors->any())
            <div class="alert alert-danger mb-4">
                <ul class="mb-0 ps-3">@foreach($errors->all() as $e)<li style="font-size:.85rem">{{ $e }}</li>@endforeach</ul>
            </div>
        @endif

        <form method="POST" action="{{ route('pasien.appointments.store') }}" id="formAppointment">
            @csrf

            {{-- Pilih Dokter --}}
            <div class="mb-4">
                <label class="form-label fw-500" style="font-weight:500">Pilih Dokter</label>
                <select name="dokter_id" id="dokterSelect"
                    class="form-select @error('dokter_id') is-invalid @enderror" required>
                    <option value="">— Pilih dokter —</option>
                    @foreach($dokters as $d)
                        <option value="{{ $d->id }}"
                            data-jadwal-url="{{ route('pasien.get-jadwal', $d) }}"
                            @selected(old('dokter_id') == $d->id)>
                            {{ $d->user->name }} — {{ $d->spesialisasi }}
                        </option>
                    @endforeach
                </select>
                @error('dokter_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            {{-- Pilih Tanggal --}}
            <div class="mb-4">
                <label class="form-label fw-500" style="font-weight:500">Tanggal Appointment</label>
                <input type="date" name="tanggal_appointment" id="tanggalInput"
                    value="{{ old('tanggal_appointment') }}"
                    min="{{ today()->toDateString() }}"
                    class="form-control @error('tanggal_appointment') is-invalid @enderror" required>
                @error('tanggal_appointment')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            {{-- Pilih Jadwal --}}
            <div class="mb-4" id="jadwalSection" style="display:none">
                <label class="form-label fw-500" style="font-weight:500">Pilih Jadwal</label>
                <div id="jadwalOptions" class="row g-2"></div>
                <input type="hidden" name="jadwal_id" id="jadwalId" value="{{ old('jadwal_id') }}">
                @error('jadwal_id')<div class="text-danger mt-1" style="font-size:.85rem">{{ $message }}</div>@enderror
            </div>

            <div id="jadwalLoading" class="mb-4 text-muted" style="display:none;font-size:.85rem">
                <div class="spinner-border spinner-border-sm me-2"></div>Memuat jadwal...
            </div>

            {{-- Keluhan --}}
            <div class="mb-4">
                <label class="form-label fw-500" style="font-weight:500">Keluhan Utama</label>
                <textarea name="keluhan" rows="3"
                    class="form-control @error('keluhan') is-invalid @enderror"
                    placeholder="Deskripsikan keluhan yang Anda rasakan..."
                    maxlength="500" required>{{ old('keluhan') }}</textarea>
                @error('keluhan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                <div class="text-muted mt-1" style="font-size:.75rem">Maks. 500 karakter</div>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-calendar-check me-1"></i>Buat Appointment
                </button>
                <a href="{{ route('pasien.dashboard') }}" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
</div>
</div>
@endsection

@push('scripts')
<script>
const dokterSelect  = document.getElementById('dokterSelect');
const tanggalInput  = document.getElementById('tanggalInput');
const jadwalSection = document.getElementById('jadwalSection');
const jadwalOptions = document.getElementById('jadwalOptions');
const jadwalLoading = document.getElementById('jadwalLoading');
const jadwalIdInput = document.getElementById('jadwalId');

const HARI_MAP = { 0:'Minggu',1:'Senin',2:'Selasa',3:'Rabu',4:'Kamis',5:'Jumat',6:'Sabtu' };

function setActiveJadwalCard(activeRadio = null) {
    document.querySelectorAll('.jadwal-card').forEach(card => {
        card.style.borderColor = '#e2e8f0';
        card.style.background = '#ffffff';
    });

    if (!activeRadio) return;

    const activeCard = activeRadio.closest('.jadwal-card');
    if (!activeCard) return;

    activeCard.style.borderColor = '#2563eb';
    activeCard.style.background = '#eff6ff';
}

function hariDariTanggal(dateStr) {
    if (!dateStr) return null;
    const d = new Date(dateStr + 'T00:00:00');
    return HARI_MAP[d.getDay()];
}

function loadJadwal() {
    const selected = dokterSelect.options[dokterSelect.selectedIndex];
    const url      = selected?.dataset?.jadwalUrl;
    const tanggal  = tanggalInput.value;

    if (!url) {
        jadwalSection.style.display = 'none';
        return;
    }

    const hasTanggal  = Boolean(tanggal);
    const targetHari  = hasTanggal ? hariDariTanggal(tanggal) : null;
    jadwalLoading.style.display = 'block';
    jadwalSection.style.display = 'none';
    jadwalOptions.innerHTML     = '';
    const prevJadwalId = jadwalIdInput.value;

    const qs = hasTanggal ? `?tanggal=${tanggal}` : '';
    fetch(`${url}${qs}`)
        .then(r => r.json())
        .then(jadwals => {
            jadwalLoading.style.display = 'none';
            let filtered = jadwals;

            if (hasTanggal) {
                filtered = jadwals.filter(j => j.hari === targetHari);
            }

            if (filtered.length === 0) {
                jadwalSection.style.display = 'block';
                jadwalOptions.innerHTML = `
                    <div class="col-12">
                        <div class="alert alert-warning py-2 mb-0" style="font-size:.85rem">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            ${hasTanggal
                                ? 'Tidak ada jadwal praktik pada hari <strong>' + targetHari + '</strong> untuk dokter ini.'
                                : 'Tidak ada jadwal praktik yang masih tersedia untuk dokter ini.'}
                        </div>
                    </div>`;
                return;
            }

            jadwalSection.style.display = 'block';
            filtered.forEach(j => {
                const habis  = j.sisa <= 0;
                const col    = document.createElement('div');
                col.className = 'col-sm-6';
                col.innerHTML = `
                    <label class="jadwal-card d-flex align-items-center gap-3 p-3 rounded-3 border cursor-pointer ${habis ? 'opacity-50' : ''}"
                           style="cursor:${habis?'not-allowed':'pointer'};border-color:#e2e8f0 !important;transition:all .15s">
                        <input type="radio" name="_jadwal_radio" value="${j.id}" ${habis ? 'disabled' : ''}
                               ${prevJadwalId == j.id ? 'checked' : ''}
                               class="d-none jadwal-radio">
                        <div style="width:40px;height:40px;background:#f1f5f9;border-radius:8px;display:grid;place-items:center;flex-shrink:0">
                            <i class="bi bi-clock" style="color:#2563eb"></i>
                        </div>
                        <div>
                            <div style="font-size:.88rem;font-weight:600">${j.jam_mulai.slice(0,5)} – ${j.jam_selesai.slice(0,5)}</div>
                            <div style="font-size:.75rem;color:${habis?'#ef4444':'#16a34a'}">
                                ${hasTanggal
                                    ? (habis ? 'Kuota penuh' : 'Sisa ' + j.sisa + ' dari ' + j.kuota + ' kuota')
                                    : 'Tersedia: ' + j.hari + ' · ' + j.tanggal_appointment}
                            </div>
                        </div>
                    </label>`;
                jadwalOptions.appendChild(col);

                // Jika ada nilai jadwal dari old(), pastikan tanggal ikut terisi.
                if (!tanggalInput.value && prevJadwalId == j.id && j.tanggal_appointment) {
                    tanggalInput.value = j.tanggal_appointment;
                }

                col.querySelector('.jadwal-radio').addEventListener('change', function () {
                    jadwalIdInput.value = this.value;
                    if (j.tanggal_appointment) tanggalInput.value = j.tanggal_appointment;
                    setActiveJadwalCard(this);
                });

                if (col.querySelector('.jadwal-radio').checked) {
                    setActiveJadwalCard(col.querySelector('.jadwal-radio'));
                }
            });
        })
        .catch(() => {
            jadwalLoading.style.display = 'none';
            jadwalOptions.innerHTML = '<div class="col-12"><div class="alert alert-danger py-2 mb-0" style="font-size:.85rem">Gagal memuat jadwal. Coba lagi.</div></div>';
            jadwalSection.style.display = 'block';
        });
}

dokterSelect.addEventListener('change', () => {
    // Hindari jadwal lama tersubmit saat dokter diganti.
    jadwalIdInput.value = '';
    loadJadwal();
});
tanggalInput.addEventListener('change', () => {
    // Saat tanggal berubah, jadwal yang dipilih sebelumnya bisa jadi tidak valid.
    jadwalIdInput.value = '';
    loadJadwal();
});

// Load on old() values (validation fail)
if (dokterSelect.value) loadJadwal();
</script>
@endpush
