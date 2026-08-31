<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Role extends Model
{
    protected $table = 'role';
    protected $primaryKey = 'role_id';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = true;

    protected $fillable = [
        'nama_role',
    ];

    public function akun(): HasMany
    {
        return $this->hasMany(Akun::class, 'role_id', 'role_id');
    }
}
