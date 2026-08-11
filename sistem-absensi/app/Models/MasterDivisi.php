<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MasterDivisi extends Model
{
    protected $table = 'master_divisi';
    protected $primaryKey = 'divisi_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'nama_divisi',
    ];

    public function pegawais(): HasMany
    {
        return $this->hasMany(Pegawai::class, 'divisi_id', 'divisi_id');
    }
}
