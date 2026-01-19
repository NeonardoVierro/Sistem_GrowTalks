<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kalender extends Model
{
    protected $primaryKey = 'id_kalender';
    protected $table = 'kalenders';

    protected $fillable = [
        'tanggal_kalender',
        'waktu',
        'sudah_dibooking',
        'jenis_agenda',
        'id_agenda',
    ];

    protected $casts = [
        'tanggal_kalender' => 'date',
        'sudah_dibooking' => 'boolean',
    ];
}