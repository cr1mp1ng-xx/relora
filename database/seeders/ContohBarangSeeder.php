<?php

namespace Database\Seeders;

use App\Models\Kategori;
use App\Models\Produk;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ContohBarangSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['Ahmad Rizky', 'ahmad_rizky', 'ahmad@relora.test', 'Jaket Denim Oversize Vintage', 'Fashion Pria', 185000, 'Lowokwaru, Malang', 2.5, 'nego',
                'Jaket denim oversize vintage, bahan tebal dan nyaman. Kondisi masih sangat baik, tidak ada sobekan.', 'images/produk/jaket-denim.jpg', 0],
            ['Budi Santoso', 'budi_santoso', 'budi@relora.test', 'Kaos Polo Katun Premium', 'Fashion Pria', 75000, 'Sukun, Malang', 4.1, 'nego',
                'Kaos polo katun premium ukuran L. Bahan adem, jahitan rapi, jarang dipakai.', 'images/produk/kaos-polo.jpg', 1],
            ['Dian Sastro', 'dian_sastro', 'dian@relora.test', 'Sepatu Sneaker Canvas Casual', 'Sepatu', 210000, 'Klojen, Malang', 1.2, 'pas',
                'Sepatu sneaker canvas ukuran 42, kondisi masih sangat baik. Jarang dipakai, tidak ada kerusakan.', 'images/produk/sepatu-sneaker.jpg', 2],
            ['Eko Prasetyo', 'eko_prasetyo', 'eko@relora.test', 'Sweater Hoodie Fleece Grey', 'Fashion Pria', 120000, 'Blimbing, Malang', 7.8, 'nego',
                'Sweater hoodie fleece warna grey, tebal dan hangat. Kondisi mulus, tanpa noda.', 'images/produk/sweater-hoodie.jpg', 3],
        ];

        foreach ($data as [$nama, $username, $email, $judul, $kategori, $harga, $area, $jarak, $nego, $deskripsi, $foto, $jam]) {
            $penjual = User::firstOrCreate(
                ['email' => $email],
                ['name' => $nama, 'username' => $username, 'password' => Hash::make('password')]
            );

            if (Produk::where('user_id', $penjual->id)->where('judul', $judul)->exists()) {
                continue; 
            }

            $produk = new Produk([
                'user_id'     => $penjual->id,
                'kategori_id' => Kategori::where('nama', $kategori)->value('id'),
                'judul'       => $judul,
                'harga'       => $harga,
                'kondisi'     => 'bekas',
                'deskripsi'   => $deskripsi,
                'area'        => $area,
                'jarak_km'    => $jarak,
                'nego'        => $nego,
                'status'      => 'tersedia',
            ]);
            $produk->created_at = now()->subHours($jam); 
            $produk->save();

            $produk->foto()->create(['path' => $foto, 'urutan' => 0]);
        }
    }
}
