<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $primaryKey = 'id';
    protected $table = 'roles';
    
    protected $fillable = [
        'nama_role',
        'kode_role',
        'hak_akses',
    ];

    public function internalUsers()
    {
        return $this->hasMany(InternalUser::class, 'id');
    }
}
