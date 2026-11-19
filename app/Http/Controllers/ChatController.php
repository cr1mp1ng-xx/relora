<?php

namespace App\Http\Controllers;

use App\Models\JanjiTemu;
use App\Models\Percakapan;
use App\Models\Produk;
use App\Support\ChatBot;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ChatController extends Controller
{
    public function index(Request $request)
    {
        $semua = Percakapan::with(['produk.foto', 'penjual:id,name', 'pesan', 'janjiTemu'])
            ->where('pembeli_id', Auth::id())
            ->orderByDesc('updated_at')
            ->orderByDesc('id')
            ->get();

        $dipilih = $semua->firstWhere('id', (int) $request->query('c')) ?? $semua->first();

        if ($dipilih) {
            $dipilih->pesan()->where('pengirim', 'penjual')->whereNull('dibaca_at')->update(['dibaca_at' => now()]);
        }

        $daftar = $semua->map(function (Percakapan $c) {
            $terakhir = $c->pesan->last();

            if (! $terakhir) {
                $preview = 'Belum ada pesan';
            } elseif ($terakhir->tipe === 'ringkasan') {
                $preview = 'Ringkasan janji temu COD';
            } else {
                $preview = $terakhir->isi;
            }

            return [
                'id'      => $c->id,
                'penjual' => $c->penjual->name ?? 'Penjual',
                'nama'    => $c->produk->judul,
                'harga'   => (int) $c->produk->harga,
                'status'  => $c->status,
                'label'   => self::label($c->updated_at),
                'preview' => $preview,
            ];
        })->all();

        $aktif = null;
        if ($dipilih) {
            $janji = $dipilih->janjiTemu;

            $aktif = [
                'id'      => $dipilih->id,
                'penjual' => $dipilih->penjual->name ?? 'Penjual',
                'nama'    => $dipilih->produk->judul,
                'harga'   => (int) $dipilih->produk->harga,
                'lokasi'  => $dipilih->produk->area,
                'foto'    => $dipilih->produk->foto->first()->path ?? 'images/produk/jaket-denim.jpg',
                'pesan'   => $dipilih->pesan->map(fn ($m) => [
                    'dari' => $m->pengirim === 'pembeli' ? 'saya' : 'penjual',
                    'tipe' => $m->tipe,
                    'teks' => $m->isi,
                    'jam'  => self::jam($m->created_at),
                    'data' => ($m->tipe === 'ringkasan' && $janji) ? self::tampilJanji($janji) : [],
                ])->all(),
            ];
        }

        return view('chat', [
            'daftar'      => $daftar,
            'aktif'       => $aktif,
            'jumlahAktif' => $semua->where('status', 'aktif')->count(),
            'area'        => 'Malang, Jawa Timur',
        ]);
    }

    public function mulai(Produk $item)
    {
        abort_unless($item->status === 'tersedia', 404);

        if ((int) $item->user_id === (int) Auth::id()) {
            return back()->with('status', 'Ini barang milikmu sendiri, jadi tidak bisa chat dengan dirimu.');
        }

        $obrolan = Percakapan::firstOrCreate(
            ['pembeli_id' => Auth::id(), 'produk_id' => $item->id],
            ['penjual_id' => $item->user_id, 'status' => 'aktif']
        );

        return redirect()->route('chat', ['c' => $obrolan->id]);
    }

    public function kirim(Request $request, Percakapan $percakapan)
    {
        abort_unless((int) $percakapan->pembeli_id === (int) Auth::id(), 404);

        $data = $request->validate([
            'teks' => ['required', 'string', 'max:500'],
        ], [
            'teks.required' => 'Tulis pesan dulu.',
            'teks.max'      => 'Pesan maksimal 500 karakter.',
        ]);
        $teks = trim($data['teks']);

        $hasil = DB::transaction(function () use ($percakapan, $teks) {
            $saya = $percakapan->pesan()->create(['pengirim' => 'pembeli', 'tipe' => 'teks', 'isi' => $teks]);

            $bot = ChatBot::balas($percakapan->load('produk'), $teks);
            $balasan = $percakapan->pesan()->create([
                'pengirim'  => 'penjual',
                'tipe'      => 'teks',
                'isi'       => $bot['teks'],
                'dibaca_at' => now(),
            ]);

            $janji = null;
            if ($bot['janji']) {
                $janji = $percakapan->janjiTemu()->create([
                    'lokasi_temu'      => $bot['janji']['lokasi_temu'],
                    'waktu_temu'       => $bot['janji']['waktu_temu']->copy()->setTimezone(config('app.timezone')),
                    'harga_disepakati' => (int) $percakapan->produk->harga,
                    'status'           => 'menunggu_konfirmasi',
                ]);

                $percakapan->pesan()->create([
                    'pengirim'  => 'penjual',
                    'tipe'      => 'ringkasan',
                    'isi'       => null,
                    'dibaca_at' => now(),
                ]);
            }

            return compact('saya', 'balasan', 'janji');
        });

        return response()->json([
            'ok'        => true,
            'saya'      => ['teks' => $teks, 'jam' => self::jam($hasil['saya']->created_at)],
            'balasan'   => ['teks' => $hasil['balasan']->isi, 'jam' => self::jam($hasil['balasan']->created_at)],
            'ringkasan' => $hasil['janji'] ? self::tampilJanji($hasil['janji']) : null,
            'label'     => self::label(now()),
            'preview'   => $hasil['janji'] ? 'Ringkasan janji temu COD' : $hasil['balasan']->isi,
        ]);
    }

    private static function tampilJanji(JanjiTemu $janji): array
    {
        $t = $janji->waktu_temu->copy()->timezone('Asia/Jakarta');
        $sekarang = Carbon::now('Asia/Jakarta');

        if ($t->isSameDay($sekarang)) {
            $hari = 'Hari ini';
        } elseif ($t->isSameDay($sekarang->copy()->addDay())) {
            $hari = 'Besok';
        } else {
            $hari = $t->format('d/m/Y');
        }

        $status = [
            'menunggu_konfirmasi' => 'Menunggu Konfirmasi',
            'disepakati'          => 'Disepakati',
            'selesai'             => 'Selesai',
            'batal'               => 'Dibatalkan',
        ];

        return [
            'lokasi' => $janji->lokasi_temu,
            'waktu'  => $hari . ', pukul ' . $t->format('H:i') . ' WIB',
            'uang'   => 'Rp ' . number_format((int) $janji->harga_disepakati, 0, ',', '.') . ' (Bayar di Tempat)',
            'status' => $status[$janji->status] ?? $janji->status,
        ];
    }

    private static function jam(Carbon $waktu): string
    {
        return $waktu->copy()->timezone('Asia/Jakarta')->format('H:i');
    }

    private static function label(Carbon $waktu): string
    {
        $t = $waktu->copy()->timezone('Asia/Jakarta');
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