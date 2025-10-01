<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\Models\Users; // Atau User, sesuai nama model Anda
use App\Models\DataDiris;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Exception;

class AuthController extends Controller
{
    /**
     * Redirect the user to the Google authentication page.
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Obtain the user information from Google.
     */
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            $email = $googleUser->getEmail();
            $nim = null;

            if (preg_match('/(\d{9})@student\.itera\.ac\.id$/', $email, $matches)) {
                $nim = $matches[1];
            }

            if (!$nim) {
                return redirect('/login')->with('error', 'Login gagal. Pastikan Anda menggunakan email mahasiswa ITERA yang valid.');
            }

            // Cari atau buat user baru di tabel 'users'
            $user = Users::updateOrCreate(
                ['nim' => $nim],
                [
                    'name' => $googleUser->getName(),
                    'email' => $email,
                    'google_id' => $googleUser->getId(),
                    'password' => bcrypt(Str::random(16))
                ]
            );

            // Hanya membuat data diri jika belum ada, tidak menimpa yang sudah ada.
            DataDiris::firstOrCreate(
                ['nim' => $nim],
                [
                    'nama' => $googleUser->getName(),
                    'email' => $email,
                ]
            );

            Auth::login($user);

            // --- PERUBAHAN UTAMA DI SINI ---
            // Arahkan pengguna ke halaman yang mereka tuju sebelumnya.
            // Jika tidak ada, arahkan ke dashboard sebagai fallback.
            return redirect()->intended('/user/mental-health');

        } catch (Exception $e) {
            // Tampilkan error detail jika APP_DEBUG=true
            if (config('app.debug')) {
                throw $e;
            }
            return redirect('/login')->with('error', 'Terjadi kesalahan saat login via Google. Silakan coba lagi.');
        }
    }
}

