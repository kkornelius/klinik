<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RekamMedis extends Model
{
    protected $table = 'rekam_medis';

    protected $fillable = [
        'appointment_id', 'pasien_id', 'dokter_id',
        'subjective', 'objective', 'assessment', 'plan',
        'diagnosa_kode', 'resep',
    ];

    public function appointment(): BelongsTo { return $this->belongsTo(Appointment::class); }
    public function pasien(): BelongsTo { return $this->belongsTo(Pasien::class); }
    public function dokter(): BelongsTo { return $this->belongsTo(Dokter::class); }
}
