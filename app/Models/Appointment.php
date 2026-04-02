<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Appointment extends Model
{
    protected $fillable = [
        'pasien_id', 'dokter_id', 'jadwal_id',
        'tanggal_appointment', 'keluhan', 'status',
        'no_antrian', 'catatan',
    ];

    protected $casts = ['tanggal_appointment' => 'date'];

    public function pasien(): BelongsTo { return $this->belongsTo(Pasien::class); }
    public function dokter(): BelongsTo { return $this->belongsTo(Dokter::class); }
    public function jadwal(): BelongsTo { return $this->belongsTo(JadwalDokter::class, 'jadwal_id'); }
    public function rekamMedis(): HasOne { return $this->hasOne(RekamMedis::class); }

    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'menunggu'     => 'warning',
            'dikonfirmasi' => 'info',
            'selesai'      => 'success',
            'dibatalkan'   => 'danger',
            default        => 'secondary',
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return ucfirst($this->status);
    }

    public function canBeCancelled(): bool
    {
        return $this->status === 'menunggu';
    }
}
