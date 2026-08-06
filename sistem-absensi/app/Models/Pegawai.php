<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Pegawai extends Model
{
    protected $table = 'pegawai';

    protected $fillable = [
        'nama',
        'email',
        'jabatan',
        'divisi',
    ];

    public function akun(): HasOne
    {
        return $this->hasOne(Akun::class, 'pegawai_id');
    }
}
