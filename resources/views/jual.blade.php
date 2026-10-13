@extends('layouts.app')

@section('title', 'Jual Barang - Relora')

@php
    $input = 'w-full h-12 px-4 rounded-2xl bg-slate-50 border border-slate-200 text-sm text-slate-800 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-[#076653]/25 focus:bg-white transition';
@endphp

@section('footer_links')
    <a href="#" class="hover:text-[#076653]">Ketentuan Layanan</a>
    <a href="#" class="hover:text-[#076653]">Panduan Aman COD</a>
    <a href="#" class="hover:text-[#076653]">Bantuan</a>
@endsection

@section('content')
<form id="form-jual" method="POST" action="{{ route('jual.store') }}" enctype="multipart/form-data" novalidate
      class="max-w-[1400px] mx-auto bg-white rounded-[28px] border border-slate-100 shadow-sm p-6 sm:p-10">
    @csrf

    @if ($errors->any())
        <div class="mb-6 rounded-xl bg-red-50 text-red-700 text-xs px-4 py-3">
            <p class="font-semibold mb-1">Periksa kembali isian kamu:</p>
            <ul class="list-disc pl-5 space-y-0.5">
                @foreach (array_unique($errors->all()) as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            @if ($errors->has('foto') || $errors->has('foto.*'))
                <p class="mt-1">Foto perlu dipilih ulang setelah halaman dimuat ulang.</p>
            @endif
        </div>
    @endif

    <div class="grid lg:grid-cols-2 gap-x-10 gap-y-8">
        <div class="space-y-6">
            <div>
                <div class="flex items-center justify-between gap-3">
                    <h2 class="flex items-center gap-2 text-sm font-bold text-slate-900">
                        <x-icon name="image" class="w-4 h-4 text-[#076653]" /> Foto Produk
                    </h2>
                    <span class="text-xs text-slate-400">Maksimal 5 foto (Format JPG/PNG)</span>
                </div>

                <div id="drop" class="mt-3 rounded-[28px] border-2 border-dashed border-emerald-400/70 bg-emerald-50/60 p-5 transition">

                    <div id="empty-state" class="flex flex-col items-center justify-center text-center py-12">
                        <span class="w-14 h-14 rounded-full bg-white shadow-sm flex items-center justify-center text-[#076653]">
                            <x-icon name="upload" class="w-6 h-6" />
                        </span>
                        <p class="mt-3 text-sm font-semibold text-slate-800">Seret foto ke sini atau pilih dari perangkat</p>
                        <p class="text-xs text-slate-400 mt-1">JPG / PNG, maksimal 2 MB per foto</p>
                        <button type="button" id="btn-pilih" class="mt-4 inline-flex items-center gap-2 h-10 px-5 rounded-full bg-white border border-emerald-200 text-xs font-semibold text-[#076653] hover:bg-emerald-50 transition">
                            <x-icon name="camera" class="w-4 h-4" /> Pilih Foto
                        </button>
                    </div>

                    <div id="preview-state" class="hidden">
                        <div class="relative rounded-2xl overflow-hidden h-[207px] bg-slate-200">
                            <img id="main-preview" src="" alt="Pratinjau foto produk" class="w-full h-full object-cover">
                            <span class="absolute top-3 right-3 inline-flex items-center gap-1.5 bg-slate-900/85 text-white text-[11px] font-semibold px-3 py-1.5 rounded-lg">
                                <x-icon name="check" class="w-3 h-3" /> Foto Terunggah
                            </span>
                        </div>
                        <div id="thumbs" class="flex flex-wrap justify-center gap-2 mt-3"></div>
                    </div>

                    <div id="photo-actions" class="hidden mt-4 flex flex-wrap justify-center gap-3">
                        <button type="button" id="btn-ganti"
                                class="inline-flex items-center gap-2 h-10 px-4 rounded-full bg-white border border-slate-200 text-xs font-semibold text-[#076653] hover:bg-slate-50 transition">
                            <x-icon name="camera" class="w-4 h-4" /> Ganti Foto Produk
                        </button>
                        <button type="button" id="btn-tambah"
                                class="inline-flex items-center gap-2 h-10 px-4 rounded-full bg-white border border-slate-200 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition">
                            <x-icon name="plus" class="w-4 h-4" /> Tambah Foto Lain
                        </button>
                    </div>

                    <p class="text-center text-xs text-slate-500 mt-4">Foto asli & jelas itu meningkatkan respon pembeli hingga 3x lipat.</p>
                    <p id="foto-msg" class="text-center text-xs text-red-600 mt-2 hidden"></p>
                </div>

                <input type="file" id="foto" name="foto[]" multiple accept="image/jpeg,image/png" class="hidden">
                <input type="file" id="picker" multiple accept="image/jpeg,image/png" class="hidden">
            </div>

            <div>
                <label for="judul" class="text-sm font-bold text-slate-900">Judul Barang</label>
                <input id="judul" name="judul" type="text" value="{{ old('judul') }}" maxlength="100"
                       placeholder="Contoh: Jaket Parka Outdoor Pria Hijau Army" class="{{ $input }} mt-2">
                <p class="text-xs text-slate-400 mt-2">Sertakan merek, jenis barang, atau warna utama.</p>
            </div>

            <div>
                <label for="harga" class="text-sm font-bold text-slate-900">Harga Jual (Rp)</label>
                <div class="mt-2 flex items-center gap-3 h-12 px-4 rounded-2xl bg-slate-50 border border-slate-200 focus-within:ring-2 focus-within:ring-[#076653]/25 focus-within:bg-white transition">
                    <span class="text-sm font-semibold text-slate-500">Rp</span>
                    <input id="harga" name="harga" type="text" inputmode="numeric" value="{{ old('harga') }}" placeholder="150000"
                           class="flex-1 min-w-0 bg-transparent outline-none text-base font-bold text-[#076653] placeholder:text-slate-300 placeholder:font-normal">
                </div>

                <div class="flex gap-2 mt-3">
                    <label class="cursor-pointer">
                        <input type="radio" name="nego" value="pas" class="sr-only peer" @checked(old('nego') === 'pas')>
                        <span class="inline-block px-3 py-1.5 rounded-lg text-[11px] font-semibold bg-slate-100 text-slate-600 peer-checked:bg-emerald-100 peer-checked:text-[#076653] transition">Harga Pas</span>
                    </label>
                    <label class="cursor-pointer">
                        <input type="radio" name="nego" value="nego" class="sr-only peer" @checked(old('nego', 'nego') === 'nego')>
                        <span class="inline-block px-3 py-1.5 rounded-lg text-[11px] font-semibold bg-slate-100 text-slate-600 peer-checked:bg-emerald-100 peer-checked:text-[#076653] transition">Bisa Nego Tipis</span>
                    </label>
                </div>
            </div>
        </div>

        <div class="space-y-6">

            <div class="grid grid-cols-[1.25fr_1fr] gap-4">
                <div>
                    <label for="area" class="flex items-center gap-2 text-sm font-bold text-slate-900">
                        <x-icon name="pin" class="w-4 h-4 text-[#076653]" :filled="true" /> Area COD / Kecamatan
                    </label>
                    <input id="area" name="area" type="text" value="{{ old('area') }}" placeholder="Contoh: Klojen, Malang" class="{{ $input }} mt-2">
                </div>
                <div>
                    <label for="jarak" class="flex items-center gap-2 text-sm font-bold text-slate-900">
                        <x-icon name="route" class="w-4 h-4 text-[#076653]" /> Estimasi Jarak (km)
                    </label>
                    <input id="jarak" name="jarak" type="text" inputmode="decimal" value="{{ old('jarak') }}" placeholder="2.5" class="{{ $input }} mt-2">
                </div>
            </div>

            <div>
                <div class="flex items-center justify-between gap-3">
                    <label for="deskripsi" class="text-sm font-bold text-slate-900">Deskripsi Barang (Sertakan Ukuran/Kondisi)</label>
                    <span id="desc-counter" class="text-xs text-slate-400 whitespace-nowrap">Minimal 30 karakter</span>
                </div>
                <textarea id="deskripsi" name="deskripsi" rows="5"
                          placeholder="Contoh: Jaket parka original warna army, ukuran L, kondisi masih sangat baik dan terawat..."
                          class="w-full mt-2 px-4 py-3 rounded-2xl bg-slate-50 border border-slate-200 text-xs leading-relaxed text-slate-700 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-[#076653]/25 focus:bg-white transition resize-none h-[150px]">{{ old('deskripsi') }}</textarea>
            </div>

            <div class="flex items-start gap-3 rounded-2xl border border-emerald-200 bg-emerald-50/60 p-4">
                <span class="shrink-0 w-9 h-9 rounded-lg bg-[#076653] text-white flex items-center justify-center">
                    <x-icon name="handshake" class="w-4 h-4" />
                </span>
                <div>
                    <h3 class="text-sm font-bold text-[#0C342C]">Panduan Transaksi COD Aman</h3>
                    <p class="text-xs text-slate-600 leading-relaxed mt-1">
                        Pilih tempat pertemuan publik yang ramai seperti kafe atau SPBU. Cek kondisi fisik barang bersama
                        sebelum pembeli membayar tunai atau QRIS di tempat.
                    </p>
                </div>
            </div>

            <div class="border-t border-slate-100 pt-5 flex gap-3">
                <button type="submit" name="aksi" value="draf" class="h-12 px-6 rounded-xl bg-slate-100 text-xs font-semibold text-slate-700 hover:bg-slate-200 transition">
                    Simpan Draf
                </button>
                <button type="submit" name="aksi" value="upload" class="hero-grad flex-1 h-12 rounded-xl ring-2 ring-emerald-300/60 shadow-md flex items-center justify-center gap-2 text-sm font-bold text-[#0C342C] hover:brightness-105 transition">
                    <x-icon name="check" class="w-4 h-4" /> Upload Produk
                </button>
            </div>
        </div>
    </div>
</form>
@endsection

@push('scripts')
    <script src="{{ asset('js/jual.js') }}"></script>
@endpush