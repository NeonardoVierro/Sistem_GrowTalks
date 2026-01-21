<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PodcastBooking extends Model
{
    protected $primaryKey = 'id';
    protected $table = 'podcasts';

    protected $fillable = [
        'id_user',
        'id_verifikator',
        'id_kalender',
        'tanggal',
        'nama_opd',
        'nama_pic',
        'keterangan',
        'narasumber',
        'verifikasi',
        'status_verifikasi',
        'host',
        'waktu',
        'cover_path',
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
        return $this->belongsTo(Kalender::class, 'id_kalender', 'id');
    }

    // Scope untuk status
    public function scopePending($query)
    {
        return $query->where('status_verifikasi', 'pending');
    }
    
    public function scopeApproved($query)
    {
        return $query->where('status_verifikasi', 'disetujui');
    }
    
    public function scopeRejected($query)
    {
        return $query->where('status_verifikasi', 'ditolak');
    }
    
    public function scopeRescheduled($query)
    {
        return $query->where('status_verifikasi', 'penjadwalan ulang');
    }
}