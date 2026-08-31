<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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

    public function privileges(): BelongsToMany
    {
        return $this->belongsToMany(Privilege::class, 'role_privilege', 'role_id', 'privilege_id')
            ->withTimestamps();
    }

    public function hasPrivilege(string $namaPrivilege): bool
    {
        if (strtolower($this->nama_role) === 'super admin' || strtolower($this->nama_role) === 'admin') {
            return true;
        }

        return $this->privileges->contains('nama_privilege', $namaPrivilege);
    }
}
