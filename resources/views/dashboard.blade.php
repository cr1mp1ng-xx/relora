@extends('layouts.guest')
@section('title', 'Dashboard - Relora')
@section('content')
<div class="bg-white rounded-3xl shadow p-10 text-center">
    <h1 class="text-2xl font-bold mb-2">Halo, {{ auth()->user()->name }}!</h1>
    <p class="text-slate-500 mb-6">Kamu berhasil login ke Relora.</p>
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button class="px-6 py-3 rounded-xl bg-[#076653] text-white font-semibold">Logout</button>
    </form>
</div>
@endsection