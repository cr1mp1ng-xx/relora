@php
    $menu = 'flex items-center gap-2 px-3.5 h-10 rounded-xl text-[13px] font-medium text-slate-600 hover:bg-slate-50 transition';
    $menuOn = 'flex items-center gap-2 px-3.5 h-10 rounded-xl text-[13px] font-bold bg-emerald-50 text-[#076653]';
@endphp

<header class="sticky top-0 z-30 bg-white border-b border-slate-100">
    <div class="w-full px-4 sm:px-7 h-[62px] flex items-center gap-3">
        <details class="relative shrink-0">
            <summary class="flex items-center gap-3 cursor-pointer select-none">
                <span class="brand-grad w-11 h-11 rounded-xl flex items-center justify-center text-[#0C342C] shadow-sm">
                    <x-icon name="bag" class="w-5 h-5" />
                </span>
                <span class="leading-tight">
                    <span class="block text-sm font-bold text-slate-900 truncate max-w-[120px] sm:max-w-[170px]">{{ auth()->user()->name }}</span>
                    <span class="block text-[11px] text-slate-500">Marketplace Lokal</span>
                </span>
            </summary>

            <div class="absolute left-0 top-14 w-52 bg-white border border-slate-100 rounded-xl shadow-lg p-2">
                <p class="px-3 py-2 text-xs text-slate-400 truncate">{{ auth()->user()->email }}</p>
                @if (Route::has('profil'))
                    <a href="{{ route('profil') }}" class="flex items-center gap-2 px-3 py-2 text-sm font-medium text-slate-700 rounded-lg hover:bg-slate-50">
                        <x-icon name="user" class="w-4 h-4" /> Profil Saya
                    </a>
                @endif
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-2 px-3 py-2 text-sm font-medium text-red-600 rounded-lg hover:bg-red-50">
                        <x-icon name="logout" class="w-4 h-4" /> Keluar
                    </button>
                </form>
            </div>
        </details>

        <div class="hidden lg:flex shrink-0 items-center gap-1.5 bg-white border border-slate-200 rounded-xl px-3 h-10 text-xs font-medium text-slate-700 whitespace-nowrap">
            <x-icon name="pin" class="w-3.5 h-3.5 text-[#076653]" :filled="true" />
            {{ $area ?? 'Malang, Jawa Timur' }}
            <x-icon name="chevron-down" class="w-3.5 h-3.5 text-slate-400" />
        </div>

        <form action="#" method="GET" class="hidden md:flex flex-1 min-w-0 max-w-[290px] relative">
            <x-icon name="search" class="w-4 h-4 text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2" />
            <input type="text" name="q" placeholder="Cari barang bekas/baru di sekitar..."
                class="w-full h-10 pl-10 pr-3 rounded-xl bg-slate-100 text-xs text-slate-700 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-[#076653]/20">
        </form>

        @if (request()->routeIs('dashboard', 'wishlist'))
            <button type="button" data-open-filter
                class="relative flex shrink-0 items-center gap-1.5 h-10 px-3.5 rounded-xl bg-slate-100 text-xs font-semibold text-slate-700 hover:bg-slate-200 transition">
                <x-icon name="sliders" class="w-3.5 h-3.5 text-[#076653]" />
                <span class="hidden sm:inline">Filter</span>
                @if (($jarak ?? 50) < 50 || ($harga ?? 0) > 0)
                    <span class="absolute -top-1 -right-1 w-2.5 h-2.5 rounded-full bg-[#076653] ring-2 ring-white"></span>
                @endif
            </button>
        @endif

        <nav class="ml-auto flex items-center gap-1">
            <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard', 'profil') ? $menuOn : $menu }}">
                <x-icon name="home" class="w-4 h-4 {{ request()->routeIs('dashboard', 'profil') ? 'text-[#076653]' : 'text-slate-500' }}" :filled="true" />
                <span class="hidden lg:inline">Beranda</span>
            </a>

            <a href="{{ route('wishlist') }}" class="{{ request()->routeIs('wishlist') ? $menuOn : $menu }}">
                <x-icon name="heart" class="w-4 h-4 {{ request()->routeIs('wishlist') ? 'text-red-500' : 'text-slate-600' }}" :filled="true" />
                <span class="hidden lg:inline">Wishlist</span>
                <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>
            </a>

            <a href="#" class="{{ $menu }}">
                <x-icon name="chat" class="w-4 h-4 text-slate-500" :filled="true" />
                <span class="hidden lg:inline">Chat</span>
                <span class="w-1.5 h-1.5 rounded-full bg-[#076653]"></span>
            </a>

            <a href="{{ route('barang.saya') }}"
                class="brand-grad ml-1 flex items-center gap-2 px-4 h-10 rounded-xl text-[13px] font-bold text-[#0C342C] shadow-sm hover:brightness-105 transition {{ request()->routeIs('jual', 'barang.saya', 'barang.edit') ? 'ring-4 ring-emerald-200/80' : '' }}">
                <x-icon name="plus-circle" class="w-4 h-4" />
                <span class="hidden sm:inline">Jual Barang</span>
            </a>
        </nav>
    </div>
</header>
