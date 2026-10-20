<?php

namespace App\Support;

use Illuminate\Support\Facades\File;

class WishlistFile{
    private static function berkas(): string {
        return storage_path('app/wishlist.json');
    }

    private static function baca(): array {
        if (! File::exists(self::berkas())) {
            return [];
        }

        $data = json_decode(File::get(self::berkas()), true);

        return is_array($data) ? $data : [];
    }

    private static function tulis(array $data): void {
        File::ensureDirectoryExists(dirname(self::berkas()));
        File::put(
            self::berkas(),
            json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
            true
        );
    }

    public static function milik($userId): array {
        $data = self::baca();
        $kunci = (string) $userId;

        if (! array_key_exists($kunci, $data)) {
            $data[$kunci] = [['id' => 'c1', 'waktu' => time()]];
            self::tulis($data);
        }

        $daftar = $data[$kunci];
        usort($daftar, fn ($a, $b) => $b['waktu'] <=> $a['waktu']);

        return $daftar;
    }

    public static function idUser($userId): array {
        return array_column(self::milik($userId), 'id');
    }

    public static function toggle($userId, string $id): bool {
        self::milik($userId); // pastikan data awal user sudah ada

        $data = self::baca();
        $kunci = (string) $userId;
        $daftar = $data[$kunci] ?? [];

        $sisa = array_values(array_filter($daftar, fn ($e) => $e['id'] !== $id));
        $tersimpan = count($sisa) === count($daftar);

        if ($tersimpan) {
            $sisa[] = ['id' => $id, 'waktu' => time()];
        }

        $data[$kunci] = $sisa;
        self::tulis($data);

        return $tersimpan;
    }
}