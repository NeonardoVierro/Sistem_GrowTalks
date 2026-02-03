<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PodcastBooking extends Model
{
    protected $primaryKey = 'id';
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

    public function kalender()
    {
        return $this->belongsTo(Kalender::class, 'id_kalender');
    }

    public function verifikator()
    {
        return $this->belongsTo(InternalUser::class, 'id_verifikator');
    }

    // Scope helper for statuses
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

    public function scopeForUser($query, $userId)
    {
        return $query->where('id_user', $userId);
    }

    public function getKodeBookingAttribute()
    {
        return $this->tanggal->format('Ymd') . '-' . str_pad($this->id, 4, '0', STR_PAD_LEFT);
    }
}
