<?php

namespace App\Http\Controllers;

use App\Support\ProdukFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ProdukController extends Controller {
    private const KATEGORI = [
        'Fashion Pria',
        'Fashion Wanita',
        'Sepatu',
        'Tas & Aksesoris',
        'Elektronik',
        'Gadget',
        'Buku & Alat Kuliah',
        'Olahraga',
        'Perabot',
        'Lainnya',
    ];

    public function index() {
        $contoh = [
            ['judul' => 'Sepatu Sneaker Canvas Casual', 'harga' => 210000, 'status' => 'tersedia', 'foto' => 'images/barang-saya/sepatu.jpg',      'waktu' => now()->subDays(1)->timestamp],
            ['judul' => 'Kaos Polo Katun Premium',      'harga' => 75000,  'status' => 'dipesan',  'foto' => 'images/barang-saya/kaos-polo.jpg',   'waktu' => now()->subDays(2)->timestamp],
            ['judul' => 'Jaket Denim Oversize Vintage', 'harga' => 185000, 'status' => 'terjual',  'foto' => 'images/barang-saya/jaket-denim.jpg', 'waktu' => now()->subDays(3)->timestamp],
        ];

        $milik = array_filter(ProdukFile::milikUser(Auth::id()), fn($p) => $p['status'] !== 'arsip');
        usort($milik, fn($a, $b) => $b['dibuat'] <=> $a['dibuat']);

        $milikku = array_map(fn($p) => [
            'id'     => $p['id'],
            'judul'  => $p['judul'],
            'harga'  => $p['harga'],
            'status' => $p['status'],
            'foto'   => $p['foto'][0] ?? null,
            'waktu'  => $p['dibuat'],
        ], $milik);

        $barang = array_merge($milikku, $contoh);

        $jumlah = ['semua' => count($barang)];
        foreach (['tersedia', 'dipesan', 'terjual'] as $status) {
            $jumlah[$status] = count(array_filter($barang, fn($b) => $b['status'] === $status));
        }

        return view('barang-saya', [
            'barang' => $barang,
            'jumlah' => $jumlah,
            'area'   => 'Malang, Jawa Timur',
        ]);
    }

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

        ProdukFile::tambah([
            'user_id'   => Auth::id(),
            'judul'     => $request->judul,
            'kategori'  => null,
            'kondisi'   => 'bekas',
            'harga'     => (int) $request->harga,
            'area'      => $request->area,
            'jarak'     => (float) $request->jarak,
            'deskripsi' => $request->deskripsi,
            'nego'      => $request->nego,
            'foto'      => $paths,
            'status'    => $upload ? 'tersedia' : 'draf',
        ]);

        if ($upload) {
            return redirect()->route('dashboard')->with('status', 'Produk berhasil diupload dan tampil di Beranda.');
        }

        return redirect()->route('barang.saya')->with('status', 'Draf produk berhasil disimpan.');
    }

    public function edit(string $produk) {
        $item = $this->milikSaya($produk);

        $terjual = count(array_filter(
            ProdukFile::milikUser(Auth::id()),
            fn($p) => $p['status'] === 'terjual'
        ));

        return view('edit-barang', [
            'produk'       => (object) $item,
            'kategori'     => self::KATEGORI,
            'penjual'      => Auth::user(),
            'totalTerjual' => $terjual,
            'area'         => 'Malang, Jawa Timur',
        ]);
    }

    public function update(Request $request, string $produk) {
        $item = $this->milikSaya($produk);

        $harga = $request->input('harga');
        $request->merge(['harga' => is_scalar($harga) ? preg_replace('/\D/', '', (string) $harga) : '']);

        $data = $request->validate([
            'judul'     => ['required', 'string', 'max:100'],
            'harga'     => ['required', 'integer', 'min:1000'],
            'kategori'  => ['required', Rule::in(self::KATEGORI)],
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

        $data['harga'] = (int) $data['harga'];
        ProdukFile::ubah($item['id'], $data);

        return redirect()->route('barang.saya')->with('status', 'Perubahan barang berhasil disimpan.');
    }

    public function ubahStatus(Request $request, string $produk) {
        $item = $this->milikSaya($produk);

        $data = $request->validate([
            'status' => ['required', 'in:tersedia,dipesan,terjual,arsip'],
        ]);

        ProdukFile::ubah($item['id'], ['status' => $data['status']]);

        return response()->json(['ok' => true, 'status' => $data['status']]);
    }

    private function milikSaya(string $id): array {
        $item = ProdukFile::cari($id);
        abort_unless($item && (int) $item['user_id'] === (int) Auth::id(), 404);

        return $item;
    }
}
