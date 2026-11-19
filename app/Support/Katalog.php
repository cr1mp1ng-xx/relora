<?php

namespace App\Support;

use App\Models\Produk;

class Katalog
{
    public static function semua(int $jarak = 50, int $harga = 0): array
    {
        $query = Produk::with(['user:id,name', 'kategori:id,nama', 'foto'])
            ->where('status', 'tersedia')
            ->where('jarak_km', '<=', $jarak);

        if ($harga > 0) {
            $query->where('harga', '<=', $harga);
        }

        return $query->latest()->orderByDesc('id')->get()
            ->map(fn (Produk $p) => self::ubah($p))
            ->all();
    }

    public static function ubah(Produk $p): array
    {
        return [
            'id'         => $p->id,
            'nama'       => $p->judul,
            'kategori'   => $p->kategori->nama ?? 'Barang',
            'penjual'    => $p->user->name ?? 'Penjual',
            'penjual_id' => $p->user_id,
            'harga'      => (int) $p->harga,
            'lokasi'     => $p->area,
            'jarak'      => ((float) $p->jarak_km) . ' km',
            'jarak_km'   => (float) $p->jarak_km,
            'foto'       => $p->foto->first()->path ?? 'images/produk/jaket-denim.jpg',
            'cod'        => true,
            'nego'       => $p->nego,
            'deskripsi'  => $p->deskripsi,
        ];
    }
}