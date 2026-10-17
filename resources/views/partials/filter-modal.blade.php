@php
    $jarakNow = $jarak ?? 50;
    $hargaNow = $harga ?? 0;
    $titik    = $titikAcuan ?? 'Klojen, Malang';
@endphp

<div id="filter-modal" class="fixed inset-0 z-50 hidden items-center justify-center p-4" role="dialog" aria-modal="true" aria-labelledby="filter-title">
    <div data-filter-close class="absolute inset-0 bg-slate-900/50 backdrop-blur-[2px]"></div>

    <form id="filter-form" method="GET" action="{{ url()->current() }}" class="relative w-full max-w-[680px] max-h-full overflow-y-auto bg-white rounded-3xl shadow-2xl border border-slate-100">
        <div class="flex items-start justify-between gap-4 px-6 py-5 bg-gradient-to-r from-emerald-50/80 to-white border-b border-slate-100">
            <div class="flex items-start gap-4">
                <span class="shrink-0 w-11 h-11 rounded-xl bg-emerald-100 text-[#076653] flex items-center justify-center">
                    <x-icon name="sliders" class="w-5 h-5" />
                </span>
                <div>
                    <h2 id="filter-title" class="text-xl font-bold text-slate-900">Filter Pencarian</h2>
                    <p class="text-sm text-slate-500 mt-0.5">Atur batas jarak dan harga untuk menemukan barang yang sesuai.</p>
                </div>
            </div>
            <div class="shrink-0 flex items-center gap-2">
                <span class="bg-emerald-50 border border-emerald-200 text-[#076653] text-xs font-bold px-3 py-1.5 rounded-full">Khusus COD</span>
                <button type="button" data-filter-close aria-label="Tutup filter"
                        class="w-9 h-9 rounded-full flex items-center justify-center text-slate-400 hover:bg-slate-100 hover:text-slate-700 transition">
                    <x-icon name="x" class="w-5 h-5" />
                </button>
            </div>
        </div>

        <div class="px-6 pt-5 pb-6">
            <div class="flex items-center justify-between gap-3">
                <h3 class="flex items-center gap-3 text-sm font-bold text-slate-900">
                    <span class="w-7 h-7 rounded-lg bg-emerald-100 text-[#076653] flex items-center justify-center">
                        <x-icon name="pin" class="w-4 h-4" />
                    </span>
                    Maksimal Jarak
                </h3>
                <span id="filter-jarak-label" class="bg-emerald-50 border border-emerald-200 text-[#076653] text-sm font-bold px-3.5 py-1 rounded-full">{{ $jarakNow }} km</span>
            </div>

            <div class="mt-5">
                <input id="filter-jarak" name="jarak" type="range" min="1" max="50" step="1" value="{{ $jarakNow }}" class="range" aria-label="Maksimal jarak (km)">
                <div class="flex items-center justify-between text-xs text-slate-500 mt-2">
                    <span>Radius 1 km</span>
                    <span class="text-slate-400 hidden sm:inline">Titik acuan: {{ $titik }}</span>
                    <span>Radius 50 km</span>
                </div>
            </div>

            <div class="flex flex-wrap items-center gap-2 mt-4">
                <span class="text-xs text-slate-400 mr-1">Pilihan Cepat:</span>
                @foreach ([2, 5, 10, 25, 50] as $km)
                    <button type="button" data-jarak="{{ $km }}" class="fpill">{{ $km }} km</button>
                @endforeach
            </div>

            <hr class="my-5 border-slate-100">

            <div class="flex items-center justify-between gap-3">
                <h3 class="flex items-center gap-3 text-sm font-bold text-slate-900">
                    <span class="w-7 h-7 rounded-lg bg-emerald-100 text-[#076653] flex items-center justify-center">
                        <x-icon name="tag" class="w-4 h-4" />
                    </span>
                    Harga Maksimal
                </h3>
                <span class="text-xs text-slate-400">Budget Tertinggi</span>
            </div>

            <div class="mt-4 flex items-center gap-3 h-12 px-3 rounded-xl border border-slate-200 focus-within:ring-2 focus-within:ring-[#076653]/25 transition">
                <span class="px-2.5 py-1 rounded-lg bg-slate-100 text-xs font-semibold text-slate-500">Rp</span>
                <input id="filter-harga-view" type="text" inputmode="numeric" autocomplete="off" placeholder="Tanpa batas"
                       class="flex-1 min-w-0 bg-transparent outline-none text-base font-bold text-slate-900 placeholder:text-slate-300 placeholder:font-normal">
                <button type="button" id="filter-harga-clear" aria-label="Hapus harga" class="text-slate-400 hover:text-slate-600">
                    <x-icon name="x" class="w-4 h-4" />
                </button>
                <input id="filter-harga" name="harga" type="hidden" value="{{ $hargaNow ?: '' }}">
            </div>

            <div class="flex flex-wrap gap-2 mt-4">
                <button type="button" data-harga="100000" class="fpill">& Rp 100 rb</button>
                <button type="button" data-harga="250000" class="fpill">& Rp 250 rb</button>
                <button type="button" data-harga="500000" class="fpill">& Rp 500 rb</button>
                <button type="button" data-harga="0" class="fpill">Semua Harga</button>
            </div>

            <div class="flex gap-3 mt-6">
                <a href="{{ url()->current() }}" class="flex-1 flex items-center justify-center gap-2 h-12 rounded-xl border border-slate-200 text-sm font-semibold text-slate-700 hover:bg-slate-50 transition">
                    <x-icon name="refresh" class="w-4 h-4" /> Reset
                </a>
                <button type="submit" class="flex-[1.5] flex items-center justify-center gap-2 h-12 rounded-xl bg-[#076653] text-sm font-bold text-white hover:bg-[#055443] transition">
                    <x-icon name="check" class="w-4 h-4" /> Terapkan Filter
                </button>
            </div>
        </div>
    </form>
</div>

@push('scripts')
    <script src="{{ asset('js/filter.js') }}"></script>
@endpush
