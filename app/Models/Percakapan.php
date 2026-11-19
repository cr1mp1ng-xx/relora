<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Percakapan extends Model
{
    protected $fillable = ['produk_id', 'pembeli_id', 'penjual_id', 'status'];

    public function produk(): BelongsTo
    {
        return $this->belongsTo(Produk::class);
    }

    public function pembeli(): BelongsTo
    {
        return $this->belongsTo(User::class, 'pembeli_id');
    }

    public function penjual(): BelongsTo
    {
        return $this->belongsTo(User::class, 'penjual_id');
    }

    public function pesan(): HasMany
    {
        return $this->hasMany(Pesan::class)->orderBy('id');
    }

    public function janjiTemu(): HasOne
    {
        return $this->hasOne(JanjiTemu::class);
    }
}