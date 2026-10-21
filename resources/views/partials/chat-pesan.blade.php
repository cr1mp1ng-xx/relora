@php
    $tipe = $p['tipe'] ?? 'teks';
    $data = $p['data'] ?? [];
@endphp

@if ($tipe === 'ringkasan')
    <div class="flex justify-center py-1" data-pesan>
        <div class="w-full max-w-[440px] rounded-2xl border border-emerald-300 bg-emerald-50 p-4 flex gap-3">
            <span class="shrink-0 w-9 h-9 rounded-lg bg-[#076653] text-white flex items-center justify-center">
                <x-icon name="pin" class="w-4 h-4" :filled="true" />
            </span>
            <div class="min-w-0 flex-1 text-xs leading-5">
                <div class="flex items-start justify-between gap-2">
                    <p class="text-[13px] font-bold text-slate-900">Ringkasan Janji Temu COD</p>
                    <span data-status class="shrink-0 bg-emerald-100 text-[#076653] text-[10px] font-semibold px-2 py-0.5 rounded">{{ $data['status'] ?? '' }}</span>
                </div>
                <p class="mt-1 text-slate-800"><b>Lokasi:</b> <span data-lokasi>{{ $data['lokasi'] ?? '' }}</span></p>
                <p class="text-slate-800"><b>Waktu:</b> <span data-waktu>{{ $data['waktu'] ?? '' }}</span></p>
                <p class="font-semibold text-[#076653]">Siapkan uang pas: <span data-uang>{{ $data['uang'] ?? '' }}</span></p>
            </div>
        </div>
    </div>
@elseif (($p['dari'] ?? '') === 'saya')
    <div class="flex justify-end" data-pesan>
        <div class="max-w-[78%]">
            <div class="bubble-saya rounded-2xl rounded-tr-md px-4 py-3 shadow-sm">
                <p data-isi class="text-sm leading-relaxed whitespace-pre-wrap break-words">{{ $p['teks'] ?? '' }}</p>
                <p class="mt-1 text-right text-[10px] opacity-60"><span data-jam>{{ $p['jam'] ?? '' }}</span> &#10003;&#10003;</p>
            </div>
        </div>
    </div>
@else
    <div class="flex items-start gap-3" data-pesan>
        <span class="shrink-0 w-9 h-9 rounded-full bg-[#076653] text-white text-sm font-bold flex items-center justify-center">{{ $inisial }}</span>
        <div class="max-w-[78%] rounded-2xl rounded-tl-md bg-white border border-slate-200 px-4 py-3 shadow-sm">
            <p data-isi class="text-sm leading-relaxed text-slate-800 whitespace-pre-wrap break-words">{{ $p['teks'] ?? '' }}</p>
            <p class="mt-1 text-right text-[10px] text-slate-400" data-jam>{{ $p['jam'] ?? '' }}</p>
        </div>
    </div>
@endif
