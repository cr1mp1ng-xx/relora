<?php
namespace App\Support;

use Illuminate\Http\Request;

class FilterBarang {
    public const JARAK_MAKS = 50;
    public static function baca(Request $request): array {
        $jarak = $request->query('jarak', self::JARAK_MAKS);
        $jarak = is_scalar($jarak) ? (int) $jarak : self::JARAK_MAKS;
        $jarak = max(1, min(self::JARAK_MAKS, $jarak));

        $harga = $request->query('harga', '');
        $harga = is_scalar($harga) ? (int) preg_replace('/\D/', '', (string) $harga) : 0;

        return [$jarak, $harga];
    }

    public static function terapkan(array $items, int $jarak, int $harga): array{
        return array_values(array_filter($items, function ($item) use ($jarak, $harga) {
            return $item['jarak_km'] <= $jarak
                && ($harga === 0 || $item['harga'] <= $harga);
        }));
    }
}
