<?php
namespace App\Http\Controllers;

use App\Models\Produk;
use App\Support\FilterBarang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class DashboardController extends Controller {
    public function index(Request $request) {
        [$jarak, $harga] = FilterBarang::baca($request);

        $produk = [
            ['id' => 1, 'nama' => 'Jaket Denim Oversize Vintage', 'penjual' => 'Ahmad Rizky', 'harga' => 185000, 'lokasi' => 'Lowokwaru, Malang', 'jarak' => '2.5 km', 'jarak_km' => 2.5, 'foto' => 'images/produk/jaket-denim.jpg',   'cod' => true, 'disimpan' => true],
            ['id' => 2, 'nama' => 'Kaos Polo Katun Premium',       'penjual' => 'Budi Santoso', 'harga' => 75000,  'lokasi' => 'Sukun, Malang',     'jarak' => '4.1 km', 'jarak_km' => 4.1, 'foto' => 'images/produk/kaos-polo.jpg',      'cod' => true, 'disimpan' => false],
            ['id' => 3, 'nama' => 'Sepatu Sneaker Canvas Casual',  'penjual' => 'Dian Sastro',  'harga' => 210000, 'lokasi' => 'Klojen, Malang',    'jarak' => '1.2 km', 'jarak_km' => 1.2, 'foto' => 'images/produk/sepatu-sneaker.jpg', 'cod' => true, 'disimpan' => false],
            ['id' => 4, 'nama' => 'Sweater Hoodie Fleece Grey',    'penjual' => 'Eko Prasetyo', 'harga' => 120000, 'lokasi' => 'Blimbing, Malang',  'jarak' => '7.8 km', 'jarak_km' => 7.8, 'foto' => 'images/produk/sweater-hoodie.jpg', 'cod' => true, 'disimpan' => false],
        ];

        $dariDb = [];
        if (Schema::hasTable('produks')) {
            $dariDb = Produk::with('user')
                ->where('status', 'aktif')
                ->latest()
                ->get()
                ->map(fn($p) => [
                    'id'       => $p->id,
                    'nama'     => $p->judul,
                    'penjual'  => $p->user->name ?? 'Penjual',
                    'harga'    => $p->harga,
                    'lokasi'   => $p->area,
                    'jarak'    => ((float) $p->jarak) . ' km',
                    'jarak_km' => (float) $p->jarak,
                    'foto'     => isset($p->foto[0]) ? 'storage/' . $p->foto[0] : 'images/produk/jaket-denim.jpg',
                    'cod'      => true,
                    'disimpan' => false,
                ])
                ->all();
        }

        $produk = FilterBarang::terapkan(array_merge($dariDb, $produk), $jarak, $harga);

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
