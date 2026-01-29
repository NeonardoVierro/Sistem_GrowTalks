<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kalender extends Model
{
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
    
    public function podcastBookings(): HasMany
    {
        return $this->hasMany(PodcastBooking::class, 'id_kalender');
    }
}