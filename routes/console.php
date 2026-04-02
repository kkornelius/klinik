<?php

use App\Models\User;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Mail;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('mail:test {email?}', function (?string $email = null) {
    $to = $email ?: User::query()->where('role', 'admin')->where('is_active', true)->value('email');

    if (! $to) {
        $this->error('Tidak ada alamat tujuan. Tambahkan argumen: php artisan mail:test admin@example.com');

        return 1;
    }

    Mail::raw('Ini email uji dari sistem Klinik. Jika Anda menerima ini, konfigurasi SMTP berfungsi.', function ($message) use ($to) {
        $message->to($to)->subject('Uji email — Klinik');
    });

    $this->info("Email uji terkirim ke: {$to}");

    return 0;
})->purpose('Kirim email uji coba untuk memverifikasi SMTP');
