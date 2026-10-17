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
@endphp

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-3">
        <div class="flex flex-wrap items-center gap-3">
            <h1 class="text-3xl font-bold text-slate-900">Barang Saya</h1>
            <span class="bg-emerald-50 border border-emerald-200 text-[#076653] text-xs font-bold px-3 py-1 rounded-full">{{ count($barang) }}
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
                data-time="{{ $item['waktu'] }}" class="bg-white rounded-2xl border border-slate-100 shadow-sm p-4 flex gap-4">

                <div class="shrink-0 w-[120px] h-[120px] rounded-2xl overflow-hidden bg-slate-100">
                    @if ($item['foto'])
                        <img src="{{ asset($item['foto']) }}" alt="{{ $item['judul'] }}" class="w-full h-full object-cover" loading="lazy">
                    @else
                        <div class="w-full h-full brand-grad opacity-30 flex items-center justify-center">
                            <x-icon name="bag" class="w-8 h-8 text-[#0C342C]" />
                        </div>
                    @endif
                </div>

                <div class="min-w-0 flex-1 flex flex-col">
                    <h3 class="text-[15px] font-bold text-slate-900 truncate" title="{{ $item['judul'] }}">
                        {{ $item['judul'] }}</h3>
                    <p class="text-lg font-bold text-[#076653] mt-1">Rp {{ number_format($item['harga'], 0, ',', '.') }}
                    </p>

                    <div class="mt-auto flex flex-wrap items-center gap-2">
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-semibold {{ $st['pill'] }}">
                            @if ($st['icon'])
                                <x-icon name="{{ $st['icon'] }}" class="w-3 h-3" />
                            @endif
                            {{ $st['label'] }}
                        </span>

                        <div class="ml-auto flex items-center gap-1.5">
                            @foreach ($st['aksi'] as $aksi)
                                <a href="#" class="h-8 px-3 rounded-lg inline-flex items-center text-xs font-semibold transition {{ $tombol[$aksi] }}">{{ $aksi }}</a>
                            @endforeach
                        </div>
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
@endsection

@push('scripts')
    <script src="{{ asset('js/barang-saya.js') }}"></script>
@endpush
