<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PodcastBooking extends Model
{
    protected $primaryKey = 'id_podcast';
    protected $table = 'podcasts';

    protected $fillable = [
        'id_user',
        'id_kalender',
        'tanggal',
        'nama_opd',
        'nama_pic',
        'keterangan',
        'narasumber',
        'verifikasi',
        'status_verifikasi',
        'host',
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