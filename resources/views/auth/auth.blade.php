@extends('layouts.guest')
@section('title', 'Sign In - Relora')
@section('content')
@php
    $startMode = ($mode ?? 'signin') === 'signup' || $errors->hasBag('register') ? 'signup' : 'signin';
@endphp
@include('partials.scene')

<main id="card" data-start="{{ $startMode }}" class="relative z-10 w-full max-w-[900px] bg-white rounded-[32px] overflow-hidden card-shadow">
    <div class="stage">
        <section id="pane-signin" class="pane pane-in">
            <h1 class="text-2xl font-bold text-slate-800 text-center mb-7">Sign In</h1>

            <form id="form-signin" method="POST" action="{{ route('login.store') }}" class="space-y-5" novalidate>
                @csrf
                <input name="identity" type="text" value="{{ old('identity') }}" placeholder="Username / Email" autocomplete="username"
                       class="input-field w-full text-base text-slate-700 px-5 py-3 rounded-xl">
                <input name="password" type="password" placeholder="Password" autocomplete="current-password"
                       class="input-field w-full text-base text-slate-700 px-5 py-3 rounded-xl">
                <div class="text-center"><a href="{{ route('password.reset') }}" class="text-sm text-slate-500 link-brand">Lupa Password?</a></div>

                @if ($errors->hasBag('login') && $errors->login->any())
                    <p class="msg error">{{ $errors->login->first() }}</p>
                @elseif (session('status'))
                    <p class="msg ok">{{ session('status') }}</p>
                @endif

                <button type="submit" class="btn-brand w-full text-white text-sm font-bold tracking-wide py-3 rounded-xl shadow-md">SIGN IN</button>
            </form>

            <p class="text-center text-sm text-slate-400 mt-6 mb-4">atau pakai akun lain</p>
            <div class="flex justify-center gap-4">
                <button type="button" aria-label="Google" class="w-12 h-12 rounded-full border border-gray-200 flex items-center justify-center hover:bg-gray-50 transition">
                    <svg width="19" height="19" viewBox="0 0 24 24"><path fill="#EA4335" d="M12 10.2v3.9h5.5c-.24 1.4-1.7 4.1-5.5 4.1-3.3 0-6-2.7-6-6.2s2.7-6.2 6-6.2c1.9 0 3.1.8 3.9 1.5l2.6-2.6C16.9 3.2 14.7 2.2 12 2.2 6.9 2.2 2.8 6.4 2.8 12s4.1 9.8 9.2 9.8c5.3 0 8.8-3.7 8.8-9 0-.6-.1-1.1-.2-1.6H12z"/></svg>
                </button>
                <button type="button" aria-label="Facebook" class="w-12 h-12 rounded-full border border-gray-200 flex items-center justify-center hover:bg-gray-50 transition">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="#1877F2"><path d="M22 12.06C22 6.5 17.52 2 12 2S2 6.5 2 12.06c0 5 3.66 9.16 8.44 9.94v-7.03H7.9v-2.9h2.54V9.85c0-2.5 1.5-3.9 3.8-3.9 1.1 0 2.24.2 2.24.2v2.46h-1.26c-1.24 0-1.63.77-1.63 1.56v1.88h2.78l-.44 2.9h-2.34V22c4.78-.78 8.44-4.93 8.44-9.94z"/></svg>
                </button>
            </div>

            <p class="text-center text-sm text-slate-500 mt-6 md:hidden">
                Belum punya akun? <a href="#signup" data-go="signup" class="font-semibold text-[#076653]">Sign Up</a>
            </p>
        </section>

        <section id="pane-signup" class="pane pane-up">
            <h1 class="text-2xl font-bold text-slate-800 text-center mb-6">Sign Up</h1>

            <form id="form-signup" method="POST" action="{{ route('register.store') }}" class="space-y-4" novalidate>
                @csrf
                <input name="nama" type="text" value="{{ old('nama') }}" placeholder="Nama" autocomplete="name"
                       class="input-field w-full text-base text-slate-700 px-5 py-3 rounded-xl">
                <input name="email" type="email" value="{{ old('email') }}" placeholder="Email" autocomplete="email"
                       class="input-field w-full text-base text-slate-700 px-5 py-3 rounded-xl">
                <input name="password" type="password" placeholder="Password (min. 8 karakter)" autocomplete="new-password"
                       class="input-field w-full text-base text-slate-700 px-5 py-3 rounded-xl">
                <input name="password_confirmation" type="password" placeholder="Konfirmasi Password" autocomplete="new-password"
                       class="input-field w-full text-base text-slate-700 px-5 py-3 rounded-xl">
                <label class="flex items-start gap-2 text-sm text-slate-500">
                    <input name="setuju" type="checkbox" value="1" @checked(old('setuju')) class="mt-1 accent-[#076653]">
                    <span>Saya setuju dengan syarat & ketentuan Relora.</span>
                </label>

                @if ($errors->hasBag('register') && $errors->register->any())
                    <p class="msg error">{{ $errors->register->first() }}</p>
                @endif

                <button type="submit" class="btn-brand w-full text-white text-sm font-bold tracking-wide py-3 rounded-xl shadow-md">SIGN UP</button>
            </form>

            <p class="text-center text-sm text-slate-500 mt-6 md:hidden">
                Sudah punya akun? <a href="#signin" data-go="signin" class="font-semibold text-[#076653]">Sign In</a>
            </p>
        </section>
    </div>

    <aside class="overlay panel-gradient hidden md:block">
        <svg class="absolute -bottom-8 -right-8 opacity-20" width="200" height="200" viewBox="0 0 24 24" fill="none" stroke="#0C342C" stroke-width="1"><path d="M6 8h12l1 12H5z"/><path d="M9 8a3 3 0 0 1 6 0"/></svg>

        <div id="promo-signin" class="promo promo-in">
            <h2 class="text-2xl font-extrabold text-[#0C342C] mb-4">Hello, Relora`s!</h2>
            <p class="text-sm text-[#0C342C]/80 leading-relaxed max-w-[300px]">Daftarkan data diri kamu apabila belum mempunyai akun untuk bisa menikmati dan menggunakan Relora yang dimana menjadi tempat COD para mahasiswa.</p>
            <button type="button" data-go="signup" class="mt-7 border-2 border-[#0C342C] text-[#0C342C] text-sm font-bold tracking-wide px-8 py-2.5 rounded-xl hover:bg-[#0C342C] hover:text-white transition">SIGN UP</button>
        </div>

        <div id="promo-signup" class="promo promo-up">
            <h2 class="text-2xl font-extrabold text-[#0C342C] mb-4">Welcome Back Relora`s!</h2>
            <p class="text-sm text-[#0C342C]/80 leading-relaxed max-w-[300px]">Sudah punya akun Relora belum nih? Kalau belum yuk masuk untuk bisa belanja dan jualan bareng dengan sesama mahasiswa.</p>
            <button type="button" data-go="signin" class="mt-7 border-2 border-[#0C342C] text-[#0C342C] text-sm font-bold tracking-wide px-8 py-2.5 rounded-xl hover:bg-[#0C342C] hover:text-white transition">SIGN IN</button>
        </div>
    </aside>
</main>
@endsection

@push('scripts')
    <script src="{{ asset('js/auth.js') }}"></script>
@endpush