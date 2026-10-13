<?php
namespace App\Http\Controllers;

class WishlistController extends Controller {
    public function index() {
        $wishlist = [
            [
                'id'       => 1,
                'kategori' => 'Fashion Pria',
                'nama'     => 'Jaket Denim Oversize Vintage',
                'harga'    => 185000,
                'lokasi'   => 'Lowokwaru, Malang',
                'jarak'    => '2.5 km',
                'foto'     => 'images/jaket-denim.jpg',
                'cod'      => true,
                'disimpan' => now()->timestamp,
            ],
        ];

        return view('wishlist', [
            'wishlist' => $wishlist,
            'area'     => 'Malang, Jawa Timur',
        ]);
    }
}