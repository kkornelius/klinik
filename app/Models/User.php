<?php
// =========================================================
// File: app/Models/User.php
// =========================================================
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = ['name', 'email', 'password', 'role', 'is_active'];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password'          => 'hashed',
        'is_active'         => 'boolean',
    ];

    public function dokter() { return $this->hasOne(Dokter::class); }
    public function pasien() { return $this->hasOne(Pasien::class); }

    public function isAdmin(): bool   { return $this->role === 'admin'; }
    public function isDokter(): bool  { return $this->role === 'dokter'; }
    public function isPasien(): bool  { return $this->role === 'pasien'; }
}
