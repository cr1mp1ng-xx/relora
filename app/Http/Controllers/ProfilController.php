<?php

namespace App\Http\Controllers;

use App\Support\ProdukFile;
use Illuminate\Support\Facades\Auth;

class ProfilController extends Controller {
    public function index() {
        $user = Auth::user();

        $milik = ProdukFile::milikUser(Auth::id());
        $jumlahBarang = count(array_filter($milik, fn ($p) => $p['status'] !== 'arsip'));
        $terjual      = count(array_filter($milik, fn ($p) => $p['status'] === 'terjual'));

        return view('profil', [
            'user'         => $user,
            'area'         => 'Malang, Jawa Timur',
            'terjual'      => $terjual,
            'areaCod'      => 'Kota Malang',
            'bio'          => [
                'good items, better prices',
                'preloved original, masih bagus & worth it!',
                'COD area Kota Malang',
            ],
            'jumlahBarang' => $jumlahBarang + 3,
            'chatBaru'     => 2,
        ]);
    }
}
