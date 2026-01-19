<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;
 
    protected $primaryKey = 'id';
    protected $table = 'users';

    protected $fillable = [
        'id_role',
        'email',
        'nama_opd',
        'nama_pic',
        'kontak_pic',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function role()
    {
        return $this->belongsTo(Role::class, 'id_role');
    }

    public function agendaPodcasts()
    {
        return $this->hasMany(AgendaPodcast::class, 'id_user');
    }

    public function agendaCCs()
    {
        return $this->hasMany(AgendaCC::class, 'id_user');
    }
}