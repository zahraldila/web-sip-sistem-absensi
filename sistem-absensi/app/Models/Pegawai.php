<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Pegawai extends Model
{
    protected $table = 'pegawai';
    protected $primaryKey = 'pegawai_id';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = false;

    protected $fillable = [
        'pegawai_id',
        'nip',
        'nama_pegawai',
        'email',
        'no_handphone',
        'jabatan',
        'divisi',
    ];

    public function akun(): HasOne
    {
        return $this->hasOne(Akun::class, 'pegawai_id', 'pegawai_id');
    }
}
