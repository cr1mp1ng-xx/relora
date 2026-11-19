<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\Wishlist;
use App\Support\FilterBarang;
use App\Support\Katalog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    public function index(Request $request)
    {
        [$jarak, $harga] = FilterBarang::baca($request);

        $daftar = Wishlist::with(['produk.user:id,name', 'produk.kategori:id,nama', 'produk.foto'])
            ->where('user_id', Auth::id())
            ->whereHas('produk', function ($q) use ($jarak, $harga) {
                $q->where('status', 'tersedia')->where('jarak_km', '<=', $jarak);
                if ($harga > 0) {
                    $q->where('harga', '<=', $harga);
                }
            })
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->get();

        $wishlist = $daftar->map(
            fn (Wishlist $w) => Katalog::ubah($w->produk) + ['disimpan' => $w->created_at->timestamp]
        )->all();

        return view('wishlist', [
            'wishlist'   => $wishlist,
            'area'       => 'Malang, Jawa Timur',
            'jarak'      => $jarak,
            'harga'      => $harga,
            'titikAcuan' => 'Klojen, Malang',
        ]);
    }

    public function toggle(Produk $item)
    {
        $ada = Wishlist::where('user_id', Auth::id())->where('produk_id', $item->id)->first();

        if ($ada) {
            $ada->delete();
            $disimpan = false;
        } else {
            abort_unless($item->status === 'tersedia', 404);
            Wishlist::create(['user_id' => Auth::id(), 'produk_id' => $item->id]);
            $disimpan = true;
        }

        return response()->json([
            'ok'       => true,
            'disimpan' => $disimpan,
            'jumlah'   => Wishlist::where('user_id', Auth::id())->count(),
        ]);
    }
}