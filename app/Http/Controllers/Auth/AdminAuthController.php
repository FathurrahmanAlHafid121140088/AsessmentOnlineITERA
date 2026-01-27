<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminAuthController extends Controller
{
    public function showLoginForm()
    {
        session(['title' => 'Login']);
        return view('login'); // sesuaikan nama file view login
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        // HAPUS ATAU KOMENTARI TRY-CATCH UNTUK MELIHAT ERROR ASLI
        // try { 

        if (Auth::guard('admin')->attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended(route('admin.home'));
        }

        return redirect()->back()
            ->withInput($request->only('email'))
            ->withErrors(['email' => 'Login Gagal! Email atau Password salah.']);

        // } catch (\Exception $e) {
        //     // KITA MATIKAN REDIRECT SWEETALERT, GANTI JADI INI:
        //     dd("ERROR ASLI: " . $e->getMessage()); 
        // }
    }

    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login')->with('success', 'Anda berhasil logout.');
    }
}
