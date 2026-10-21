@extends('layouts.app')

@section('title', 'Profil - Relora')

@section('footer_links')
    <span class="text-slate-400">&copy; {{ date('Y') }} COD Market Malang. Prioritas Aman &amp; Terverifikasi.</span>
@endsection

@php
    $menu = [
        ['judul' => 'Barang Saya',       'ket' => 'Barang yang kamu unggah',            'href' => route('barang.saya'), 'badge' => $jumlahBarang, 'warna' => 'bg-emerald-100 text-[#076653]'],
        ['judul' => 'Chat',              'ket' => 'Tanya barang & atur COD',            'href' => '#',                  'badge' => $chatBaru,     'warna' => 'bg-rose-500 text-white'],
        ['judul' => 'Barang Favorit',    'ket' => 'Barang yang kamu simpan',            'href' => route('wishlist'),    'badge' => null,          'warna' => ''],
        ['judul' => 'Riwayat Transaksi', 'ket' => 'Daftar transaksi selesai di Malang', 'href' => '#',                  'badge' => null,          'warna' => ''],
    ];
@endphp

@section('content')
<div class="grid lg:grid-cols-[5fr_7fr] gap-8 lg:gap-10 lg:px-8 items-start">
    <section class="bg-white rounded-[28px] border border-slate-100 shadow-sm p-6 sm:p-9">
        <div class="flex items-center gap-5">
            <div class="shrink-0 w-[104px] h-[104px] rounded-full border-4 border-emerald-400 p-1">
                <div class="brand-grad w-full h-full rounded-full flex items-center justify-center text-4xl font-bold text-[#0C342C]">
                    {{ mb_strtoupper(mb_substr($user->name, 0, 1)) }}
                </div>
            </div>

            <div class="min-w-0">
                <h1 class="text-3xl font-semibold text-slate-900 truncate">{{ $user->name }}</h1>
                <span class="inline-flex items-center gap-1 mt-1 bg-emerald-50 text-[#076653] text-xs font-semibold px-3 py-1 rounded-full">
                    <x-icon name="check" class="w-3 h-3" /> Terpercaya
                </span>
                <p class="text-lg text-slate-400 mt-1 truncate">{{ $user->email }}</p>
            </div>
        </div>

        <div class="mt-8 grid sm:grid-cols-2 sm:divide-x divide-slate-200 rounded-2xl bg-slate-50 border border-slate-100 p-4">
            <div class="flex items-center gap-4 px-2 py-1">
                <span class="shrink-0 w-12 h-12 rounded-xl bg-slate-100 text-slate-500 flex items-center justify-center">
                    <x-icon name="calendar" class="w-5 h-5" />
                </span>
                <div>
                    <p class="text-[11px] uppercase tracking-wider text-slate-400">Produk Terjual</p>
                    <p class="text-xl font-bold text-slate-900">{{ $terjual }}</p>
                </div>
            </div>
            <div class="flex items-center gap-4 px-2 py-1 sm:pl-6 mt-3 sm:mt-0">
                <span class="shrink-0 w-12 h-12 rounded-xl bg-emerald-100 text-[#076653] flex items-center justify-center">
                    <x-icon name="pin" class="w-5 h-5" :filled="true" />
                </span>
                <div>
                    <p class="text-[11px] uppercase tracking-wider text-slate-400">Area COD</p>
                    <p class="text-xl font-bold text-slate-900">{{ $areaCod }}</p>
                </div>
            </div>
        </div>

        <hr class="mt-8 border-slate-100">

        <div class="mt-6 text-lg text-slate-700 leading-8">
            @foreach ($bio as $baris)
                <p>{{ $baris }}</p>
            @endforeach
        </div>

        <a href="#" class="mt-9 flex items-center justify-center gap-2.5 h-16 rounded-2xl bg-[#0F5C50] text-lg font-medium text-white hover:bg-[#0B4A40] transition">
            <x-icon name="pencil" class="w-5 h-5" :filled="true" /> Edit Profile
        </a>
    </section>

    <div>
        <h2 class="text-2xl font-bold text-slate-900">Riwayat Aktifitas</h2>

        <nav class="mt-5 bg-white rounded-[28px] border border-slate-100 shadow-sm overflow-hidden divide-y divide-slate-100">
            @foreach ($menu as $item)
                <a href="{{ $item['href'] }}" class="flex items-center justify-between gap-4 px-7 py-5 hover:bg-slate-50 transition">
                    <div class="min-w-0">
                        <p class="text-[17px] font-semibold text-slate-900">{{ $item['judul'] }}</p>
                        <p class="text-sm text-slate-500 mt-0.5">{{ $item['ket'] }}</p>
                    </div>
                    <div class="flex items-center gap-3 shrink-0">
                        @if ($item['badge'])
                            <span class="min-w-8 h-8 px-2 rounded-full text-sm font-bold flex items-center justify-center {{ $item['warna'] }}">{{ $item['badge'] }}</span>
                        @endif
                        <x-icon name="chevron-right" class="w-5 h-5 text-slate-300" />
                    </div>
                </a>
            @endforeach
        </nav>

        <div class="mt-6 bg-white rounded-[28px] border border-slate-100 shadow-sm px-7 py-6 flex flex-wrap items-center justify-between gap-4">
            <div>
                <p class="text-sm font-bold tracking-wider uppercase text-slate-900">Pilihan Tema</p>
                <p class="text-sm text-slate-500 mt-0.5">Sesuaikan tampilan mata</p>
            </div>
            <div class="flex items-center gap-1 p-1.5 rounded-2xl bg-slate-100">
                <button type="button" data-tema="terang"  class="seg">Terang</button>
                <button type="button" data-tema="gelap"   class="seg">Gelap</button>
                <button type="button" data-tema="default" class="seg">Default</button>
            </div>
        </div>
    </div>
</div>
@endsection