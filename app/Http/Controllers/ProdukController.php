<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ProdukController extends Controller
{
    public function index()
    {
        $milik = Produk::with('foto')
            ->where('user_id', Auth::id())
            ->where('status', '!=', 'arsip')
            ->latest()
            ->orderByDesc('id')
            ->get();

        $barang = $milik->map(fn (Produk $p) => [
            'id'     => $p->id,
            'judul'  => $p->judul,
            'harga'  => (int) $p->harga,
            'status' => $p->status,
            'foto'   => $p->foto->first()->path ?? null,
            'waktu'  => $p->created_at->timestamp,
        ])->all();

        $jumlah = ['semua' => count($barang)];
        foreach (['tersedia', 'dipesan', 'terjual'] as $status) {
            $jumlah[$status] = $milik->where('status', $status)->count();
        }

        return view('barang-saya', [
            'barang' => $barang,
            'jumlah' => $jumlah,
            'area'   => 'Malang, Jawa Timur',
        ]);
    }

    public function create()
    {
        return view('jual', ['area' => 'Malang, Jawa Timur']);
    }

    public function store(Request $request)
    {
        $upload = $request->input('aksi') !== 'draf';

        $request->validate([
            'judul'     => ['required', 'string', 'max:100'],
            'harga'     => ['required', 'integer', 'min:1000'],
            'area'      => ['required', 'string', 'max:100'],
            'jarak'     => ['required', 'numeric', 'min:0', 'max:50'],
            'nego'      => ['required', 'in:pas,nego'],
            'deskripsi' => $upload ? ['required', 'string', 'min:30', 'max:500'] : ['nullable', 'string', 'max:500'],
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
            'deskripsi.max'      => 'Deskripsi maksimal 500 karakter.',
            'foto.required'      => 'Tambahkan minimal 1 foto produk.',
            'foto.max'           => 'Maksimal 5 foto.',
            'foto.*.image'       => 'File harus berupa gambar.',
            'foto.*.mimes'       => 'Format foto harus JPG atau PNG.',
            'foto.*.max'         => 'Ukuran tiap foto maksimal 2 MB.',
        ]);

        $paths = [];
        foreach ($request->file('foto', []) as $file) {
            $nama = Str::random(24) . '.' . $file->extension();
            $file->move(public_path('uploads/produk'), $nama);
            $paths[] = 'uploads/produk/' . $nama;
        }

        DB::transaction(function () use ($request, $upload, $paths) {
            $produk = Produk::create([
                'user_id'   => Auth::id(),
                'judul'     => $request->judul,
                'harga'     => (int) $request->harga,
                'kondisi'   => 'bekas',
                'deskripsi' => $request->deskripsi,
                'area'      => $request->area,
                'jarak_km'  => (float) $request->jarak,
                'nego'      => $request->nego,
                'status'    => $upload ? 'tersedia' : 'draf',
            ]);

            foreach ($paths as $urutan => $path) {
                $produk->foto()->create(['path' => $path, 'urutan' => $urutan]);
            }
        });

        if ($upload) {
            return redirect()->route('dashboard')->with('status', 'Produk berhasil diupload dan tampil di Beranda.');
        }

        return redirect()->route('barang.saya')->with('status', 'Draf produk berhasil disimpan.');
    }

    public function edit(Produk $produk)
    {
        $this->pastikanPemilik($produk);
        $produk->load('kategori');

        return view('edit-barang', [
            'produk' => (object) [
                'id'        => $produk->id,
                'judul'     => $produk->judul,
                'harga'     => $produk->harga,
                'kondisi'   => $produk->kondisi,
                'kategori'  => $produk->kategori->nama ?? null,
                'deskripsi' => $produk->deskripsi,
                'area'      => $produk->area,
                'jarak'     => $produk->jarak_km,
            ],
            'kategori'     => Kategori::orderBy('id')->pluck('nama')->all(),
            'penjual'      => Auth::user(),
            'totalTerjual' => Produk::where('user_id', Auth::id())->where('status', 'terjual')->count(),
            'area'         => 'Malang, Jawa Timur',
        ]);
    }

    public function update(Request $request, Produk $produk)
    {
        $this->pastikanPemilik($produk);

        $harga = $request->input('harga');
        $request->merge(['harga' => is_scalar($harga) ? preg_replace('/\D/', '', (string) $harga) : '']);

        $data = $request->validate([
            'judul'     => ['required', 'string', 'max:100'],
            'harga'     => ['required', 'integer', 'min:1000'],
            'kategori'  => ['required', Rule::in(Kategori::pluck('nama')->all())],
            'kondisi'   => ['required', 'in:baru,bekas'],
            'deskripsi' => ['required', 'string', 'min:30', 'max:500'],
            'area'      => ['required', 'string', 'max:100'],
        ], [
            'judul.required'     => 'Nama barang wajib diisi.',
            'harga.required'     => 'Harga wajib diisi.',
            'harga.integer'      => 'Harga harus berupa angka.',
            'harga.min'          => 'Harga minimal Rp 1.000.',
            'kategori.required'  => 'Pilih kategori barang.',
            'kategori.in'        => 'Kategori tidak valid.',
            'kondisi.required'   => 'Pilih kondisi barang.',
            'deskripsi.required' => 'Deskripsi wajib diisi.',
            'deskripsi.min'      => 'Deskripsi minimal 30 karakter.',
            'deskripsi.max'      => 'Deskripsi maksimal 500 karakter.',
            'area.required'      => 'Lokasi wajib diisi.',
        ]);

        $produk->update([
            'judul'       => $data['judul'],
            'harga'       => (int) $data['harga'],
            'kategori_id' => Kategori::where('nama', $data['kategori'])->value('id'),
            'kondisi'     => $data['kondisi'],
            'deskripsi'   => $data['deskripsi'],
            'area'        => $data['area'],
        ]);

        return redirect()->route('barang.saya')->with('status', 'Perubahan barang berhasil disimpan.');
    }

    public function ubahStatus(Request $request, Produk $produk)
    {
        $this->pastikanPemilik($produk);

        $data = $request->validate([
            'status' => ['required', 'in:tersedia,dipesan,terjual,arsip'],
        ]);

        $produk->update(['status' => $data['status']]);

        return response()->json(['ok' => true, 'status' => $data['status']]);
    }

    private function pastikanPemilik(Produk $produk): void
    {
        abort_unless((int) $produk->user_id === (int) Auth::id(), 404);
    }
}