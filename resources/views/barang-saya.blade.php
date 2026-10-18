@extends('layouts.app')

@section('title', 'Barang Saya - Relora')

@php
    $statusMap = [
        'tersedia' => [
            'label' => 'Tersedia',
            'pill' => 'bg-emerald-50 text-[#076653]',
            'icon' => 'check',
            'aksi' => ['Edit', 'Detail'],
        ],
        'dipesan' => [
            'label' => 'Sedang Diproses',
            'pill' => 'bg-amber-100 text-amber-700',
            'icon' => 'clock',
            'aksi' => ['Edit', 'Detail'],
        ],
        'terjual' => [
            'label' => 'Terjual',
            'pill' => 'bg-red-100 text-red-600',
            'icon' => 'x',
            'aksi' => ['Arsip', 'Jual Lagi'],
        ],
        'draf' => [
            'label' => 'Draf',
            'pill' => 'bg-slate-100 text-slate-600',
            'icon' => null,
            'aksi' => ['Edit', 'Detail'],
        ],
    ];

    $tombol = [
        'Edit' => 'bg-white border border-slate-200 text-slate-700 hover:bg-slate-50',
        'Detail' => 'bg-[#076653] text-white hover:bg-[#055443]',
        'Arsip' => 'bg-slate-100 text-slate-500 hover:bg-slate-200',
        'Jual Lagi' => 'bg-white border border-slate-200 text-slate-700 hover:bg-slate-50',
    ];

    $opsiStatus = [
        'tersedia' => [
            'label' => 'Tersedia',
            'ket' => 'Barang masih bisa dibeli',
            'icon' => 'clock',
            'warna' => 'bg-emerald-100 text-emerald-500',
            'filled' => false,
        ],
        'dipesan' => [
            'label' => 'Sedang Dipesan',
            'ket' => 'Barang sedang dalam proses transaksi',
            'icon' => 'zap',
            'warna' => 'bg-amber-100 text-amber-500',
            'filled' => true,
        ],
        'terjual' => [
            'label' => 'Terjual',
            'ket' => 'Barang sudah berhasil dijual',
            'icon' => 'box',
            'warna' => 'bg-red-100 text-red-500',
            'filled' => false,
        ],
    ];
