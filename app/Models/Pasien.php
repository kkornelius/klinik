<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pasien extends Model
{
    protected $fillable = [
        'user_id', 'no_rm', 'tanggal_lahir', 'jenis_kelamin',
        'alamat', 'no_hp', 'golongan_darah', 'alergi',
    ];

    protected $casts = ['tanggal_lahir' => 'date'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class);
    }

    public function rekamMedis(): HasMany
    {
        return $this->hasMany(RekamMedis::class);
    }

    public function getUmurAttribute(): ?int
    {
        return $this->tanggal_lahir ? Carbon::parse($this->tanggal_lahir)->age : null;
    }

    public function getJenisKelaminLabelAttribute(): string
    {
        return match($this->jenis_kelamin) {
            'L' => 'Laki-laki',
            'P' => 'Perempuan',
            default => '-',
        };
    }
}
