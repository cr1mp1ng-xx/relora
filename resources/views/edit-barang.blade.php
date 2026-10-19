@extends('layouts.app')

@section('title', 'Edit Barang - Relora')

@php
    $input = 'w-full h-12 px-4 rounded-xl bg-white border border-slate-200 text-sm text-slate-800 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-[#076653]/25 transition';

    $hargaTampil = number_format((int) preg_replace('/\D/', '', (string) old('harga', $produk->harga)), 0, ',', '.');
    $kondisiNow = old('kondisi', $produk->kondisi ?: 'bekas');
    $kategoriNow = old('kategori', $produk->kategori);
    $deskripsiNow = old('deskripsi', $produk->deskripsi);
    $areaNow = old('area', $produk->area);

    $bergabung = $penjual->created_at ? $penjual->created_at->locale('id')->translatedFormat('F Y') : '-';
    $infoPenjual = [
        ['icon' => 'calendar', 'label' => 'Bergabung sejak', 'nilai' => $bergabung],
        ['icon' => 'box', 'label' => 'Total barang dijual', 'nilai' => $totalTerjual . ' barang'],
        ['icon' => 'clock', 'label' => 'Waktu respons', 'nilai' => '< 1 jam'],
    ];
    $lokasiLengkap = $produk->area . ', Jawa Timur';
@endphp

