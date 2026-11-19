<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JanjiTemu extends Model
{
    protected $fillable = ['percakapan_id', 'lokasi_temu', 'waktu_temu', 'harga_disepakati', 'status'];

    protected $casts = [
        'waktu_temu'       => 'datetime',
        'harga_disepakati' => 'integer',
    ];

    public function percakapan(): BelongsTo
    {
        return $this->belongsTo(Percakapan::class);
    }
}