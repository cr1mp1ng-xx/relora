<?php

namespace App\Http\Controllers;

use App\Support\FilterBarang;
use App\Support\Katalog;
use App\Support\WishlistFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller {
    public function index(Request $request) {
        [$jarak, $harga] = FilterBarang::baca($request);

        $tersimpan = WishlistFile::idUser(Auth::id());
        $produk = array_map(
            fn($barang) => $barang + ['disimpan' => in_array($barang['id'], $tersimpan, true)],
            Katalog::semua()
        );

        $produk = FilterBarang::terapkan($produk, $jarak, $harga);

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
