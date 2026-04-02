@extends('layouts.app')
@section('title', 'Profil Saya')
@section('page-title', 'Profil')

@section('content')
<div class="row justify-content-center">
<div class="col-lg-7">
<div class="card">
    <div class="card-header px-4 py-3">
        <span class="fw-600" style="font-weight:600;font-size:.9rem">Edit Profil</span>
    </div>
    <div class="card-body p-4">
        @if($errors->any())
            <div class="alert alert-danger mb-4">
                <ul class="mb-0 ps-3">@foreach($errors->all() as $e)<li style="font-size:.85rem">{{ $e }}</li>@endforeach</ul>
            </div>
        @endif

        <form method="POST" action="{{ route('dokter.profil.update') }}">
            @csrf @method('PUT')

            <p class="mb-2" style="font-size:.75rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.08em">Akun</p>
            <div class="row g-3 mb-4">
                <div class="col-12">
                    <label class="form-label">Nama Lengkap</label>
                    <input type="text" name="name" value="{{ old('name', auth()->user()->name) }}" class="form-control @error('name') is-invalid @enderror" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" value="{{ old('email', auth()->user()->email) }}" class="form-control @error('email') is-invalid @enderror" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Spesialisasi</label>
                    <input type="text" name="spesialisasi" value="{{ old('spesialisasi', $dokter->spesialisasi) }}" class="form-control @error('spesialisasi') is-invalid @enderror" required>
                </div>
                <div class="col-12">
                    <label class="form-label">No. HP</label>
                    <input type="text" name="no_hp" value="{{ old('no_hp', $dokter->no_hp) }}" class="form-control">
                </div>
                <div class="col-12">
                    <label class="form-label">Bio</label>
                    <textarea name="bio" rows="3" class="form-control">{{ old('bio', $dokter->bio) }}</textarea>
                </div>
            </div>

            <p class="mb-2" style="font-size:.75rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.08em">Ganti Password <span class="text-muted" style="font-weight:400;font-size:.7rem">(kosongkan jika tidak diubah)</span></p>
            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label class="form-label">Password Baru</label>
                    <input type="password" name="password" class="form-control">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" class="form-control">
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        </form>
    </div>
</div>
</div>
</div>
@endsection
