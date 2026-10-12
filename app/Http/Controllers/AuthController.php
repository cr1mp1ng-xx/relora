<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller {
    public function showLogin()
    {
        return view('auth.auth', ['mode' => 'signin']);
    }

    public function showRegister()
    {
        return view('auth.auth', ['mode' => 'signup']);
    }

    public function login(Request $request)
    {
        $request->validateWithBag('login', [
            'identity' => ['required'],
            'password' => ['required'],
        ], [
            'identity.required' => 'Isi username/email dan password dulu.',
            'password.required' => 'Isi username/email dan password dulu.',
        ]);

        $field = filter_var($request->identity, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        if (Auth::attempt([$field => $request->identity, 'password' => $request->password])) {
            $request->session()->regenerate();
            return redirect()->intended(route('dashboard'));
        }

        return back()
            ->withErrors(['identity' => 'Username/email atau password salah.'], 'login')
            ->onlyInput('identity');
    }

    public function register(Request $request)
    {
        $request->validateWithBag('register', [
            'nama'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'min:8', 'confirmed'],
            'setuju'   => ['accepted'],
        ], [
            'nama.required'      => 'Lengkapi semua data dulu.',
            'email.required'     => 'Lengkapi semua data dulu.',
            'email.email'        => 'Format email belum benar.',
            'email.unique'       => 'Email sudah terdaftar.',
            'password.required'  => 'Lengkapi semua data dulu.',
            'password.min'       => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak sama.',
            'setuju.accepted'    => 'Centang persetujuan syarat & ketentuan.',
        ]);

        User::create([
            'name'     => $request->nama,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('login')->with('status', 'Akun berhasil dibuat. Silakan Sign In.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}