<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CoachingBooking extends Model
{
    protected $primaryKey = 'id_coaching';
    protected $table = 'agenda_ccs';

    protected $fillable = [
        'id_user',
        'id_kalender',
        'tanggal',
        'layanan',
        'keterangan',
        'pic',
        'no_telp',
        'verifikasi',
        'status_verifikasi',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function kalender()
    {
        return $this->belongsTo(Kalender::class, 'id_kalender');
    }
}