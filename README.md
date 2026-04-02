# Sistem Manajemen Klinik — Laravel 11

Starter kit lengkap sistem manajemen klinik berbasis Laravel 11 + MySQL + Bootstrap 5.

## Fitur

| Modul | Fitur |
|---|---|
| **Auth** | Login, register pasien, middleware role, guard aktif/nonaktif |
| **Admin** | Dashboard statistik + chart, CRUD dokter, daftar pasien, manajemen appointment, laporan + PDF export |
| **Dokter** | Dashboard antrian harian, detail appointment, input rekam medis SOAP, kelola jadwal praktik, profil |
| **Pasien** | Dashboard, booking appointment (AJAX jadwal + cek kuota), riwayat, baca rekam medis, profil |

## Stack

- Laravel 11
- MySQL
- Bootstrap 5.3
- Chart.js 4
- barryvdh/laravel-dompdf

## Instalasi

```bash
# 1. Clone / copy file ke project Laravel 11 Anda
# 2. Install dependencies
composer require barryvdh/laravel-dompdf

# 3. Konfigurasi .env
DB_DATABASE=klinik
DB_USERNAME=root
DB_PASSWORD=

# 4. Jalankan migration
php artisan migrate

# 5. Seed data demo
php artisan db:seed

# 6. Jalankan server
php artisan serve
```

## Akun Demo (setelah seeder)

| Role | Email | Password |
|---|---|---|
| Admin | admin@klinik.test | password |
| Dokter 1 | andi@klinik.test | password |
| Dokter 2 | siti@klinik.test | password |
| Dokter 3 | budi@klinik.test | password |
| Pasien | rina@mail.test | password |
| Pasien | dimas@mail.test | password |

## Struktur File yang Ditambahkan

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Auth/AuthController.php
│   │   ├── Admin/{Dashboard,Dokter,Pasien,Appointment,Laporan}Controller.php
│   │   ├── Dokter/{Dashboard,Appointment,RekamMedis,Jadwal,Profil}Controller.php
│   │   └── Pasien/{Dashboard,Appointment,RekamMedis,Profil}Controller.php
│   └── Middleware/RoleMiddleware.php
├── Models/{User,Dokter,Pasien,JadwalDokter,Appointment,RekamMedis}.php
└── Providers/AppServiceProvider.php  ← @active directive + Carbon ID locale

bootstrap/app.php  ← middleware alias 'role'
routes/web.php

database/
├── migrations/ (4 file migration)
└── seeders/DatabaseSeeder.php

resources/views/
├── layouts/app.blade.php
├── auth/{login,register}.blade.php
├── admin/{dashboard, dokter/*, pasien/*, appointments/*, laporan/*}.blade.php
├── dokter/{dashboard, appointments/*, jadwal/*, profil/*}.blade.php
├── pasien/{dashboard, appointments/*, rekam-medis/*, profil/*}.blade.php
└── pdf/laporan.blade.php
```

## Catatan Penting

- Middleware `role` didaftarkan di `bootstrap/app.php` (cara Laravel 11, bukan `Kernel.php`)
- `@active('admin.*')` menggunakan wildcard dari `request()->routeIs()`
- Cek kuota appointment dilakukan di `JadwalDokter::sisaKuota()` dan di controller
- Rekam medis SOAP otomatis mengubah status appointment ke `selesai` saat disimpan
- PDF menggunakan `landscape` A4 via DomPDF
