<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produk extends Model {
    protected $fillable = [
        'user_id', 'judul', 'kategori', 'kondisi', 'harga', 'area', 'jarak',
        'deskripsi', 'nego', 'foto', 'status',
    ];

    protected $casts = [
        'foto' => 'array',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }
}