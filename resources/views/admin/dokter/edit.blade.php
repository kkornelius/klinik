@extends('layouts.app')
@section('title', 'Edit Dokter')
@section('page-title', 'Edit Dokter')

@section('content')
<div class="row justify-content-center">
<div class="col-lg-7">
<div class="card">
    <div class="card-header px-4 py-3">
        <span class="fw-600" style="font-weight:600">Edit: {{ $dokter->user->name }}</span>
    </div>
    <div class="card-body p-4">
        @if($errors->any())
            <div class="alert alert-danger mb-4">
                <ul class="mb-0 ps-3">@foreach($errors->all() as $e)<li style="font-size:.85rem">{{ $e }}</li>@endforeach</ul>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.dokter.update', $dokter) }}">
            @csrf @method('PUT')
            <div class="row g-3 mb-4">
                <div class="col-12">
                    <label class="form-label">Nama Lengkap</label>
                    <input type="text" name="name" value="{{ old('name', $dokter->user->name) }}" class="form-control @error('name') is-invalid @enderror" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" value="{{ old('email', $dokter->user->email) }}" class="form-control @error('email') is-invalid @enderror" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Password Baru <small class="text-muted">(kosongkan jika tidak diubah)</small></label>
                    <input type="password" name="password" class="form-control">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Spesialisasi</label>
                    <input type="text" name="spesialisasi" value="{{ old('spesialisasi', $dokter->spesialisasi) }}" class="form-control @error('spesialisasi') is-invalid @enderror" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">No. STR</label>
                    <input type="text" name="no_str" value="{{ old('no_str', $dokter->no_str) }}" class="form-control @error('no_str') is-invalid @enderror" required>
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
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                <a href="{{ route('admin.dokter.index') }}" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
</div>
</div>
@endsection
