<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class PasswordResetController extends Controller {
    public function showReset() {
        return view('auth.reset-password');
    }

    public function reset(Request $request) {
        $request->validate([
            'email'    => ['required', 'email', 'exists:users,email'],
            'password' => ['required', 'min:8', 'confirmed'],
        ], [
            'email.required'     => 'Isi email kamu dulu.',
            'email.email'        => 'Format email belum benar.',
            'email.exists'       => 'Email tidak terdaftar.',
            'password.required'  => 'Isi password baru dulu.',
            'password.min'       => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak sama.',
        ]);

        $user = User::where('email', $request->email)->firstOrFail();
        $user->password = Hash::make($request->password);
        $user->setRememberToken(Str::random(60));
        $user->save();

        return redirect()->route('login')->with('status', 'Password berhasil diubah. Silakan Sign In.');
    }
}