<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CoachingBooking extends Model
{
    protected $primaryKey = 'id';
    protected $table = 'agenda_ccs';

    protected $fillable = [
        'id_user',
        'id_verifikator',
        'id_kalender',
        'tanggal',
        'layanan',
        'keterangan',
        'pic',
        'no_telp',
        'verifikasi',
        'status_verifikasi',
        'coach',
        'waktu',
        'dokumentasi_path',
        'catatan',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function verifikator()
    {
        return $this->belongsTo(InternalUser::class, 'id_verifikator');
    }

    public function kalender()
    {
        return $this->belongsTo(Kalender::class, 'id_kalender');
    }
}