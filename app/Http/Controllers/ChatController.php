<?php

namespace App\Http\Controllers;

use App\Support\ChatBot;
use App\Support\ChatFile;
use App\Support\Katalog;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller {
    public function index(Request $request) {
        $daftar = ChatFile::milik(Auth::id());

        $aktif = null;
        $dipilih = $request->query('c');
        foreach ($daftar as $c) {
            if ($dipilih !== null && (string) $c['id'] === (string) $dipilih) {
                $aktif = $c;
            }
        }
        $aktif = $aktif ?? ($daftar[0] ?? null);

        $daftarTampil = array_map(function ($c) {
            $terakhir = count($c['pesan']) ? $c['pesan'][count($c['pesan']) - 1] : null;

            if (! $terakhir) {
                $preview = 'Belum ada pesan';
            } elseif (($terakhir['tipe'] ?? '') === 'ringkasan') {
                $preview = 'Ringkasan janji temu COD';
            } else {
                $preview = $terakhir['teks'];
            }

            return $c + [
                'label'   => self::label($c['diperbarui']),
                'preview' => $preview,
            ];
        }, $daftar);

        if ($aktif) {
            $aktif['pesan'] = array_map(fn($p) => $p + ['jam' => self::jam($p['waktu'])], $aktif['pesan']);
        }

        return view('chat', [
            'daftar'      => $daftarTampil,
            'aktif'       => $aktif,
            'jumlahAktif' => count(array_filter($daftar, fn($c) => $c['status'] === 'aktif')),
            'area'        => 'Malang, Jawa Timur',
        ]);
    }

    public function mulai(string $item) {
        $barang = Katalog::cari($item);
        abort_unless($barang, 404);

        if (! empty($barang['penjual_id']) && (int) $barang['penjual_id'] === (int) Auth::id()) {
            return back()->with('status', 'Ini barang milikmu sendiri, jadi tidak bisa chat dengan dirimu.');
        }

        $obrolan = ChatFile::untukBarang(Auth::id(), $item) ?? ChatFile::buat([
            'pembeli_id' => Auth::id(),
            'item_id'    => $item,
            'penjual'    => $barang['penjual'],
            'nama'       => $barang['nama'],
            'harga'      => $barang['harga'],
            'lokasi'     => $barang['lokasi'],
            'foto'       => $barang['foto'],
            'nego'       => $barang['nego'] ?? 'nego',
            'deskripsi'  => $barang['deskripsi'] ?? null,
        ]);

        return redirect()->route('chat', ['c' => $obrolan['id']]);
    }

    public function kirim(Request $request, string $id) {
        $obrolan = ChatFile::cari($id);
        abort_unless($obrolan && (int) $obrolan['pembeli_id'] === (int) Auth::id(), 404);

        $data = $request->validate([
            'teks' => ['required', 'string', 'max:500'],
        ], [
            'teks.required' => 'Tulis pesan dulu.',
            'teks.max'      => 'Pesan maksimal 500 karakter.',
        ]);
        $teks = trim($data['teks']);

        $pesanSaya = ['dari' => 'saya', 'tipe' => 'teks', 'teks' => $teks, 'waktu' => time()];
        $obrolan = ChatFile::tambahPesan($obrolan['id'], $pesanSaya);

        $hasil = ChatBot::balas($obrolan, $teks);
        $pesanBalasan = ['dari' => 'penjual', 'tipe' => 'teks', 'teks' => $hasil['teks'], 'waktu' => time()];
        ChatFile::tambahPesan($obrolan['id'], $pesanBalasan);

        $ringkasan = null;
        if ($hasil['ringkasan']) {
            $pesanRingkasan = ['dari' => 'penjual', 'tipe' => 'ringkasan', 'teks' => '', 'waktu' => time(), 'data' => $hasil['ringkasan']];
            ChatFile::tambahPesan($obrolan['id'], $pesanRingkasan);
            $ringkasan = $hasil['ringkasan'];
        }

        return response()->json([
            'ok'        => true,
            'saya'      => ['teks' => $teks, 'jam' => self::jam($pesanSaya['waktu'])],
            'balasan'   => ['teks' => $hasil['teks'], 'jam' => self::jam($pesanBalasan['waktu'])],
            'ringkasan' => $ringkasan,
            'label'     => self::label(time()),
            'preview'   => $ringkasan ? 'Ringkasan janji temu COD' : $hasil['teks'],
        ]);
    }

    private static function jam(int $waktu): string {
        return Carbon::createFromTimestamp($waktu, 'Asia/Jakarta')->format('H:i');
    }

    private static function label(int $waktu): string {
        $t = Carbon::createFromTimestamp($waktu, 'Asia/Jakarta');
        $sekarang = Carbon::now('Asia/Jakarta');

        if ($t->isSameDay($sekarang)) {
            return $t->format('H:i');
        }
        if ($t->isSameDay($sekarang->copy()->subDay())) {
            return 'Kemarin';
        }

        return $t->format('d/m');
    }
}
