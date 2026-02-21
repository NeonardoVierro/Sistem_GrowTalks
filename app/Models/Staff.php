<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    protected $table = 'staffs';

    protected $fillable = [
        'nama',
        'role', // 'host' or 'coach'
        'no_hp',
        'bidang',
    ];
}