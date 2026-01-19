<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class InternalUser extends Authenticatable
{
    use Notifiable;

    protected $primaryKey = 'id';
    protected $table = 'internal_users';
    
    protected $fillable = [
        'id_role',
        'nama_user',
        'email',
        'password',
        'jabatan',
        'no_hp',
        'status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function role()
    {
        return $this->belongsTo(Role::class, 'id_role');
    }
}