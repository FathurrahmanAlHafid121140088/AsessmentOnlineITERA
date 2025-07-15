<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckNimSession
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->session()->has('nim')) {
            return redirect()
                ->route('mental-health.data-diri')
                ->with('warning', 'Silakan isi data diri terlebih dahulu.');
        }

        return $next($request);
    }
}
