<?php

namespace App\Http\Controllers;

use App\Support\FilterBarang;
use App\Support\Katalog;
use App\Support\WishlistFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller {
    public function index(Request $request) {
        [$jarak, $harga] = FilterBarang::baca($request);

        $katalog = [];
        foreach (Katalog::semua() as $barang) {
            $katalog[$barang['id']] = $barang;
        }

        $wishlist = [];
        foreach (WishlistFile::milik(Auth::id()) as $entri) {
            if (isset($katalog[$entri['id']])) {
                $wishlist[] = $katalog[$entri['id']] + ['disimpan' => $entri['waktu']];
            }
        }

        return view('wishlist', [
            'wishlist'   => FilterBarang::terapkan($wishlist, $jarak, $harga),
            'area'       => 'Malang, Jawa Timur',
            'jarak'      => $jarak,
            'harga'      => $harga,
            'titikAcuan' => 'Klojen, Malang',
        ]);
    }

    public function toggle(string $item) {
        $sudahAda = in_array($item, WishlistFile::idUser(Auth::id()), true);

        abort_unless($sudahAda || Katalog::cari($item), 404);

        $disimpan = WishlistFile::toggle(Auth::id(), $item);

        return response()->json([
            'ok'       => true,
            'disimpan' => $disimpan,
            'jumlah'   => count(WishlistFile::idUser(Auth::id())),
        ]);
    }
}
