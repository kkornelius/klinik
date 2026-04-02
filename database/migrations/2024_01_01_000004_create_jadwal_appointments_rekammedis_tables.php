<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jadwal_dokters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dokter_id')->constrained('dokters')->onDelete('cascade');
            $table->enum('hari', ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu']);
            $table->time('jam_mulai');
            $table->time('jam_selesai');
            $table->unsignedInteger('kuota')->default(10);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pasien_id')->constrained('pasiens')->onDelete('cascade');
            $table->foreignId('dokter_id')->constrained('dokters')->onDelete('cascade');
            $table->foreignId('jadwal_id')->constrained('jadwal_dokters')->onDelete('cascade');
            $table->date('tanggal_appointment');
            $table->string('keluhan');
            $table->enum('status', ['menunggu', 'dikonfirmasi', 'selesai', 'dibatalkan'])->default('menunggu');
            $table->string('no_antrian', 10)->nullable();
            $table->text('catatan')->nullable();
            $table->timestamps();
        });

        Schema::create('rekam_medis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('appointment_id')->constrained('appointments')->onDelete('cascade');
            $table->foreignId('pasien_id')->constrained('pasiens')->onDelete('cascade');
            $table->foreignId('dokter_id')->constrained('dokters')->onDelete('cascade');
            $table->text('subjective');     // S — Keluhan pasien
            $table->text('objective');      // O — Hasil pemeriksaan fisik
            $table->text('assessment');     // A — Diagnosa dokter
            $table->text('plan');           // P — Rencana pengobatan
            $table->string('diagnosa_kode', 20)->nullable(); // Kode ICD-10
            $table->text('resep')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rekam_medis');
        Schema::dropIfExists('appointments');
        Schema::dropIfExists('jadwal_dokters');
    }
};
