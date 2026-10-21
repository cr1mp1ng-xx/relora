<?php

namespace App\Support;

use Illuminate\Support\Facades\File;

class ChatFile {
    private static function berkas(): string {
        return storage_path('app/chat.json');
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

    public static function milik($userId): array {
        $daftar = array_values(array_filter(
            self::semua(),
            fn($c) => (int) $c['pembeli_id'] === (int) $userId
        ));

        usort($daftar, fn($a, $b) => $b['diperbarui'] <=> $a['diperbarui']);

        return $daftar;
    }

    public static function cari($id): ?array {
        foreach (self::semua() as $c) {
            if ((string) $c['id'] === (string) $id) {
                return $c;
            }
        }

        return null;
    }

    public static function untukBarang($userId, string $itemId): ?array {
        foreach (self::semua() as $c) {
            if ((int) $c['pembeli_id'] === (int) $userId && $c['item_id'] === $itemId) {
                return $c;
            }
        }

        return null;
    }

    public static function buat(array $data): array {
        $semua = self::semua();

        $terbesar = 0;
        foreach ($semua as $c) {
            $terbesar = max($terbesar, (int) $c['id']);
        }

        $data['id'] = $terbesar + 1;
        $data['status'] = 'aktif';
        $data['pesan'] = [];
        $data['dibuat'] = time();
        $data['diperbarui'] = time();

        $semua[] = $data;
        self::simpan($semua);

        return $data;
    }

    public static function tambahPesan($id, array $pesan): ?array {
        $semua = self::semua();
        $hasil = null;

        foreach ($semua as $i => $c) {
            if ((string) $c['id'] === (string) $id) {
                $semua[$i]['pesan'][] = $pesan;
                $semua[$i]['diperbarui'] = $pesan['waktu'];
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
