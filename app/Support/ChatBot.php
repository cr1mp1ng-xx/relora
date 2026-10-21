<?php

namespace App\Support;

use Illuminate\Support\Carbon;

class ChatBot {
    public static function balas(array $obrolan, string $teks): array {
        $kata = mb_strtolower($teks);
        $nama = $obrolan['nama'];
        $lokasi = $obrolan['lokasi'];
        $harga = 'Rp ' . number_format((int) $obrolan['harga'], 0, ',', '.');

        $sudahJanji = false;
        foreach ($obrolan['pesan'] as $p) {
            if (($p['tipe'] ?? '') === 'ringkasan') {
                $sudahJanji = true;
            }
        }

        $jumlahKata = count(preg_split('/\s+/', trim($kata)));
        if ($jumlahKata <= 4 && self::mulaiDengan($kata, ['halo', 'hallo', 'hai', 'hi', 'permisi', 'selamat', 'assalamualaikum', 'pagi', 'siang', 'sore', 'malam'])) {
            return ['teks' => "Halo kak! Ada yang ingin ditanyakan tentang {$nama}? Bisa nego halus atau janji ketemu lokasi COD di area {$lokasi}.", 'ringkasan' => null];
        }

        if (self::memuat($kata, ['makasih', 'terima kasih', 'thanks', 'thx'])) {
            return ['teks' => 'Sama-sama kak! Kabari ya kalau sudah sampai di lokasi COD.', 'ringkasan' => null];
        }

        if (self::memuat($kata, ['masih ada', 'ready', 'tersedia', 'stok', 'stock', 'ada gak', 'ada nggak', 'ada ga'])) {
            return ['teks' => "Baik kak, {$nama} masih ada dan siap COD di {$lokasi}. Mau diatur jadwal ketemunya?", 'ringkasan' => null];
        }

        if (self::memuat($kata, ['kondisi', 'minus', 'lecet', 'cacat', 'rusak', 'kualitas', 'jahitan', 'mulus', 'original', 'ukuran', 'size'])) {
            $deskripsi = trim((string) ($obrolan['deskripsi'] ?? ''));
            $jawab = $deskripsi !== ''
                ? 'Kondisi sesuai deskripsi ya kak: ' . mb_strimwidth($deskripsi, 0, 140, '...') . ' Nanti bisa cek fisik langsung saat COD.'
                : 'Kondisi sesuai foto ya kak, masih sangat layak pakai. Nanti bisa dicek langsung saat COD.';

            return ['teks' => $jawab, 'ringkasan' => null];
        }

        if (self::memuat($kata, ['nego', 'tawar', 'kurang', 'harga', 'murah', 'diskon'])) {
            $jawab = ($obrolan['nego'] ?? 'nego') === 'pas'
                ? "Harga {$harga} sudah pas ya kak, mohon maaf belum bisa nego."
                : "Harga {$harga} masih bisa nego tipis kak. Silakan ajukan tawaran, nanti saya pertimbangkan.";

            return ['teks' => $jawab, 'ringkasan' => null];
        }

        if (self::memuat($kata, ['cod', 'ketemu', 'temu', 'lokasi', 'tempat', 'kapan', 'deal', 'sepakat', 'janji', 'jam ', 'pagi', 'siang', 'sore', 'malam', 'besok', 'hari ini'])) {
            if ($sudahJanji) {
                return ['teks' => 'Siap kak, jadwalnya tetap seperti ringkasan janji temu di atas ya. Kabari kalau ada perubahan.', 'ringkasan' => null];
            }

            $jam = Carbon::now('Asia/Jakarta')->addHours(2);
            $waktu = ($jam->hour >= 9 && $jam->hour <= 20)
                ? 'Hari ini, pukul ' . $jam->format('H') . ':00 WIB'
                : 'Besok, pukul 10:00 WIB';

            return [
                'teks' => "Bisa kak, COD di sekitar {$lokasi} ya. Bagaimana kalau {$waktu}? Siapkan uang pas {$harga}.",
                'ringkasan' => [
                    'lokasi' => "Titik temu ramai di {$lokasi}",
                    'waktu'  => $waktu,
                    'uang'   => "{$harga} (Bayar di Tempat)",
                    'status' => 'Menunggu Konfirmasi',
                ],
            ];
        }

        return ['teks' => "Terima kasih sudah menghubungi kak. {$nama} siap COD di {$lokasi}. Mau diatur jadwal ketemu atau ada yang ingin ditanyakan?", 'ringkasan' => null];
    }

    private static function memuat(string $teks, array $kata): bool {
        foreach ($kata as $k) {
            if (str_contains($teks, $k)) {
                return true;
            }
        }

        return false;
    }

    private static function mulaiDengan(string $teks, array $kata): bool {
        foreach ($kata as $k) {
            if (str_starts_with($teks, $k)) {
                return true;
            }
        }

        return false;
    }
}
