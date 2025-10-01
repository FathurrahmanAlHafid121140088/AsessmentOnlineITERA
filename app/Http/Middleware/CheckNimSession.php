<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckNimSession
{
    public function handle(Request $request, Closure $next): Response
    {
        // 2. Periksa apakah pengguna sudah login melalui sistem Auth
        if (Auth::check()) {
            // 3. Ambil NIM dari user yang login dan simpan ke session
            // Ini akan memastikan session('nim') selalu ada jika user sudah login.
            session(['nim' => Auth::user()->nim]);

            // Lanjutkan ke halaman yang dituju
            return $next($request);
        }

        // Jika pengguna belum login sama sekali, arahkan ke halaman data diri
        // (Meskipun middleware 'auth' seharusnya sudah menangani ini terlebih dahulu)
        return redirect()
            ->route('mental-health.isi-data-diri')
            ->with('warning', 'Silakan isi data diri terlebih dahulu.');
    }
}
