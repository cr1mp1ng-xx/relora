<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProdukController extends Controller {
    public function create() {
        return view('jual', ['area' => 'Malang, Jawa Timur']);
    }

    public function store(Request $request) {
        $upload = $request->input('aksi') !== 'draf';

        $request->validate([
            'judul'     => ['required', 'string', 'max:100'],
            'harga'     => ['required', 'integer', 'min:1000'],
            'area'      => ['required', 'string', 'max:100'],
            'jarak'     => ['required', 'numeric', 'min:0', 'max:50'],
            'nego'      => ['required', 'in:pas,nego'],
            'deskripsi' => $upload ? ['required', 'string', 'min:30'] : ['nullable', 'string'],
            'foto'      => $upload ? ['required', 'array', 'min:1', 'max:5'] : ['nullable', 'array', 'max:5'],
            'foto.*'    => ['image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ], [
            'judul.required'     => 'Judul barang wajib diisi.',
            'harga.required'     => 'Harga jual wajib diisi.',
            'harga.integer'      => 'Harga harus berupa angka.',
            'harga.min'          => 'Harga minimal Rp 1.000.',
            'area.required'      => 'Area COD / kecamatan wajib diisi.',
            'jarak.required'     => 'Estimasi jarak wajib diisi.',
            'jarak.numeric'      => 'Estimasi jarak harus berupa angka.',
            'jarak.max'          => 'Estimasi jarak maksimal 50 km.',
            'deskripsi.required' => 'Deskripsi barang wajib diisi.',
            'deskripsi.min'      => 'Deskripsi minimal 30 karakter.',
            'foto.required'      => 'Tambahkan minimal 1 foto produk.',
            'foto.max'           => 'Maksimal 5 foto.',
            'foto.*.image'       => 'File harus berupa gambar.',
            'foto.*.mimes'       => 'Format foto harus JPG atau PNG.',
            'foto.*.max'         => 'Ukuran tiap foto maksimal 2 MB.',
        ]);

        $paths = [];
        foreach ($request->file('foto', []) as $file) {
            $paths[] = $file->store('produk', 'public');
        }

        Produk::create([
            'user_id'   => Auth::id(),
            'judul'     => $request->judul,
            'harga'     => $request->harga,
            'area'      => $request->area,
            'jarak'     => $request->jarak,
            'deskripsi' => $request->deskripsi,
            'nego'      => $request->nego,
            'foto'      => $paths,
            'status'    => $upload ? 'aktif' : 'draf',
        ]);

        if ($upload) {
            return redirect()->route('dashboard')->with('status', 'Produk berhasil diupload dan tampil di Beranda.');
        }

        return redirect()->route('jual')->with('status', 'Draf produk berhasil disimpan.');
    }
}