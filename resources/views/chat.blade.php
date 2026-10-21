@extends('layouts.app')

@section('title', 'Chat - Relora')

@section('footer_links')
    <span class="text-slate-400">&copy; {{ date('Y') }} COD Market Malang. Prioritas Aman &amp; Terverifikasi.</span>
@endsection

@php
    $inisial = $aktif ? mb_strtoupper(mb_substr($aktif['penjual'], 0, 1)) : '';
    $kota = $aktif ? trim(last(explode(',', $aktif['lokasi']))) : '';
@endphp

@section('content')
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden grid lg:grid-cols-[minmax(280px,340px)_1fr] lg:h-[calc(100vh-230px)] min-h-[600px]">
        <aside class="flex flex-col min-h-0 max-h-[320px] lg:max-h-none border-b lg:border-b-0 lg:border-r border-slate-100">
            <div class="px-5 py-4 flex items-center justify-between border-b border-slate-100">
                <div class="flex items-center gap-3">
                    <h2 class="text-sm font-bold text-slate-900">Pesan Transaksi COD</h2>
                    <span class="bg-emerald-100 text-[#076653] text-[11px] font-bold px-2.5 py-1 rounded-full">{{ $jumlahAktif }} Aktif</span>
                </div>
                <x-icon name="filter" class="w-4 h-4 text-slate-400" :filled="true" />
            </div>

            <div class="px-4 py-3 border-b border-slate-100">
                <div class="relative">
                    <x-icon name="search" class="w-4 h-4 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2" />
                    <input id="cari-obrolan" type="text" placeholder="Cari obrolan..."
                        class="w-full h-10 pl-9 pr-3 rounded-lg bg-slate-50 border border-slate-200 text-xs text-slate-700 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-[#076653]/20">
                </div>
            </div>

            <div id="daftar-obrolan" class="flex-1 min-h-0 overflow-y-auto">
                @forelse ($daftar as $c)
                    @php $dipilih = $aktif && $c['id'] === $aktif['id']; @endphp
                    <a href="{{ route('chat', ['c' => $c['id']]) }}" data-obrolan="{{ $c['id'] }}" data-cari="{{ mb_strtolower($c['penjual'] . ' ' . $c['nama']) }}"
                        class="chat-item {{ $dipilih ? 'is-on' : '' }} block px-4 py-4 border-b border-slate-50 hover:bg-slate-50 transition">
                        <div class="flex gap-3">
                            <span class="relative shrink-0 w-11 h-11 rounded-full {{ $dipilih ? 'bg-[#076653]' : 'bg-slate-500' }} text-white text-base font-bold flex items-center justify-center">
                                {{ mb_strtoupper(mb_substr($c['penjual'], 0, 1)) }}
                                @if ($dipilih)
                                    <span class="absolute -bottom-0.5 -right-0.5 w-3 h-3 rounded-full bg-emerald-500 ring-2 ring-white"></span>
                                @endif
                            </span>
                            <div class="min-w-0 flex-1">
                                <div class="flex items-center justify-between gap-2">
                                    <p class="flex items-center gap-1 min-w-0 text-[13px] font-bold text-slate-900">
                                        <span class="truncate">{{ $c['penjual'] }}</span>
                                        <x-icon name="check-circle" class="w-3.5 h-3.5 shrink-0 text-[#076653]" :filled="true" />
                                    </p>
                                    <span data-label class="shrink-0 text-[11px] text-slate-400">{{ $c['label'] }}</span>
                                </div>
                                <p class="mt-0.5 text-xs font-semibold truncate {{ $dipilih ? 'text-[#076653]' : 'text-slate-700' }}">{{ $c['nama'] }}</p>
                                <p data-preview class="mt-0.5 text-xs text-slate-500 line-clamp-2">{{ $c['preview'] }}</p>
                                <div class="mt-2 flex items-center gap-2">
                                    @if ($c['status'] === 'aktif')
                                        <span class="bg-emerald-100 text-[#076653] text-[10px] font-bold px-2 py-0.5 rounded">COD MALANG</span>
                                        <span class="text-[11px] text-slate-500">Rp {{ number_format($c['harga'], 0, ',', '.') }}</span>
                                    @else
                                        <span class="bg-slate-100 text-slate-500 text-[10px] font-bold px-2 py-0.5 rounded">SELESAI</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="px-6 py-14 text-center">
                        <span class="mx-auto w-12 h-12 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center">
                            <x-icon name="chat" class="w-5 h-5" :filled="true" />
                        </span>
                        <p class="mt-3 text-sm font-semibold text-slate-700">Belum ada obrolan</p>
                        <p class="mt-1 text-xs text-slate-400">Obrolanmu dengan penjual akan muncul di sini.</p>
                    </div>
                @endforelse
            </div>
        </aside>

        @if ($aktif)
            <section class="flex flex-col min-h-0 min-w-0">
                <div class="flex items-center justify-between gap-3 px-6 py-4 border-b border-slate-100">
                    <div class="flex items-center gap-3 min-w-0">
                        <span class="shrink-0 w-11 h-11 rounded-full bg-[#076653] text-white text-lg font-bold flex items-center justify-center">{{ $inisial }}</span>
                        <div class="min-w-0">
                            <p class="flex flex-wrap items-center gap-2 text-sm font-bold text-slate-900">{{ $aktif['penjual'] }}
                                <x-icon name="check-circle" class="w-4 h-4 text-[#076653]" :filled="true" />
                                <span class="bg-emerald-100 text-[#076653] text-[10px] font-bold px-2 py-0.5 rounded">COD SAFE</span>
                            </p>
                            <p class="flex items-center gap-1.5 text-xs text-[#076653] mt-0.5">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Online - Siap COD ({{ $kota }})
                            </p>
                        </div>
                    </div>
                    <span class="shrink-0 w-9 h-9 rounded-lg border border-slate-200 text-slate-500 flex items-center justify-center">
                        <x-icon name="dots-vertical" class="w-4 h-4" />
                    </span>
                </div>

                <div class="flex flex-wrap items-center justify-between gap-3 px-6 py-3 border-b border-slate-100 bg-slate-50/60">
                    <div class="flex items-center gap-3 min-w-0">
                        <img src="{{ asset($aktif['foto']) }}" alt="{{ $aktif['nama'] }}" class="shrink-0 w-10 h-10 rounded-lg object-cover bg-slate-200">
                        <div class="min-w-0">
                            <p class="text-xs font-bold text-slate-900 truncate">{{ $aktif['nama'] }}</p>
                            <p class="text-xs text-slate-500">
                                <b class="text-[#076653]">Rp {{ number_format($aktif['harga'], 0, ',', '.') }}</b>
                                &nbsp;COD di {{ $aktif['lokasi'] }}
                            </p>
                        </div>
                    </div>
                    <span class="bg-emerald-50 border border-emerald-200 text-[#076653] text-[11px] font-semibold px-3 py-1.5 rounded-lg">
                        Barang Siap Ditransaksikan</span>
                </div>

                <div id="area-pesan" data-url="{{ route('chat.kirim', $aktif['id']) }}"
                    class="flex-1 min-h-[260px] overflow-y-auto bg-slate-50 px-6 py-5 space-y-5">
                    @forelse ($aktif['pesan'] as $p)
                        @include('partials.chat-pesan', ['p' => $p, 'inisial' => $inisial])
                    @empty
                        <div id="kosong-pesan"
                            class="h-full min-h-[200px] flex flex-col items-center justify-center text-center">
                            <span class="w-12 h-12 rounded-full bg-white border border-slate-200 text-[#076653] flex items-center justify-center">
                                <x-icon name="chat" class="w-5 h-5" :filled="true" />
                            </span>
                            <p class="mt-3 text-sm font-semibold text-slate-700">Belum ada pesan</p>
                            <p class="mt-1 text-xs text-slate-400">Tulis pesan pertamamu untuk menanyakan barang ini ke penjual.</p>
                        </div>
                    @endforelse
                </div>

                <div class="border-t border-slate-100 px-5 pt-4 pb-3">
                    <div class="flex items-center gap-3">
                        <span class="shrink-0 text-slate-400"><x-icon name="paperclip" class="w-5 h-5" /></span>
                        <input id="input-pesan" type="text" maxlength="500" autocomplete="off" placeholder="Tulis pesan..."
                            class="flex-1 min-w-0 h-12 px-4 rounded-xl bg-slate-100 text-sm text-slate-800 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-[#076653]/25">
                        <button type="button" id="btn-kirim"
                            class="hero-grad shrink-0 inline-flex items-center gap-2 h-12 px-6 rounded-xl text-sm font-bold text-[#0C342C] shadow-sm hover:brightness-105 transition">
                            Kirim <x-icon name="send" class="w-4 h-4" />
                        </button>
                    </div>
                    <div class="mt-2 flex flex-wrap items-center justify-between gap-2 text-[11px] text-slate-400">
                        <span>Transaksi aman dengan inspeksi langsung sebelum bayar</span>
                        <span>Tekan Enter untuk kirim</span>
                    </div>
                </div>
            </section>
        @else
            <section class="flex flex-col items-center justify-center text-center px-8 py-16 bg-slate-50/60">
                <span class="w-16 h-16 rounded-full bg-white border border-slate-200 text-[#076653] flex items-center justify-center">
                    <x-icon name="chat" class="w-7 h-7" :filled="true" />
                </span>
                <h2 class="mt-4 text-lg font-bold text-slate-900">Belum ada obrolan</h2>
                <p class="mt-1 text-sm text-slate-500 max-w-[360px]">
                    Mulai tanya penjual lewat tombol <b>Chat</b> di kartu barang pada Beranda, atau <b>Chat Penjual</b> di
                    halaman Wishlist.
                </p>
                <a href="{{ route('dashboard') }}"
                    class="mt-5 inline-flex items-center h-11 px-6 rounded-xl bg-[#076653] text-sm font-bold text-white hover:bg-[#055443] transition">
                    Lihat Barang
                </a>
            </section>
        @endif
    </div>

    @if ($aktif)
        <template id="tpl-saya">@include('partials.chat-pesan', [
            'p' => ['dari' => 'saya', 'tipe' => 'teks', 'teks' => '', 'jam' => ''],
            'inisial' => $inisial,
        ])</template>
        <template id="tpl-penjual">@include('partials.chat-pesan', [
            'p' => ['dari' => 'penjual', 'tipe' => 'teks', 'teks' => '', 'jam' => ''],
            'inisial' => $inisial,
        ])</template>
        <template id="tpl-ringkasan">@include('partials.chat-pesan', [
            'p' => ['dari' => 'penjual', 'tipe' => 'ringkasan', 'data' => []],
            'inisial' => $inisial,
        ])</template>
        <template id="tpl-ketik">
            <div class="flex items-start gap-3" data-ketik>
                <span class="shrink-0 w-9 h-9 rounded-full bg-[#076653] text-white text-sm font-bold flex items-center justify-center">{{ $inisial }}</span>
                <div class="rounded-2xl rounded-tl-md bg-white border border-slate-200 px-4 py-3.5 shadow-sm">
                    <span class="titik-ketik"><span></span><span></span><span></span></span>
                </div>
            </div>
        </template>
    @endif
@endsection

@push('scripts')
    <script src="{{ asset('js/chat.js') }}"></script>
@endpush