@endphp

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-3">
        <div class="flex flex-wrap items-center gap-3">
            <h1 class="text-3xl font-bold text-slate-900">Barang Saya</h1>
            <span id="jumlah-barang" class="bg-emerald-50 border border-emerald-200 text-[#076653] text-xs font-bold px-3 py-1 rounded-full">{{ count($barang) }}
                Barang
            </span>
        </div>

        <div class="flex items-center gap-3 text-xs text-slate-400">
            <label for="sort-barang">Urutkan:</label>
            <select id="sort-barang"
                class="h-10 px-4 rounded-full bg-white border border-slate-200 text-xs font-semibold text-slate-700 focus:outline-none focus:ring-2 focus:ring-[#076653]/20">
                <option value="baru">Terbaru Diunggah</option>
                <option value="lama">Terlama Diunggah</option>
                <option value="murah">Harga Terendah</option>
                <option value="mahal">Harga Tertinggi</option>
            </select>
        </div>
    </div>

    <div class="flex flex-wrap gap-2 mt-5">
        <button type="button" data-tab="semua" class="tpill is-on">Semua ({{ $jumlah['semua'] }})</button>
        <button type="button" data-tab="tersedia" class="tpill">Tersedia ({{ $jumlah['tersedia'] }})</button>
        <button type="button" data-tab="dipesan" class="tpill">Dipesan ({{ $jumlah['dipesan'] }})</button>
        <button type="button" data-tab="terjual" class="tpill">Terjual ({{ $jumlah['terjual'] }})</button>
    </div>

    <section id="barang-grid" class="mt-5 grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">
        @foreach ($barang as $item)
            @php $st = $statusMap[$item['status']] ?? $statusMap['draf']; @endphp
            <article data-item data-status="{{ $item['status'] }}" data-price="{{ $item['harga'] }}"
                data-time="{{ $item['waktu'] }}" data-judul="{{ $item['judul'] }}"
                @if (!empty($item['id'])) data-id="{{ $item['id'] }}" data-url="{{ route('barang.status', $item['id']) }}" @endif
                class="bg-white rounded-2xl border border-slate-100 shadow-sm p-4 flex gap-4">

                <div class="shrink-0 w-[120px] h-[120px] rounded-2xl overflow-hidden bg-slate-100">
                    @if ($item['foto'])
                        <img src="{{ asset($item['foto']) }}" alt="{{ $item['judul'] }}"
                            class="w-full h-full object-cover" loading="lazy">
                    @else
                        <div class="w-full h-full brand-grad opacity-30 flex items-center justify-center">
                            <x-icon name="bag" class="w-8 h-8 text-[#0C342C]" />
                        </div>
                    @endif
                </div>

                <div class="min-w-0 flex-1 flex flex-col">
                    <h3 class="text-[15px] font-bold text-slate-900 truncate" title="{{ $item['judul'] }}">{{ $item['judul'] }}</h3>
                    <p class="text-lg font-bold text-[#076653] mt-1">Rp {{ number_format($item['harga'], 0, ',', '.') }}</p>

                    <div data-status-area class="mt-auto flex flex-wrap items-center gap-2">
                        @include('partials.status-area', ['st' => $st, 'tombol' => $tombol])
                    </div>
                </div>
            </article>
        @endforeach

        <a href="{{ route('jual') }}" data-upload
            class="min-h-[152px] rounded-2xl border-2 border-dashed border-emerald-300/70 bg-emerald-50/50 flex flex-col items-center justify-center text-center p-5 hover:bg-emerald-50 transition">
            <span class="w-11 h-11 rounded-full bg-[#076653] text-white flex items-center justify-center shadow-sm">
                <x-icon name="plus" class="w-5 h-5" />
            </span>
            <span class="mt-3 text-sm font-bold text-slate-900">+ Upload Barang Baru</span>
            <span class="text-xs text-slate-500 mt-1">Pasang iklan barang barumu secara cepat</span>
        </a>
    </section>

    <p id="tab-kosong" class="hidden mt-4 text-center text-sm text-slate-400">Belum ada barang di kelompok ini.</p>

    <section class="mt-14 flex flex-wrap items-center justify-between gap-3 rounded-2xl border border-emerald-200 bg-emerald-50/70 px-6 py-4">
        <div>
            <h2 class="text-sm font-bold text-slate-900">Panduan Transaksi Aman COD</h2>
            <p class="text-xs text-slate-600 mt-0.5">Ketemu di tempat ramai seperti kafe kampus atau minimarket. Cek fisik barang sebelum bayar tunai!</p>
        </div>
        <span class="bg-white border border-emerald-200 text-[#076653] text-xs font-bold px-4 py-2 rounded-full">100% Anti
            Penipuan</span>
    </section>

    @foreach ($statusMap as $kode => $st)
        <template data-tpl="{{ $kode }}">
            @include('partials.status-area', ['st' => $st, 'tombol' => $tombol])
        </template>
    @endforeach

    <div id="status-modal" class="fixed inset-0 z-50 hidden items-center justify-center p-4" role="dialog" aria-modal="true" aria-labelledby="sm-title">
        <div data-sm-close class="absolute inset-0 bg-slate-900/50 backdrop-blur-[2px]"></div>

        <div class="relative w-full max-w-[453px] max-h-full overflow-y-auto bg-white rounded-3xl shadow-2xl">
            <div class="flex items-center justify-between px-6 py-5 border-b border-slate-100">
                <h2 id="sm-title" class="text-xl font-bold text-slate-900">Ubah Status Barang</h2>
                <button type="button" data-sm-close aria-label="Tutup"
                    class="w-9 h-9 -mr-2 rounded-full flex items-center justify-center text-slate-400 hover:bg-slate-100 hover:text-slate-700 transition">
                    <x-icon name="x" class="w-5 h-5" />
                </button>
            </div>

            <div class="px-6 pt-5 pb-6">
                <div class="flex items-center gap-4 pb-5 border-b border-slate-100">
                    <div class="shrink-0 w-[54px] h-[54px] rounded-lg overflow-hidden bg-slate-100">
                        <img id="sm-foto" src="" alt="" class="w-full h-full object-cover hidden">
                        <div id="sm-foto-kosong" class="w-full h-full brand-grad opacity-30 flex items-center justify-center">
                            <x-icon name="bag" class="w-6 h-6 text-[#0C342C]" />
                        </div>
                    </div>
                    <div class="min-w-0">
                        <p id="sm-judul" class="text-sm font-bold text-slate-900 truncate"></p>
                        <p id="sm-harga" class="text-sm font-bold text-[#076653] mt-0.5"></p>
                    </div>
                </div>

                {{-- Pilihan status --}}
                <div class="mt-4 space-y-1.5">
                    @foreach ($opsiStatus as $kode => $o)
                        <label class="opsi">
                            <input type="radio" name="status-opsi" value="{{ $kode }}" class="sr-only">
                            <span class="radio"></span>
                            <span class="shrink-0 w-10 h-10 rounded-full flex items-center justify-center {{ $o['warna'] }}">
                                <x-icon :name="$o['icon']" class="w-5 h-5" :filled="$o['filled']" />
                            </span>
                            <span class="min-w-0">
                                <span class="block text-sm font-bold text-slate-900">{{ $o['label'] }}</span>
                                <span class="block text-xs text-slate-400 mt-0.5">{{ $o['ket'] }}</span>
                            </span>
                        </label>
                    @endforeach
                </div>

                <div class="mt-6 grid grid-cols-2 gap-3">
                    <button type="button" data-sm-close
                        class="h-12 rounded-xl border-[1.5px] border-[#076653] text-sm font-bold text-[#076653] hover:bg-emerald-50 transition">Batal</button>
                    <button type="button" id="sm-simpan"
                        class="h-12 rounded-xl bg-[#0F5C50] text-sm font-bold text-white hover:bg-[#0B4A40] transition">Simpan</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/barang-saya.js') }}"></script>
@endpush
