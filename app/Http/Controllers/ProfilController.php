<?php

namespace App\Http\Controllers;

use App\Models\Pesan;
use App\Models\Produk;
use Illuminate\Support\Facades\Auth;

class ProfilController extends Controller
{
    public function index()
    {
        $user = Auth::user(); 

        $milik = Produk::where('user_id', Auth::id());

        $chatBaru = Pesan::where('pengirim', 'penjual')
            ->whereNull('dibaca_at')
            ->whereHas('percakapan', fn ($q) => $q->where('pembeli_id', Auth::id()))
            ->count();

        return view('profil', [
            'user'         => $user,
            'area'         => 'Malang, Jawa Timur',
            'terjual'      => (clone $milik)->where('status', 'terjual')->count(),
            'areaCod'      => 'Kota Malang',
            'bio'          => [
                'good items, better prices',
                'preloved original, masih bagus & worth it!',
                'COD area Kota Malang',
            ],
            'jumlahBarang' => (clone $milik)->where('status', '!=', 'arsip')->count(),
            'chatBaru'     => $chatBaru,
        ]);
    }
}
