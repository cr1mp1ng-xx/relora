@extends('layouts.guest')

@section('title', 'Reset Password - Relora')

@section('content')
@include('partials.scene')

<div class="relative z-10 w-full max-w-[900px] flex flex-col md:flex-row bg-white rounded-[32px] overflow-hidden card-shadow">
    <div class="w-full md:w-1/2 px-8 sm:px-12 py-10 flex flex-col justify-center">
        <section class="view">
            <h1 class="text-2xl font-bold text-slate-800 text-center mb-2">Reset Password</h1>
            <p class="text-center text-sm text-slate-500 mb-6 max-w-[340px] mx-auto">
                Masukkan email akun kamu, lalu buat password baru.
            </p>

            <form id="form-reset" method="POST" action="{{ route('password.update') }}" class="space-y-4" novalidate>
                @csrf
                <input name="email" type="email" value="{{ old('email') }}" placeholder="Email" autocomplete="email"
                       class="input-field w-full text-base text-slate-700 px-5 py-3 rounded-xl">
                <input name="password" type="password" placeholder="Password baru (min. 8 karakter)" autocomplete="new-password"
                       class="input-field w-full text-base text-slate-700 px-5 py-3 rounded-xl">
                <input name="password_confirmation" type="password" placeholder="Konfirmasi Password baru" autocomplete="new-password"
                       class="input-field w-full text-base text-slate-700 px-5 py-3 rounded-xl">

                @php
                    $serverMsg = $errors->any() ? ['error', $errors->first()] : null;
                @endphp
                <p id="msg-reset" class="msg {{ $serverMsg[0] ?? '' }}" {{ $serverMsg ? '' : 'hidden' }}>{{ $serverMsg[1] ?? '' }}</p>

                <button id="btn-reset" type="submit" class="btn-brand w-full text-white text-sm font-bold tracking-wide py-3 rounded-xl shadow-md">
                    RESET PASSWORD
                </button>
            </form>

            <p class="text-center text-sm text-slate-500 mt-6">
                Ingat password kamu? <a href="{{ route('login') }}" class="font-semibold text-[#076653]">Kembali ke Sign In</a>
            </p>
        </section>
    </div>

    <div class="w-full md:w-1/2 panel-gradient px-10 py-12 hidden md:flex flex-col items-center justify-center text-center relative overflow-hidden">
        <svg class="absolute -bottom-8 -right-8 opacity-20" width="200" height="200" viewBox="0 0 24 24" fill="none" stroke="#0C342C" stroke-width="1"><path d="M6 8h12l1 12H5z"/><path d="M9 8a3 3 0 0 1 6 0"/></svg>

        <div class="view relative z-10 flex flex-col items-center">
            <h2 class="text-2xl font-extrabold text-[#0C342C] mb-4">Tenang, Relora`s!</h2>
            <p class="text-sm text-[#0C342C]/80 leading-relaxed max-w-[300px]">
                Lupa password itu biasa. Buat password baru langsung di sini, lalu kamu bisa lanjut belanja dan jualan bareng sesama mahasiswa.
            </p>
            <a href="{{ route('login') }}" class="mt-7 inline-block border-2 border-[#0C342C] text-[#0C342C] text-sm font-bold tracking-wide px-8 py-2.5 rounded-xl hover:bg-[#0C342C] hover:text-white transition">
                SIGN IN
            </a>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script src="{{ asset('js/reset.js') }}"></script>
@endpush