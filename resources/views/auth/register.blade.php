<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar — Klinik Central Medika</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background: #f1f5f9; min-height: 100vh; display:grid; place-items:center; padding: 32px 16px; }
        .auth-card { background: #fff; border-radius: 16px; border: 1px solid #e2e8f0; padding: 40px; width: 100%; max-width: 500px; box-shadow: 0 4px 24px rgba(0,0,0,.06); }
        .auth-logo { width: 48px; height: 48px; background: #2563eb; border-radius: 12px; display:grid; place-items:center; color:#fff; font-size:1.4rem; }
        .form-label { font-size:.85rem; font-weight:500; color:#374151; }
        .form-control, .form-select { font-size:.9rem; border-radius:8px; border-color:#d1d5db; }
        .form-control:focus, .form-select:focus { border-color:#2563eb; box-shadow:0 0 0 3px rgba(37,99,235,.1); }
        .btn-primary { background:#2563eb; border-color:#2563eb; border-radius:8px; font-weight:600; font-size:.9rem; padding:10px; }
        .btn-primary:hover { background:#1d4ed8; }
        .section-divider { font-size:.75rem; font-weight:600; color:#94a3b8; text-transform:uppercase; letter-spacing:.08em; margin: 20px 0 12px; }
    </style>
</head>
<body>
<div class="auth-card">
    <div class="d-flex align-items-center gap-3 mb-4">
        <div class="auth-logo"><i class="bi bi-hospital"></i></div>
        <div>
            <div class="fw-700 text-dark" style="font-size:1.1rem;font-weight:700">Klinik Central Medika</div>
            <div class="text-muted" style="font-size:.78rem">Daftar Pasien Baru</div>
        </div>
    </div>

    @if($errors->any())
        <div class="alert alert-danger py-2 mb-3" style="font-size:.85rem">
            <ul class="mb-0 ps-3">
                @foreach($errors->all() as $e)
                    <li>{{ $e }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div class="section-divider">Data Akun</div>
        <div class="mb-3">
            <label class="form-label">Nama Lengkap</label>
            <input type="text" name="name" value="{{ old('name') }}" class="form-control @error('name') is-invalid @enderror" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" required>
        </div>
        <div class="row g-3 mb-3">
            <div class="col">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
            </div>
            <div class="col">
                <label class="form-label">Konfirmasi Password</label>
                <input type="password" name="password_confirmation" class="form-control" required>
            </div>
        </div>

        <div class="section-divider">Data Diri</div>
        <div class="row g-3 mb-3">
            <div class="col-sm-6">
                <label class="form-label">Tanggal Lahir</label>
                <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}" class="form-control @error('tanggal_lahir') is-invalid @enderror" required>
            </div>
            <div class="col-sm-6">
                <label class="form-label">Jenis Kelamin</label>
                <select name="jenis_kelamin" class="form-select @error('jenis_kelamin') is-invalid @enderror" required>
                    <option value="">Pilih...</option>
                    <option value="L" @selected(old('jenis_kelamin') === 'L')>Laki-laki</option>
                    <option value="P" @selected(old('jenis_kelamin') === 'P')>Perempuan</option>
                </select>
            </div>
        </div>
        <div class="row g-3 mb-3">
            <div class="col-sm-6">
                <label class="form-label">Golongan Darah <span class="text-muted" style="font-weight:500">(opsional)</span></label>
                <select name="golongan_darah" class="form-select @error('golongan_darah') is-invalid @enderror">
                    <option value="" @selected(!old('golongan_darah'))>-</option>
                    <option value="A" @selected(old('golongan_darah') === 'A')>A</option>
                    <option value="B" @selected(old('golongan_darah') === 'B')>B</option>
                    <option value="AB" @selected(old('golongan_darah') === 'AB')>AB</option>
                    <option value="O" @selected(old('golongan_darah') === 'O')>O</option>
                </select>
            </div>
            <div class="col-sm-6">
                <label class="form-label">No. HP</label>
                <input type="text" name="no_hp" value="{{ old('no_hp') }}" class="form-control @error('no_hp') is-invalid @enderror" placeholder="0812xxxxxxxx" required>
            </div>
        </div>
        <div class="mb-4">
            <label class="form-label">Alamat</label>
            <textarea name="alamat" rows="2" class="form-control @error('alamat') is-invalid @enderror" required>{{ old('alamat') }}</textarea>
        </div>

        <button type="submit" class="btn btn-primary w-100">Daftar Sekarang</button>
    </form>

    <p class="text-center mt-4 mb-0" style="font-size:.85rem">
        Sudah punya akun? <a href="{{ route('login') }}" class="text-primary text-decoration-none fw-500">Masuk</a>
    </p>
</div>
</body>
</html>
