<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRmibConsent
{
    /**
     * Handle an incoming request.
     * Memastikan user sudah memberikan consent sebelum mengakses form data diri.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Cek apakah user sudah memberikan consent
        if (!session()->has('rmib_consent_accepted')) {
            // Redirect ke halaman consent jika belum
            return redirect()->route('karir.consent')
                ->with('warning', 'Anda harus menyetujui informed consent terlebih dahulu.');
        }

        return $next($request);
    }
}
