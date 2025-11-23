<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckKarirHomeAccess
{
    /**
     * Handle an incoming request.
     *
     * Middleware ini memastikan user hanya bisa akses form data diri
     * jika sudah mengunjungi halaman karir-home terlebih dahulu.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Cek apakah ada session flag 'visited_karir_home'
        if (!session()->has('visited_karir_home')) {
            // Jika belum mengunjungi karir.home, redirect ke sana
            return redirect()->route('karir.home')
                ->with('error', 'Silakan mulai dari halaman utama Peminatan Karir.');
        }

        // Jika sudah mengunjungi karir.home, lanjutkan
        return $next($request);
    }
}