@section('content')
    <form id="form-edit" method="POST" action="{{ route('barang.update', $produk->id) }}" novalidate>
        @csrf
        @method('PATCH')

        <div class="-mt-7 -mx-4 sm:-mx-7 mb-6 bg-white border-b border-slate-100 px-4 sm:px-7 h-14 flex items-center gap-5">
            <a href="{{ route('barang.saya') }}" aria-label="Kembali ke Barang Saya" class="text-slate-700 hover:text-[#076653] transition">
                <x-icon name="arrow-left" class="w-5 h-5" />
            </a>
            <h1 class="text-base font-bold text-slate-900">Edit Barang</h1>
        </div>

        <section class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 space-y-5">
            @if ($errors->any())
                <div class="rounded-xl bg-red-50 text-red-700 text-xs px-4 py-3">
                    <p class="font-semibold mb-1">Periksa kembali isian kamu:</p>
                    <ul class="list-disc pl-5 space-y-0.5">
                        @foreach (array_unique($errors->all()) as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div>
                <label for="judul" class="text-sm font-semibold text-slate-900">Nama Barang <span class="text-red-500">*</span></label>
                <input id="judul" name="judul" type="text" maxlength="100" value="{{ old('judul', $produk->judul) }}" class="{{ $input }} mt-2">
            </div>

            <div>
                <label for="harga" class="text-sm font-semibold text-slate-900">Harga <span class="text-red-500">*</span></label>
                <div
                    class="mt-2 flex h-12 rounded-xl border border-slate-200 overflow-hidden focus-within:ring-2 focus-within:ring-[#076653]/25 transition">
                    <span class="flex items-center px-4 bg-slate-50 border-r border-slate-200 text-xs font-medium text-slate-500">Rp</span>
                    <input id="harga" name="harga" type="text" inputmode="numeric" value="{{ $hargaTampil }}"
                        class="flex-1 min-w-0 px-4 bg-white outline-none text-sm text-slate-800">
                </div>
            </div>

            <div>
                <label for="kategori" class="text-sm font-semibold text-slate-900">Kategori <span class="text-red-500">*</span></label>
                <div class="relative mt-2">
                    <select id="kategori" name="kategori" class="{{ $input }} appearance-none pr-11">
                        <option value="" disabled @selected(!$kategoriNow)>Pilih kategori</option>
                        @foreach ($kategori as $k)
                            <option value="{{ $k }}" @selected($kategoriNow === $k)>{{ $k }}</option>
                        @endforeach
                    </select>
                    <x-icon name="chevron-down" class="w-4 h-4 text-slate-500 absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none" />
                </div>
            </div>

            <div>
                <p class="text-sm font-semibold text-slate-900">Kondisi <span class="text-red-500">*</span></p>
                <div class="mt-2 grid grid-cols-2 gap-4">
                    <label class="kopsi">
                        <input type="radio" name="kondisi" value="baru" class="sr-only" @checked($kondisiNow === 'baru')>
                        <span class="radio"></span> Baru
                    </label>
                    <label class="kopsi">
                        <input type="radio" name="kondisi" value="bekas" class="sr-only" @checked($kondisiNow === 'bekas')>
                        <span class="radio"></span> Bekas
                    </label>
                </div>
            </div>

            <div>
                <label for="deskripsi" class="text-sm font-semibold text-slate-900">Deskripsi <span class="text-red-500">*</span></label>
                <div class="relative mt-2">
                    <textarea id="deskripsi" name="deskripsi" maxlength="500"
                        class="w-full h-[116px] px-4 py-3 rounded-xl bg-white border border-slate-200 text-sm leading-relaxed text-slate-800 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-[#076653]/25 transition resize-none">{{ $deskripsiNow }}</textarea>
                    <span id="desc-counter" class="absolute bottom-3 right-4 text-[11px] text-slate-400">0/500</span>
                </div>
            </div>

            <div>
                <label for="area" class="text-sm font-semibold text-slate-900">Lokasi <span class="text-red-500">*</span></label>
                <div class="relative mt-2">
                    <x-icon name="pin" class="w-4 h-4 text-slate-400 absolute left-4 top-1/2 -translate-y-1/2" :filled="true" />
                    <input id="area" name="area" type="text" maxlength="100" value="{{ $areaNow }}"
                        class="{{ $input }} pl-11">
                </div>
            </div>
        </section>

        <h2 class="mt-8 mb-3 text-sm font-bold text-slate-900">Lokasi Barang</h2>
        <section class="bg-white rounded-2xl border border-slate-100 shadow-sm p-3">
            <div class="peta relative h-[188px] rounded-xl overflow-hidden bg-[#EEF2F7]">
                <svg viewBox="0 0 880 188" preserveAspectRatio="xMidYMid slice" class="absolute inset-0 w-full h-full"
                    aria-hidden="true">
                    <rect width="880" height="188" fill="#EEF2F7" />
                    <ellipse cx="470" cy="100" rx="360" ry="90" fill="#D1FAE5" opacity=".35" />
                    <polygon points="360,0 440,0 880,150 880,188 800,188" fill="#DCE3EC" />
                    <polygon points="150,188 230,188 450,0 390,0" fill="#E3E8F0" />
                    <polygon points="290,0 335,0 655,188 605,188" fill="#FCD9A8" />
                    <circle cx="437" cy="93" r="52" fill="#6EE7B7" fill-opacity=".12" stroke="#6EE7B7" />
                    <circle cx="437" cy="93" r="35" fill="#6EE7B7" fill-opacity=".25" stroke="#6EE7B7" />
                    <ellipse cx="293" cy="112" rx="4" ry="1.6" fill="#94A3B8"
                        opacity=".6" />
                    <circle cx="293" cy="91" r="15" fill="#0F5C50" />
                    <g transform="translate(286 84) scale(.6)" fill="none" stroke="#fff" stroke-width="2.4"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z" />
                        <circle cx="12" cy="10" r="3" />
                    </g>
                </svg>

                <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode($lokasiLengkap) }}" target="_blank"
                    rel="noopener" class="absolute right-3 bottom-3 inline-flex items-center gap-2 h-9 px-4 rounded-lg bg-white shadow text-xs font-bold text-slate-900 hover:bg-slate-50 transition">
                    Buka di Maps <x-icon name="arrow-right" class="w-4 h-4 text-[#076653]" />
                </a>
            </div>

            <div class="flex items-center gap-3 px-1 pt-4 pb-1">
                <span class="shrink-0 w-10 h-10 rounded-xl bg-emerald-50 text-[#076653] flex items-center justify-center">
                    <x-icon name="pin" class="w-5 h-5" :filled="true" />
                </span>
                <div>
                    <p class="text-sm font-bold text-slate-900">{{ $lokasiLengkap }}</p>
                    <p class="text-xs text-slate-500 mt-0.5">Jarak dari lokasi Anda: &plusmn; {{ (float) $produk->jarak }}
                        km</p>
                </div>
            </div>
        </section>

        <h2 class="mt-8 mb-3 text-sm font-bold text-slate-900">Informasi Penjual</h2>
        <section class="bg-white rounded-2xl border border-slate-100 shadow-sm px-5 py-4">
            <div class="flex items-center justify-between gap-4">
                <div class="flex items-center gap-3 min-w-0">
                    <span
                        class="brand-grad shrink-0 w-10 h-10 rounded-full flex items-center justify-center text-base font-bold text-[#0C342C]">
                        {{ mb_strtoupper(mb_substr($penjual->name, 0, 1)) }}
                    </span>
                    <div class="min-w-0">
                        <div class="flex flex-wrap items-center gap-2">
                            <p class="text-sm font-bold text-slate-900 uppercase truncate">{{ $penjual->name }}</p>
                            <span class="bg-emerald-100 text-[#076653] text-[10px] font-semibold px-2.5 py-0.5 rounded-full">Penjual Terpercaya</span>
                        </div>
                        <p class="flex items-center gap-1 text-xs text-slate-500 mt-1">
                            <x-icon name="star" class="w-3.5 h-3.5 text-amber-400" :filled="true" />
                            <b class="text-slate-900">4.9</b> (120 ulasan)
                        </p>
                    </div>
                </div>
                <x-icon name="chevron-right" class="w-5 h-5 text-slate-400 shrink-0" />
            </div>

            <dl class="mt-4 pt-4 border-t border-slate-100 space-y-3">
                @foreach ($infoPenjual as $info)
                    <div class="flex items-center justify-between gap-4 text-xs">
                        <dt class="flex items-center gap-2.5 text-slate-500">
                            <x-icon :name="$info['icon']" class="w-4 h-4 text-slate-400" /> {{ $info['label'] }}
                        </dt>
                        <dd class="text-slate-800">{{ $info['nilai'] }}</dd>
                    </div>
                @endforeach
            </dl>
        </section>

        <button type="submit" id="btn-simpan"
            class="mt-6 w-full h-12 rounded-xl bg-[#064032] text-sm font-bold text-white hover:bg-[#04352A] transition">
            Simpan Perubahan
        </button>
    </form>
@endsection

@push('scripts')
    <script src="{{ asset('js/edit-barang.js') }}"></script>
@endpush
