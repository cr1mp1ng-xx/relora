<?php
namespace App\Http\Controllers;

class DashboardController extends Controller {
    public function index() {
        $produk = [
            ['id' => 1, 'nama' => 'Jaket Denim Oversize Vintage', 'penjual' => 'Ahmad Rizky', 'harga' => 185000, 'lokasi' => 'Lowokwaru, Malang', 'jarak' => '2.5 km', 'foto' => 'images/produk/jaket-denim.jpg',    'cod' => true, 'disimpan' => true],
            ['id' => 2, 'nama' => 'Kaos Polo Katun Premium',       'penjual' => 'Budi Santoso', 'harga' => 75000,  'lokasi' => 'Sukun, Malang',     'jarak' => '4.1 km', 'foto' => 'images/produk/kaos-polo.jpg',       'cod' => true, 'disimpan' => false],
            ['id' => 3, 'nama' => 'Sepatu Sneaker Canvas Casual',  'penjual' => 'Dian Sastro',  'harga' => 210000, 'lokasi' => 'Klojen, Malang',    'jarak' => '1.2 km', 'foto' => 'images/produk/sepatu-sneaker.jpg',  'cod' => true, 'disimpan' => false],
            ['id' => 4, 'nama' => 'Sweater Hoodie Fleece Grey',    'penjual' => 'Eko Prasetyo', 'harga' => 120000, 'lokasi' => 'Blimbing, Malang',  'jarak' => '7.8 km', 'foto' => 'images/produk/sweater-hoodie.jpg',  'cod' => true, 'disimpan' => false],
        ];

        return view('dashboard', [
            'produk'  => $produk,
            'area'    => 'Malang, Jawa Timur',
            'wilayah' => 'Malang Kota & Sekitar',
            'radius'  => 50,
        ]);
    }
}