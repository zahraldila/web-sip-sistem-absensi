<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Privilege extends Model
{
    protected $table = 'privilege';
    protected $primaryKey = 'privilege_id';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = true;

    protected $fillable = [
        'nama_privilege',
        'label_privilege',
        'kategori',
        'deskripsi',
    ];

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'role_privilege', 'privilege_id', 'role_id')
            ->withTimestamps();
    }
}
