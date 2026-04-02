{{-- resources/views/pasien/profil/edit.blade.php --}}
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

        <form method="POST" action="{{ route('pasien.profil.update') }}">
            @csrf @method('PUT')

            <p class="mb-2" style="font-size:.75rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.08em">Akun</p>
            <div class="row g-3 mb-4">
                <div class="col-12">
                    <label class="form-label">Nama Lengkap</label>
                    <input type="text" name="name" value="{{ old('name', auth()->user()->name) }}" class="form-control @error('name') is-invalid @enderror" required>
                </div>
                <div class="col-12">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" value="{{ old('email', auth()->user()->email) }}" class="form-control @error('email') is-invalid @enderror" required>
                </div>
            </div>

            <p class="mb-2" style="font-size:.75rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.08em">Data Diri</p>
            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label class="form-label">Tanggal Lahir</label>
                    <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir', $pasien->tanggal_lahir?->format('Y-m-d')) }}" class="form-control">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Jenis Kelamin</label>
                    <select name="jenis_kelamin" class="form-select">
                        <option value="">Pilih...</option>
                        <option value="L" @selected(old('jenis_kelamin', $pasien->jenis_kelamin) === 'L')>Laki-laki</option>
                        <option value="P" @selected(old('jenis_kelamin', $pasien->jenis_kelamin) === 'P')>Perempuan</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">No. HP</label>
                    <input type="text" name="no_hp" value="{{ old('no_hp', $pasien->no_hp) }}" class="form-control" placeholder="0812xxxxxxxx">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Golongan Darah</label>
                    <select name="golongan_darah" class="form-select">
                        <option value="">Pilih...</option>
                        @foreach(['A','B','AB','O'] as $gb)
                            <option value="{{ $gb }}" @selected(old('golongan_darah', $pasien->golongan_darah) === $gb)>{{ $gb }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12">
                    <label class="form-label">Alamat</label>
                    <textarea name="alamat" rows="2" class="form-control">{{ old('alamat', $pasien->alamat) }}</textarea>
                </div>
                <div class="col-12">
                    <label class="form-label">Alergi <span class="text-muted" style="font-weight:400">(opsional)</span></label>
                    <input type="text" name="alergi" value="{{ old('alergi', $pasien->alergi) }}" class="form-control" placeholder="Contoh: Penisilin, seafood...">
                </div>
            </div>

            <p class="mb-2" style="font-size:.75rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.08em">Ganti Password <span style="font-weight:400;font-size:.7rem;text-transform:none">(kosongkan jika tidak diubah)</span></p>
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
