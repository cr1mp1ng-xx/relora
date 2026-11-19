<?php

namespace Database\Seeders;

use App\Models\Kategori;
use Illuminate\Database\Seeder;

class KategoriSeeder extends Seeder
{
    public function run(): void
    {
        foreach ([
            'Fashion Pria', 'Fashion Wanita', 'Sepatu', 'Tas & Aksesoris', 'Elektronik',
            'Gadget', 'Buku & Alat Kuliah', 'Olahraga', 'Perabot', 'Lainnya',
        ] as $nama) {
            Kategori::firstOrCreate(['nama' => $nama]);
        }
    }
}
