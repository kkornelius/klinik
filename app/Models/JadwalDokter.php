<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JadwalDokter extends Model
{
    protected $table = 'jadwal_dokters';

    protected $fillable = ['dokter_id', 'hari', 'jam_mulai', 'jam_selesai', 'kuota', 'is_active'];

    protected $casts = ['is_active' => 'boolean'];

    public function dokter(): BelongsTo
    {
        return $this->belongsTo(Dokter::class);
    }

    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class, 'jadwal_id');
    }

    /**
     * Sisa kuota pada tanggal tertentu (default: hari ini).
     */
    public function sisaKuota(\DateTime|string|null $tanggal = null): int
    {
        $tanggal ??= today();
        $booked = $this->appointments()
            ->whereDate('tanggal_appointment', $tanggal)
            ->whereIn('status', ['menunggu', 'dikonfirmasi'])
            ->count();
        return max(0, $this->kuota - $booked);
    }
}
