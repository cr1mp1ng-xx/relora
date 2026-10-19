<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Support\FilterBarang;
use App\Support\ProdukFile;
use Illuminate\Http\Request;

class DashboardController extends Controller {
    public function index(Request $request) {
        [$jarak, $harga] = FilterBarang::baca($request);

        $produk = [
            ['id' => 1, 'nama' => 'Jaket Denim Oversize Vintage', 'penjual' => 'Ahmad Rizky', 'harga' => 185000, 'lokasi' => 'Lowokwaru, Malang', 'jarak' => '2.5 km', 'jarak_km' => 2.5, 'foto' => 'images/produk/jaket-denim.jpg',   'cod' => true, 'disimpan' => true],
            ['id' => 2, 'nama' => 'Kaos Polo Katun Premium',       'penjual' => 'Budi Santoso', 'harga' => 75000,  'lokasi' => 'Sukun, Malang',     'jarak' => '4.1 km', 'jarak_km' => 4.1, 'foto' => 'images/produk/kaos-polo.jpg',      'cod' => true, 'disimpan' => false],
            ['id' => 3, 'nama' => 'Sepatu Sneaker Canvas Casual',  'penjual' => 'Dian Sastro',  'harga' => 210000, 'lokasi' => 'Klojen, Malang',    'jarak' => '1.2 km', 'jarak_km' => 1.2, 'foto' => 'images/produk/sepatu-sneaker.jpg', 'cod' => true, 'disimpan' => false],
            ['id' => 4, 'nama' => 'Sweater Hoodie Fleece Grey',    'penjual' => 'Eko Prasetyo', 'harga' => 120000, 'lokasi' => 'Blimbing, Malang',  'jarak' => '7.8 km', 'jarak_km' => 7.8, 'foto' => 'images/produk/sweater-hoodie.jpg', 'cod' => true, 'disimpan' => false],
        ];

        $semua = ProdukFile::semua();
        usort($semua, fn($a, $b) => $b['dibuat'] <=> $a['dibuat']);
        $namaPenjual = User::whereIn('id', array_column($semua, 'user_id'))->pluck('name', 'id');

        $dariFile = [];
        foreach ($semua as $p) {
            if ($p['status'] !== 'tersedia') {
                continue;
            }

            $dariFile[] = [
                'id'       => 'u' . $p['id'],
                'nama'     => $p['judul'],
                'penjual'  => $namaPenjual[$p['user_id']] ?? 'Penjual',
                'harga'    => $p['harga'],
                'lokasi'   => $p['area'],
                'jarak'    => ((float) $p['jarak']) . ' km',
                'jarak_km' => (float) $p['jarak'],
                'foto'     => $p['foto'][0] ?? 'images/produk/jaket-denim.jpg',
                'cod'      => true,
                'disimpan' => false,
            ];
        }

        $produk = FilterBarang::terapkan(array_merge($dariFile, $produk), $jarak, $harga);

        return view('dashboard', [
            'produk'     => $produk,
            'area'       => 'Malang, Jawa Timur',
            'wilayah'    => 'Malang Kota & Sekitar',
            'radius'     => $jarak,
            'jarak'      => $jarak,
            'harga'      => $harga,
            'titikAcuan' => 'Klojen, Malang',
        ]);
    }
}
