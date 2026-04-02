<?php

namespace Database\Seeders;

use App\Models\Dokter;
use App\Models\JadwalDokter;
use App\Models\Pasien;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── Admin ─────────────────────────────────────────────
        User::create([
            'name'      => 'Administrator',
            'email'     => 'admin@klinik.test',
            'password'  => Hash::make('password'),
            'role'      => 'admin',
            'is_active' => true,
        ]);

        // ── Dokter ─────────────────────────────────────────────
        $dokterData = [
            [
                'name'         => 'dr. Andi Santoso',
                'email'        => 'andi@klinik.test',
                'spesialisasi' => 'Umum',
                'no_str'       => 'STR-001-2024',
                'no_hp'        => '08111234001',
                'bio'          => 'Dokter umum berpengalaman lebih dari 10 tahun.',
            ],
            [
                'name'         => 'dr. Siti Rahayu, Sp.A',
                'email'        => 'siti@klinik.test',
                'spesialisasi' => 'Anak',
                'no_str'       => 'STR-002-2024',
                'no_hp'        => '08111234002',
                'bio'          => 'Spesialis anak dengan pengalaman 8 tahun.',
            ],
            [
                'name'         => 'dr. Budi Kurniawan, Sp.PD',
                'email'        => 'budi@klinik.test',
                'spesialisasi' => 'Penyakit Dalam',
                'no_str'       => 'STR-003-2024',
                'no_hp'        => '08111234003',
                'bio'          => 'Spesialis penyakit dalam.',
            ],
        ];

        $jadwalTemplate = [
            [
                ['hari' => 'Senin',  'jam_mulai' => '08:00', 'jam_selesai' => '12:00', 'kuota' => 15],
                ['hari' => 'Rabu',   'jam_mulai' => '08:00', 'jam_selesai' => '12:00', 'kuota' => 15],
                ['hari' => 'Jumat',  'jam_mulai' => '13:00', 'jam_selesai' => '17:00', 'kuota' => 12],
            ],
            [
                ['hari' => 'Selasa', 'jam_mulai' => '09:00', 'jam_selesai' => '13:00', 'kuota' => 12],
                ['hari' => 'Kamis',  'jam_mulai' => '09:00', 'jam_selesai' => '13:00', 'kuota' => 12],
                ['hari' => 'Sabtu',  'jam_mulai' => '08:00', 'jam_selesai' => '11:00', 'kuota' => 10],
            ],
            [
                ['hari' => 'Senin',  'jam_mulai' => '14:00', 'jam_selesai' => '18:00', 'kuota' => 10],
                ['hari' => 'Kamis',  'jam_mulai' => '14:00', 'jam_selesai' => '18:00', 'kuota' => 10],
            ],
        ];

        foreach ($dokterData as $i => $data) {
            $user = User::create([
                'name'      => $data['name'],
                'email'     => $data['email'],
                'password'  => Hash::make('password'),
                'role'      => 'dokter',
                'is_active' => true,
            ]);

            $dokter = Dokter::create([
                'user_id'      => $user->id,
                'spesialisasi' => $data['spesialisasi'],
                'no_str'       => $data['no_str'],
                'no_hp'        => $data['no_hp'],
                'bio'          => $data['bio'],
            ]);

            foreach ($jadwalTemplate[$i] as $jadwal) {
                JadwalDokter::create([
                    'dokter_id'   => $dokter->id,
                    'hari'        => $jadwal['hari'],
                    'jam_mulai'   => $jadwal['jam_mulai'],
                    'jam_selesai' => $jadwal['jam_selesai'],
                    'kuota'       => $jadwal['kuota'],
                ]);
            }
        }

        // ── Pasien ─────────────────────────────────────────────
        $pasienData = [
            ['name' => 'Rina Wulandari',  'email' => 'rina@mail.test',  'lahir' => '1990-05-12', 'jk' => 'P'],
            ['name' => 'Dimas Prasetyo',  'email' => 'dimas@mail.test', 'lahir' => '1985-09-25', 'jk' => 'L'],
            ['name' => 'Nur Halimah',     'email' => 'nur@mail.test',   'lahir' => '2000-01-30', 'jk' => 'P'],
        ];

        foreach ($pasienData as $i => $data) {
            $user = User::create([
                'name'      => $data['name'],
                'email'     => $data['email'],
                'password'  => Hash::make('password'),
                'role'      => 'pasien',
                'is_active' => true,
            ]);

            Pasien::create([
                'user_id'       => $user->id,
                'no_rm'         => 'RM-' . str_pad($user->id, 6, '0', STR_PAD_LEFT),
                'tanggal_lahir' => $data['lahir'],
                'jenis_kelamin' => $data['jk'],
                'no_hp'         => '0812000000' . ($i + 1),
                'alamat'        => 'Jl. Contoh No. ' . (($i + 1) * 10) . ', Jakarta',
            ]);
        }
    }
}
