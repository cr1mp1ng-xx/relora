<button type="button" data-ubah-status title="Ubah status barang"
    class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-semibold hover:brightness-95 transition {{ $st['pill'] }}">
    @if ($st['icon'])
        <x-icon :name="$st['icon']" class="w-3 h-3" />
    @endif
    {{ $st['label'] }}
</button>

<div class="ml-auto flex items-center gap-1.5">
    @foreach ($st['aksi'] as $aksi)
        @php $kelas = 'h-8 px-3 rounded-lg inline-flex items-center text-xs font-semibold transition ' . $tombol[$aksi]; @endphp
        @if ($aksi === 'Jual Lagi')
            <button type="button" data-aksi="jual-lagi" class="{{ $kelas }}">{{ $aksi }}</button>
        @elseif ($aksi === 'Arsip')
            <button type="button" data-aksi="arsip" class="{{ $kelas }}">{{ $aksi }}</button>
        @else
            <a href="{{ $aksi === 'Edit' ? $editUrl ?? '#' : '#' }}" @if ($aksi === 'Edit') data-edit @endif class="{{ $kelas }}">{{ $aksi }}</a>
        @endif
    @endforeach
</div>
