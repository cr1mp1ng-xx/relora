<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Produk extends Model
{
    protected $fillable = [
        'user_id', 'kategori_id', 'judul', 'harga', 'kondisi',
        'deskripsi', 'area', 'jarak_km', 'nego', 'status',
    ];

    protected $casts = [
        'harga'    => 'integer',
        'jarak_km' => 'decimal:1',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function kategori(): BelongsTo
    {
        return $this->belongsTo(Kategori::class);
    }

    public function foto(): HasMany
    {
        return $this->hasMany(ProdukFoto::class)->orderBy('urutan');
    }

    public function wishlists(): HasMany
    {
        return $this->hasMany(Wishlist::class);
    }

    public function percakapans(): HasMany
    {
        return $this->hasMany(Percakapan::class);
    }
}