<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProdukFoto extends Model
{
    public const UPDATED_AT = null; 

    protected $fillable = ['produk_id', 'path', 'urutan'];

    public function produk(): BelongsTo
    {
        return $this->belongsTo(Produk::class);
    }
}