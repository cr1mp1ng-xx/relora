@extends('layouts.app')
@section('title', 'Beranda - Relora')
@section('content')
    <section class="hero-grad relative overflow-hidden rounded-3xl px-8 sm:px-[30px] py-9">
        <x-icon name="handshake" class="absolute right-10 bottom-4 w-28 h-28 text-[#0C342C] opacity-10" />

        <div class="relative">
            <div class="flex flex-wrap items-center gap-3">
                <span class="bg-[#0F172A] text-white text-[10px] font-bold tracking-wider px-3 py-1.5 rounded-lg">AMAN &PRAKTIS</span>
                <span class="flex items-center gap-1.5 text-xs font-semibold text-slate-800">
                    <x-icon name="check-circle" class="w-4 h-4 text-[#076653]" :filled="true" /> Transaksi Tatap Muka
                </span>
            </div>
            <h1 class="text-4xl sm:text-[42px] leading-tight font-extrabold text-[#0C342C] mt-3">Transaksi Khusus COD</h1>
            <p class="text-base text-[#0C342C]/80 mt-1.5">Cek fisik barang langsung & bayar di tempat tanpa kawatir.</p>
        </div>
    </section>

    <div class="mt-8 pb-3 border-b border-slate-200 flex flex-wrap items-center justify-between gap-2">
        <div class="flex flex-wrap items-center gap-3">
            <h2 class="text-2xl font-bold text-slate-900">Barang Terdekat ({{ count($produk) }})</h2>
            <span class="bg-slate-100 text-slate-600 text-xs font-medium px-3 py-1 rounded-full">{{ $wilayah }}</span>
        </div>
        <p class="text-xs text-slate-500">Maksimal radius: <b class="text-[#076653]">{{ $radius }} km</b></p>
    </div>

    <section class="mt-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        @foreach ($produk as $item)
            <article
                class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden flex flex-col hover:shadow-md transition">
                <div class="relative aspect-square bg-slate-100">
                    <img src="{{ asset($item['foto']) }}" alt="{{ $item['nama'] }}" class="w-full h-full object-cover"
                        loading="lazy">

                    <button type="button" data-fav aria-label="Simpan ke wishlist"
                        class="fav {{ $item['disimpan'] ? 'is-saved' : '' }} absolute top-3 right-3 w-8 h-8 rounded-full bg-white/95 shadow flex items-center justify-center hover:scale-110 transition">
                        <x-icon name="heart" class="w-4 h-4" />
                    </button>

                    <span
                        class="absolute left-3 bottom-3 inline-flex items-center gap-1 bg-slate-900/85 text-white text-[10px] font-semibold px-2.5 py-1 rounded-lg">
                        <x-icon name="navigation" class="w-2.5 h-2.5" :filled="true" /> {{ $item['jarak'] }}
                    </span>
                </div>

                <div class="p-4 flex flex-col flex-1">
                    <h3 class="text-sm font-bold text-slate-900 leading-snug">{{ $item['nama'] }}</h3>
                    <p class="flex items-center gap-1.5 text-[11px] text-slate-400 mt-1.5 pb-2.5 border-b border-slate-100">
                        <x-icon name="store" class="w-3 h-3" /> {{ $item['penjual'] }}
                    </p>

                    <p class="text-lg font-bold text-[#076653] mt-2.5">Rp {{ number_format($item['harga'], 0, ',', '.') }}
                    </p>

                    <div class="mt-1 flex items-center justify-between gap-2">
                        <p class="flex items-center gap-1.5 text-xs text-slate-500">
                            <x-icon name="pin" class="w-3 h-3 text-slate-400" :filled="true" />
                            {{ $item['lokasi'] }}
                        </p>
                        @if ($item['cod'])
                            <span
                                class="bg-emerald-50 text-[#076653] text-[10px] font-bold px-2.5 py-1 rounded-lg">COD</span>
                        @endif
                    </div>
                </div>
            </article>
        @endforeach
    </section>
@endsection

@push('scripts')
    <script src="{{ asset('js/dashboard.js') }}"></script>
@endpush
