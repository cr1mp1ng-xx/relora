<?php

namespace App\Support;

use App\Models\User;

class Katalog {
    private static function contoh(): array {
        return [
            ['id' => 'c1', 'nama' => 'Jaket Denim Oversize Vintage', 'kategori' => 'Fashion Pria', 'penjual' => 'Ahmad Rizky', 'harga' => 185000, 'lokasi' => 'Lowokwaru, Malang', 'jarak' => '2.5 km', 'jarak_km' => 2.5, 'foto' => 'images/produk/jaket-denim.jpg',   'cod' => true],
            ['id' => 'c2', 'nama' => 'Kaos Polo Katun Premium',       'kategori' => 'Fashion Pria', 'penjual' => 'Budi Santoso', 'harga' => 75000,  'lokasi' => 'Sukun, Malang',     'jarak' => '4.1 km', 'jarak_km' => 4.1, 'foto' => 'images/produk/kaos-polo.jpg',      'cod' => true],
            ['id' => 'c3', 'nama' => 'Sepatu Sneaker Canvas Casual',  'kategori' => 'Sepatu',       'penjual' => 'Dian Sastro',  'harga' => 210000, 'lokasi' => 'Klojen, Malang',    'jarak' => '1.2 km', 'jarak_km' => 1.2, 'foto' => 'images/produk/sepatu-sneaker.jpg', 'cod' => true],
            ['id' => 'c4', 'nama' => 'Sweater Hoodie Fleece Grey',    'kategori' => 'Fashion Pria', 'penjual' => 'Eko Prasetyo', 'harga' => 120000, 'lokasi' => 'Blimbing, Malang',  'jarak' => '7.8 km', 'jarak_km' => 7.8, 'foto' => 'images/produk/sweater-hoodie.jpg', 'cod' => true],
        ];
    }

    public static function semua(): array {
        $file = ProdukFile::semua();
        usort($file, fn ($a, $b) => ($b['dibuat'] ?? 0) <=> ($a['dibuat'] ?? 0));

        $namaPenjual = User::whereIn('id', array_column($file, 'user_id'))->pluck('name', 'id');

        $upload = [];
        foreach ($file as $p) {
            if (! in_array($p['status'] ?? '', ['tersedia', 'aktif'], true)) {
                continue;
            }

            $upload[] = [
                'id'       => 'u' . $p['id'],
                'nama'     => $p['judul'],
                'kategori' => $p['kategori'] ?? null ?: 'Barang',
                'penjual'  => $namaPenjual[$p['user_id']] ?? 'Penjual',
                'harga'    => (int) $p['harga'],
                'lokasi'   => $p['area'],
                'jarak'    => ((float) $p['jarak']) . ' km',
                'jarak_km' => (float) $p['jarak'],
                'foto'     => $p['foto'][0] ?? 'images/produk/jaket-denim.jpg',
                'cod'      => true,
            ];
        }

        return array_merge($upload, self::contoh());
    }

    public static function cari(string $id): ?array {
        foreach (self::semua() as $barang) {
            if ($barang['id'] === $id) {
                return $barang;
            }
        }

        return null;
    }
}