<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Akun extends Authenticatable
{
    use Notifiable;

    protected $table = 'akun';
    protected $primaryKey = 'akun_id';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = false;

    protected $fillable = [
        'username',
        'password',
        'role',
        'pegawai_id',
    ];

    protected $hidden = [
        'password',
    ];

    // Note: password hashing is handled in service/controller using Hash::make().
    // Keep casts empty to avoid automatic double-hashing.
    protected $casts = [];

    /**
     * Disable remember-token functionality karena kolom `remember_token`
     * tidak ada di tabel `akun`. Fitur "Ingat Saya" ditangani via session.
     */
    public function getRememberToken(): string
    {
        return '';
    }

    public function setRememberToken($value): void
    {
        // Sengaja dikosongkan — tidak ada kolom remember_token di tabel akun.
    }

    public function getRememberTokenName(): string
    {
        return '';
    }

    public function pegawai(): BelongsTo
    {
        return $this->belongsTo(Pegawai::class, 'pegawai_id', 'pegawai_id');
    }
}
