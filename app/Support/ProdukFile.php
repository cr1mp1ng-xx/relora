<?php

namespace App\Support;

use Illuminate\Support\Facades\File;

/**
 * Ini untuk penyimpanan barang SEMENTARA di file (storage/app/produk.json), tanpa database karena takutnya jelasinnya kurang jelas dan anggota nanti kebingungan.
 */
class ProdukFile {
    private static function berkas(): string {
        return storage_path('app/produk.json');
    }

    public static function semua(): array {
        if (! File::exists(self::berkas())) {
            return [];
        }

        $data = json_decode(File::get(self::berkas()), true);

        return is_array($data) ? $data : [];
    }

    private static function simpan(array $data): void {
        File::ensureDirectoryExists(dirname(self::berkas()));
        File::put(
            self::berkas(),
            json_encode(array_values($data), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
            true
        );
    }

    public static function milikUser($userId): array {
        return array_values(array_filter(
            self::semua(),
            fn ($p) => (int) $p['user_id'] === (int) $userId
        ));
    }

    public static function cari($id): ?array {
        foreach (self::semua() as $p) {
            if ((string) $p['id'] === (string) $id) {
                return $p;
            }
        }

        return null;
    }

    public static function tambah(array $data): array {
        $semua = self::semua();

        $terbesar = 0;
        foreach ($semua as $p) {
            $terbesar = max($terbesar, (int) $p['id']);
        }

        $data['id'] = $terbesar + 1;
        $data['dibuat'] = time();

        $semua[] = $data;
        self::simpan($semua);

        return $data;
    }

    public static function ubah($id, array $perubahan): ?array {
        $semua = self::semua();
        $hasil = null;

        foreach ($semua as $i => $p) {
            if ((string) $p['id'] === (string) $id) {
                $semua[$i] = array_merge($p, $perubahan);
                $hasil = $semua[$i];
                break;
            }
        }

        if ($hasil) {
            self::simpan($semua);
        }

        return $hasil;
    }
}