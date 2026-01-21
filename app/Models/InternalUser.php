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

    public function isAdmin()
    {
        return $this->role->kode_role === 'admin';
    }

    public function isVerifikatorPodcast()
    {
        return $this->role->kode_role === 'verifikator_podcast';
    }

    public function isVerifikatorCoaching()
    {
        return $this->role->kode_role === 'verifikator_coaching';
    }

    public function podcastVerifications()
    {
        return $this->hasMany(AgendaPodcast::class, 'id_verifikator');
    }

    public function coachingVerifications()
    {
        return $this->hasMany(AgendaCC::class, 'id_verifikator');
    }
}