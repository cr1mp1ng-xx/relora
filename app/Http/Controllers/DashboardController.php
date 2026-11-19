<?php

namespace App\Http\Controllers;

use App\Models\Wishlist;
use App\Support\FilterBarang;
use App\Support\Katalog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        [$jarak, $harga] = FilterBarang::baca($request);

        $tersimpan = array_map('intval', Wishlist::where('user_id', Auth::id())->pluck('produk_id')->all());

        $produk = array_map(
            fn (array $barang) => $barang + ['disimpan' => in_array($barang['id'], $tersimpan, true)],
            Katalog::semua($jarak, $harga)
        );

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