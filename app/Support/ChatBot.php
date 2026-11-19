<?php

namespace App\Support;

use App\Models\Percakapan;
use Illuminate\Support\Carbon;

class ChatBot
{
    public static function balas(Percakapan $obrolan, string $teks): array
    {
        $produk = $obrolan->produk;
        $kata = mb_strtolower($teks);
        $nama = $produk->judul;
        $lokasi = $produk->area;
        $harga = 'Rp ' . number_format((int) $produk->harga, 0, ',', '.');

        $jumlahKata = count(preg_split('/\s+/', trim($kata)));
        if ($jumlahKata <= 4 && self::mulaiDengan($kata, ['halo', 'hallo', 'hai', 'hi', 'permisi', 'selamat', 'assalamualaikum', 'pagi', 'siang', 'sore', 'malam'])) {
            return ['teks' => "Halo kak! Ada yang ingin ditanyakan tentang {$nama}? Bisa nego halus atau janji ketemu lokasi COD di area {$lokasi}.", 'janji' => null];
        }

        if (self::memuat($kata, ['makasih', 'terima kasih', 'thanks', 'thx'])) {
            return ['teks' => 'Sama-sama kak! Kabari ya kalau sudah sampai di lokasi COD.', 'janji' => null];
        }

        // 3) barang masih ada?
        if (self::memuat($kata, ['masih ada', 'ready', 'tersedia', 'stok', 'stock', 'ada gak', 'ada nggak', 'ada ga'])) {
            return ['teks' => "Baik kak, {$nama} masih ada dan siap COD di {$lokasi}. Mau diatur jadwal ketemunya?", 'janji' => null];
        }

        if (self::memuat($kata, ['kondisi', 'minus', 'lecet', 'cacat', 'rusak', 'kualitas', 'jahitan', 'mulus', 'original', 'ukuran', 'size'])) {
            $deskripsi = trim((string) $produk->deskripsi);
            $jawab = $deskripsi !== ''
                ? 'Kondisi sesuai deskripsi ya kak: ' . mb_strimwidth($deskripsi, 0, 140, '...') . ' Nanti bisa cek fisik langsung saat COD.'
                : 'Kondisi sesuai foto ya kak, masih sangat layak pakai. Nanti bisa dicek langsung saat COD.';

            return ['teks' => $jawab, 'janji' => null];
        }

        if (self::memuat($kata, ['nego', 'tawar', 'kurang', 'harga', 'murah', 'diskon'])) {
            $jawab = $produk->nego === 'pas'
                ? "Harga {$harga} sudah pas ya kak, mohon maaf belum bisa nego."
                : "Harga {$harga} masih bisa nego tipis kak. Silakan ajukan tawaran, nanti saya pertimbangkan.";

            return ['teks' => $jawab, 'janji' => null];
        }

        if (self::memuat($kata, ['cod', 'ketemu', 'temu', 'lokasi', 'tempat', 'kapan', 'deal', 'sepakat', 'janji', 'jam ', 'pagi', 'siang', 'sore', 'malam', 'besok', 'hari ini'])) {
            if ($obrolan->janjiTemu()->exists()) {
                return ['teks' => 'Siap kak, jadwalnya tetap seperti ringkasan janji temu di atas ya. Kabari kalau ada perubahan.', 'janji' => null];
            }

            $jam = Carbon::now('Asia/Jakarta')->addHours(2)->minute(0)->second(0);
            if ($jam->hour >= 9 && $jam->hour <= 20) {
                $waktu = $jam;
                $teksWaktu = 'Hari ini, pukul ' . $jam->format('H') . ':00 WIB';
            } else {
                $waktu = Carbon::now('Asia/Jakarta')->addDay()->setTime(10, 0, 0);
                $teksWaktu = 'Besok, pukul 10:00 WIB';
            }

            return [
                'teks'  => "Bisa kak, COD di sekitar {$lokasi} ya. Bagaimana kalau {$teksWaktu}? Siapkan uang pas {$harga}.",
                'janji' => [
                    'lokasi_temu' => "Titik temu ramai di {$lokasi}",
                    'waktu_temu'  => $waktu,
                ],
            ];
        }

        return ['teks' => "Terima kasih sudah menghubungi kak. {$nama} siap COD di {$lokasi}. Mau diatur jadwal ketemu atau ada yang ingin ditanyakan?", 'janji' => null];
    }

    private static function memuat(string $teks, array $kata): bool
    {
        foreach ($kata as $k) {
            if (str_contains($teks, $k)) {
                return true;
            }
        }

        return false;
    }

    private static function mulaiDengan(string $teks, array $kata): bool
    {
        foreach ($kata as $k) {
            if (str_starts_with($teks, $k)) {
                return true;
            }
        }

        return false;
    }
}