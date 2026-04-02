<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Klinik Central Medika') — Sistem Manajemen Klinik</title>

    <!-- Bootstrap 5 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --sidebar-width: 260px;
            --header-height: 60px;
            --primary: #2563eb;
            --primary-dark: #1d4ed8;
            --sidebar-bg: #0f172a;
            --sidebar-text: #94a3b8;
            --sidebar-active: #fff;
            --sidebar-active-bg: rgba(37,99,235,.2);
            --sidebar-hover-bg: rgba(255,255,255,.05);
        }

        * { box-sizing: border-box; }

        body {
            font-family: 'Inter', sans-serif;
            background: #f1f5f9;
            color: #1e293b;
        }

        /* ── Sidebar ─────────────────────────────────────────── */
        #sidebar {
            position: fixed;
            top: 0; left: 0;
            width: var(--sidebar-width);
            height: 100vh;
            background: var(--sidebar-bg);
            display: flex;
            flex-direction: column;
            z-index: 1000;
            transition: transform .3s ease;
        }

        .sidebar-brand {
            padding: 20px 24px;
            border-bottom: 1px solid rgba(255,255,255,.06);
        }
        .sidebar-brand h5 {
            color: #fff;
            font-weight: 700;
            font-size: .95rem;
            margin: 0;
            letter-spacing: -.01em;
        }
        .sidebar-brand small {
            color: var(--sidebar-text);
            font-size: .72rem;
            text-transform: uppercase;
            letter-spacing: .08em;
        }

        .sidebar-nav {
            flex: 1;
            overflow-y: auto;
            padding: 12px 0;
        }
        .sidebar-nav::-webkit-scrollbar { width: 0; }

        .nav-section-label {
            color: #475569;
            font-size: .65rem;
            font-weight: 600;
            letter-spacing: .1em;
            text-transform: uppercase;
            padding: 16px 24px 6px;
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 9px 24px;
            color: var(--sidebar-text);
            text-decoration: none;
            font-size: .85rem;
            font-weight: 500;
            border-radius: 0;
            transition: all .15s;
        }
        .sidebar-link:hover {
            color: #e2e8f0;
            background: var(--sidebar-hover-bg);
        }
        .sidebar-link.active {
            color: var(--sidebar-active);
            background: var(--sidebar-active-bg);
            border-right: 3px solid var(--primary);
        }
        .sidebar-link i { font-size: 1rem; width: 20px; flex-shrink: 0; }

        .sidebar-footer {
            padding: 16px 24px;
            border-top: 1px solid rgba(255,255,255,.06);
        }
        .sidebar-user {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .sidebar-avatar {
            width: 36px; height: 36px;
            background: var(--primary);
            border-radius: 50%;
            display: grid;
            place-items: center;
            color: #fff;
            font-weight: 700;
            font-size: .85rem;
            flex-shrink: 0;
        }
        .sidebar-user-name { color: #e2e8f0; font-size: .82rem; font-weight: 600; }
        .sidebar-user-role { color: #64748b; font-size: .7rem; }

        /* ── Main ────────────────────────────────────────────── */
        #main {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        #topbar {
            height: var(--header-height);
            background: #fff;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 28px;
            position: sticky;
            top: 0;
            z-index: 900;
        }

        .page-title {
            font-size: .95rem;
            font-weight: 600;
            color: #0f172a;
        }

        #content {
            padding: 28px;
            flex: 1;
        }

        /* ── Cards ───────────────────────────────────────────── */
        .stat-card {
            background: #fff;
            border-radius: 12px;
            padding: 20px 24px;
            border: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            gap: 16px;
        }
        .stat-icon {
            width: 48px; height: 48px;
            border-radius: 10px;
            display: grid;
            place-items: center;
            font-size: 1.3rem;
            flex-shrink: 0;
        }
        .stat-value { font-size: 1.6rem; font-weight: 700; color: #0f172a; line-height: 1; }
        .stat-label { font-size: .78rem; color: #64748b; margin-top: 2px; }

        /* ── Responsive ──────────────────────────────────────── */
        @media (max-width: 768px) {
            #sidebar { transform: translateX(-100%); }
            #sidebar.open { transform: translateX(0); }
            #main { margin-left: 0; }
            #sidebar-overlay {
                display: none;
                position: fixed; inset: 0;
                background: rgba(0,0,0,.5);
                z-index: 999;
            }
            #sidebar-overlay.open { display: block; }
        }

        /* ── Utilities ───────────────────────────────────────── */
        .badge-pill { border-radius: 999px; font-weight: 500; }
        .table th { font-weight: 600; font-size: .82rem; color: #475569; }
        .table td { font-size: .85rem; vertical-align: middle; }
        .card { border: 1px solid #e2e8f0; border-radius: 12px; }
        .card-header { border-bottom: 1px solid #e2e8f0; background: #f8fafc; }
        .btn-primary { background: var(--primary); border-color: var(--primary); }
        .btn-primary:hover { background: var(--primary-dark); border-color: var(--primary-dark); }
    </style>

    @stack('styles')
</head>
<body>

<div id="sidebar-overlay"></div>

{{-- ── Sidebar ─────────────────────────────────────────────────── --}}
<nav id="sidebar">
    <div class="sidebar-brand">
        <small>Sistem Manajemen</small>
        <h5><i class="bi bi-hospital me-2"></i>Klinik Central Medika</h5>
    </div>

    <div class="sidebar-nav">
        @auth
            @if(auth()->user()->isAdmin())
                <div class="nav-section-label">Utama</div>
                <a href="{{ route('admin.dashboard') }}" class="sidebar-link @active('admin.dashboard')">
                    <i class="bi bi-speedometer2"></i> Dashboard
                </a>

                <div class="nav-section-label">Manajemen</div>
                <a href="{{ route('admin.dokter.index') }}" class="sidebar-link @active('admin.dokter.*')">
                    <i class="bi bi-person-badge"></i> Dokter
                </a>
                <a href="{{ route('admin.pasien.index') }}" class="sidebar-link @active('admin.pasien.*')">
                    <i class="bi bi-people"></i> Pasien
                </a>
                <a href="{{ route('admin.appointments.index') }}" class="sidebar-link @active('admin.appointments.*')">
                    <i class="bi bi-calendar2-check"></i> Appointments
                </a>

                <div class="nav-section-label">Laporan</div>
                <a href="{{ route('admin.laporan.index') }}" class="sidebar-link @active('admin.laporan.*')">
                    <i class="bi bi-file-earmark-bar-graph"></i> Laporan
                </a>

            @elseif(auth()->user()->isDokter())
                <div class="nav-section-label">Utama</div>
                <a href="{{ route('dokter.dashboard') }}" class="sidebar-link @active('dokter.dashboard')">
                    <i class="bi bi-speedometer2"></i> Dashboard
                </a>

                <div class="nav-section-label">Klinik</div>
                <a href="{{ route('dokter.appointments.index') }}" class="sidebar-link @active('dokter.appointments.*')">
                    <i class="bi bi-calendar2-check"></i> Appointments
                </a>
                <a href="{{ route('dokter.jadwal.index') }}" class="sidebar-link @active('dokter.jadwal.*')">
                    <i class="bi bi-clock-history"></i> Jadwal Saya
                </a>

                <div class="nav-section-label">Akun</div>
                <a href="{{ route('dokter.profil.edit') }}" class="sidebar-link @active('dokter.profil.*')">
                    <i class="bi bi-person-circle"></i> Profil
                </a>

            @elseif(auth()->user()->isPasien())
                <div class="nav-section-label">Utama</div>
                <a href="{{ route('pasien.dashboard') }}" class="sidebar-link @active('pasien.dashboard')">
                    <i class="bi bi-speedometer2"></i> Dashboard
                </a>

                <div class="nav-section-label">Layanan</div>
                <a href="{{ route('pasien.appointments.create') }}" class="sidebar-link @active('pasien.appointments.create')">
                    <i class="bi bi-plus-circle"></i> Buat Appointment
                </a>
                <a href="{{ route('pasien.appointments.index') }}" class="sidebar-link @active('pasien.appointments.index')">
                    <i class="bi bi-calendar2-check"></i> Riwayat Appointment
                </a>
                <a href="{{ route('pasien.rekam-medis.index') }}" class="sidebar-link @active('pasien.rekam-medis.*')">
                    <i class="bi bi-journal-medical"></i> Rekam Medis
                </a>

                <div class="nav-section-label">Akun</div>
                <a href="{{ route('pasien.profil.edit') }}" class="sidebar-link @active('pasien.profil.*')">
                    <i class="bi bi-person-circle"></i> Profil
                </a>
            @endif
        @endauth
    </div>

    <div class="sidebar-footer">
        @auth
        <div class="sidebar-user">
            <div class="sidebar-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
            <div>
                <div class="sidebar-user-name">{{ Str::limit(auth()->user()->name, 20) }}</div>
                <div class="sidebar-user-role">{{ ucfirst(auth()->user()->role) }}</div>
            </div>
        </div>
        @endauth
    </div>
</nav>

{{-- ── Main Area ────────────────────────────────────────────────── --}}
<div id="main">
    <header id="topbar">
        <div class="d-flex align-items-center gap-3">
            <button class="btn btn-sm btn-light d-md-none" id="sidebar-toggle">
                <i class="bi bi-list fs-5"></i>
            </button>
            <span class="page-title">@yield('page-title', 'Dashboard')</span>
        </div>

        <div class="d-flex align-items-center gap-2">
            @auth
            <form action="{{ route('logout') }}" method="POST" class="mb-0">
                @csrf
                <button class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-box-arrow-right me-1"></i>Logout
                </button>
            </form>
            @endauth
        </div>
    </header>

    <main id="content">
        {{-- Alert Global --}}
        @foreach (['success' => 'success', 'error' => 'danger', 'warning' => 'warning'] as $key => $class)
            @if(session($key))
                <div class="alert alert-{{ $class }} alert-dismissible fade show mb-4" role="alert">
                    <i class="bi bi-{{ $key === 'success' ? 'check-circle' : 'exclamation-triangle' }} me-2"></i>
                    {{ session($key) }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
        @endforeach

        @yield('content')
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

{{-- Modal konfirmasi (ganti alert browser) --}}
<div class="modal fade" id="appConfirmModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow" style="border-radius:12px">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-600" id="appConfirmTitle" style="font-weight:600">Konfirmasi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body pt-2 text-secondary" id="appConfirmBody" style="font-size:.95rem"></div>
            <div class="modal-footer border-0 pt-0 gap-2">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="appConfirmBtnOk">Ya, lanjutkan</button>
            </div>
        </div>
    </div>
</div>

<script>
    // Sidebar toggle (mobile)
    const sidebar  = document.getElementById('sidebar');
    const overlay  = document.getElementById('sidebar-overlay');
    const toggler  = document.getElementById('sidebar-toggle');

    toggler?.addEventListener('click', () => {
        sidebar.classList.toggle('open');
        overlay.classList.toggle('open');
    });
    overlay?.addEventListener('click', () => {
        sidebar.classList.remove('open');
        overlay.classList.remove('open');
    });

    // Konfirmasi form (class js-confirm + data-confirm-message)
    (function () {
        const modalEl = document.getElementById('appConfirmModal');
        if (!modalEl) return;
        const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
        const titleEl = document.getElementById('appConfirmTitle');
        const bodyEl = document.getElementById('appConfirmBody');
        const okBtn = document.getElementById('appConfirmBtnOk');
        let pendingForm = null;

        document.addEventListener('submit', function (e) {
            const form = e.target;
            if (!(form instanceof HTMLFormElement) || !form.classList.contains('js-confirm')) return;
            if (form.dataset.confirmed === '1') {
                delete form.dataset.confirmed;
                return;
            }
            e.preventDefault();
            pendingForm = form;
            titleEl.textContent = form.dataset.confirmTitle || 'Konfirmasi';
            bodyEl.textContent = form.dataset.confirmMessage || 'Lanjutkan tindakan ini?';
            const danger = form.dataset.confirmDanger === '1';
            okBtn.classList.remove('btn-primary', 'btn-danger', 'btn-warning');
            okBtn.classList.add(danger ? 'btn-danger' : (form.dataset.confirmWarn === '1' ? 'btn-warning' : 'btn-primary'));
            okBtn.textContent = form.dataset.confirmOk || 'Ya, lanjutkan';
            modal.show();
        }, true);

        okBtn.addEventListener('click', function () {
            if (!pendingForm) return;
            const f = pendingForm;
            pendingForm = null;
            f.dataset.confirmed = '1';
            modal.hide();
            if (typeof f.requestSubmit === 'function') {
                f.requestSubmit();
            } else {
                f.submit();
            }
        });

        modalEl.addEventListener('hidden.bs.modal', function () {
            pendingForm = null;
        });
    })();
</script>
@stack('scripts')
</body>
</html>
