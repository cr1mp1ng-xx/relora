<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pesan extends Model
{
    public const UPDATED_AT = null; 

    protected $touches = ['percakapan'];

    protected $fillable = ['percakapan_id', 'pengirim', 'tipe', 'isi', 'dibaca_at'];

    protected $casts = [
        'dibaca_at' => 'datetime',
    ];

    public function percakapan(): BelongsTo
    {
        return $this->belongsTo(Percakapan::class);
    }
}