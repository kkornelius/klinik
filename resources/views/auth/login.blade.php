{{-- resources/views/auth/login.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — Klinik Central Medika</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background: #f1f5f9; min-height: 100vh; display:grid; place-items:center; }
        .auth-card { background: #fff; border-radius: 16px; border: 1px solid #e2e8f0; padding: 40px; width: 100%; max-width: 420px; box-shadow: 0 4px 24px rgba(0,0,0,.06); }
        .auth-logo { width: 48px; height: 48px; background: #2563eb; border-radius: 12px; display:grid; place-items:center; color:#fff; font-size:1.4rem; }
        .form-label { font-size:.85rem; font-weight:500; color:#374151; }
        .form-control { font-size:.9rem; border-radius:8px; border-color:#d1d5db; }
        .form-control:focus { border-color:#2563eb; box-shadow:0 0 0 3px rgba(37,99,235,.1); }
        .btn-primary { background:#2563eb; border-color:#2563eb; border-radius:8px; font-weight:600; font-size:.9rem; padding:10px; }
        .btn-primary:hover { background:#1d4ed8; }
    </style>
</head>
<body>
<div class="auth-card">
    <div class="d-flex align-items-center gap-3 mb-4">
        <div class="auth-logo"><i class="bi bi-hospital"></i></div>
        <div>
            <div class="fw-700 text-dark" style="font-size:1.1rem;font-weight:700">Klinik Central Medika</div>
            <div class="text-muted" style="font-size:.78rem">Sistem Manajemen Klinik</div>
        </div>
    </div>

    <h5 class="fw-600 mb-1" style="font-weight:600">Masuk ke Akun</h5>
    <p class="text-muted mb-4" style="font-size:.85rem">Selamat datang kembali. Masukkan kredensial Anda.</p>

    @if($errors->any())
        <div class="alert alert-danger py-2 mb-3" style="font-size:.85rem">
            {{ $errors->first() }}
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger py-2 mb-3" style="font-size:.85rem">{{ session('error') }}</div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf
        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" placeholder="nama@email.com" required autofocus>
        </div>
        <div class="mb-4">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="••••••••" required>
        </div>
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div class="form-check mb-0">
                <input class="form-check-input" type="checkbox" name="remember" id="remember">
                <label class="form-check-label" for="remember" style="font-size:.83rem">Ingat saya</label>
            </div>
        </div>
        <button type="submit" class="btn btn-primary w-100">Masuk</button>
    </form>

    <p class="text-center mt-4 mb-0" style="font-size:.85rem">
        Belum punya akun?
        <a href="{{ route('register') }}" class="text-primary fw-500 text-decoration-none">Daftar sebagai pasien</a>
    </p>
</div>
</body>
</html>
