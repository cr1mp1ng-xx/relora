<?php
namespace App\Http\Controllers;

use App\Support\FilterBarang;
use Illuminate\Http\Request;

class WishlistController extends Controller {
    public function index(Request $request) {
        [$jarak, $harga] = FilterBarang::baca($request);

        $wishlist = [
            [
                'id'       => 1,
                'kategori' => 'Fashion Pria',
                'nama'     => 'Jaket Denim Oversize Vintage',
                'harga'    => 185000,
                'lokasi'   => 'Lowokwaru, Malang',
                'jarak'    => '2.5 km',
                'jarak_km' => 2.5,
                'foto'     => 'images/jaket-denim.jpg',
                'cod'      => true,
                'disimpan' => now()->timestamp,
            ],
        ];

        return view('wishlist', [
            'wishlist'   => FilterBarang::terapkan($wishlist, $jarak, $harga),
            'area'       => 'Malang, Jawa Timur',
            'jarak'      => $jarak,
            'harga'      => $harga,
            'titikAcuan' => 'Klojen, Malang',
        ]);
    }
}
