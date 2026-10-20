@extends('layouts.app')

@section('title', 'Wishlist - Relora')

@section('content')
    <section class="bg-white rounded-2xl border border-slate-100 shadow-sm px-6 py-5 flex flex-wrap items-center justify-between gap-4">
        <div>
            <div class="flex flex-wrap items-center gap-3">
                <h1 class="text-2xl font-bold text-slate-900">Wishlist & Barang Disimpan</h1>
                <span
                    class="inline-flex items-center gap-1.5 bg-emerald-50 text-[#076653] text-xs font-bold px-3 py-1 rounded-full">
                    <x-icon name="heart" class="w-3 h-3 text-red-500" :filled="true" />
                    <span id="wishlist-count">{{ count($wishlist) }}</span> Tersimpan
                </span>
            </div>
            <p class="text-sm text-slate-500 mt-1">Pantau barang favorit yang ingin Anda beli langsung via COD di wilayah Anda.</p>
        </div>

        <div class="flex items-center gap-3 text-xs text-slate-400">
            <label for="sort">Urutkan:</label>
            <select id="sort" class="h-9 px-4 rounded-xl bg-slate-50 border border-slate-200 text-xs font-semibold text-slate-700 focus:outline-none focus:ring-2 focus:ring-[#076653]/20">
                <option value="terbaru">Terbaru Disimpan</option>
                <option value="murah">Harga Terendah</option>
                <option value="mahal">Harga Tertinggi</option>
            </select>
        </div>
    </section>

    <section class="mt-7">
        <div id="wishlist-grid" class="product-grid">
            @foreach ($wishlist as $item)
                <article data-card data-price="{{ $item['harga'] }}" data-saved="{{ $item['disimpan'] }}" class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden flex flex-col">
                    <div class="relative h-[181px] bg-slate-100">
                        @if ($item['foto'])
                            <img src="{{ asset($item['foto']) }}" alt="{{ $item['nama'] }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full brand-grad opacity-30 flex items-center justify-center">
                                <x-icon name="bag" class="w-10 h-10 text-[#0C342C]" />
                            </div>
                        @endif

                        <button type="button" data-remove data-url="{{ route('wishlist.toggle', $item['id']) }}" aria-label="Hapus dari wishlist"
                            class="absolute top-3 right-3 w-[26px] h-[26px] rounded-full bg-white shadow flex items-center justify-center hover:scale-110 transition">
                            <x-icon name="heart" class="w-3.5 h-3.5 text-red-500" :filled="true" />
                        </button>

                        @if ($item['cod'])
                            <span class="absolute left-2.5 bottom-2.5 inline-flex items-center gap-1 bg-white text-[#076653] text-[10px] font-bold tracking-wide px-2.5 py-1 rounded-full shadow-sm">
                                <x-icon name="handshake" class="w-3 h-3" /> COD AVAILABLE
                            </span>
                        @endif
                    </div>

                    <div class="p-3.5 flex flex-col flex-1">
                        <p class="text-[10px] font-semibold uppercase tracking-wider text-slate-400">{{ $item['kategori'] }}</p>
                        <h3 class="text-sm font-bold text-slate-900 mt-1 leading-snug">{{ $item['nama'] }}</h3>
                        <p class="text-base font-bold text-[#076653] mt-1.5">Rp {{ number_format($item['harga'], 0, ',', '.') }}</p>
                        <p class="flex items-center gap-1.5 text-xs text-slate-500 mt-2">
                            <x-icon name="pin" class="w-3.5 h-3.5 text-[#076653]" :filled="true" />
                            {{ $item['lokasi'] }} <span class="text-slate-400">({{ $item['jarak'] }})</span>
                        </p>

                        <div class="mt-auto pt-3.5">
                            <div class="border-t border-slate-100 pt-3 flex gap-2">
                                <a href="#" class="flex-[1.4] flex items-center justify-center gap-1.5 h-9 rounded-xl border border-slate-200 bg-white text-xs font-semibold text-slate-800 hover:bg-slate-50 transition">
                                    <x-icon name="chat" class="w-3.5 h-3.5 text-[#076653]" :filled="true" /> 
                                    Chat Penjual
                                </a>
                                <a href="#" class="brand-grad flex-1 flex items-center justify-center h-9 rounded-xl text-xs font-bold text-white hover:brightness-105 transition">
                                    Beli COD
                                </a>
                            </div>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>

        <div id="wishlist-empty"
            class="{{ count($wishlist) ? 'hidden' : '' }} bg-white rounded-2xl border border-dashed border-slate-200 py-14 text-center">
            <x-icon name="heart" class="w-8 h-8 text-slate-300 mx-auto" />
            <p class="mt-3 text-sm font-semibold text-slate-700">
                {{ ($jarak ?? 50) < 50 || ($harga ?? 0) > 0 ? 'Tidak ada barang yang sesuai filter' : 'Belum ada barang yang disimpan' }}
            </p>
            <p class="text-xs text-slate-400 mt-1">
                {{ ($jarak ?? 50) < 50 || ($harga ?? 0) > 0 ? 'Coba ubah atau reset filter pencarian.' : 'Tekan ikon hati pada barang untuk menyimpannya di sini.' }}
            </p>
        </div>
    </section>

    <section class="mt-10 bg-emerald-50/70 border border-emerald-100 rounded-2xl px-6 py-5 flex items-start gap-4">
        <div class="shrink-0 w-11 h-11 rounded-xl bg-white shadow-sm flex items-center justify-center">
            <x-icon name="shield" class="w-6 h-6 text-[#076653]" :filled="true" />
        </div>
        <div>
            <h2 class="text-sm font-bold text-slate-900">Panduan & Tips Belanja Aman COD</h2>
            <p class="text-xs text-slate-600 leading-relaxed mt-1">
                Jadwalkan pertemuan di tempat terbuka yang ramai seperti kafe, minimarket, atau area publik lainnya.
                Selalu periksa keaslian, kondisi fisik, serta jahitan/fungsi sebelum membayar uang tunai pas kepada penjual.
                Tanpa transfer uang muka apa pun!
            </p>
        </div>
    </section>

    @include('partials.filter-modal')
@endsection

@push('scripts')
    <script src="{{ asset('js/wishlist.js') }}"></script>
@endpush
