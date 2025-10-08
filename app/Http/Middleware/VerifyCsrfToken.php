<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;
use Illuminate\Support\Facades\Log; // 1. Tambahkan Log facade
use Closure;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        //
    ];

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // 2. Tambahkan blok ini HANYA untuk request POST ke data diri Anda
        if ($request->isMethod('post') && $request->is('mental-health/isi-data-diri')) {
            $sessionToken = $request->session()->token();
            $requestToken = $request->input('_token');

            Log::info('--- CSRF DEBUG START ---');
            Log::info('Session ID on POST: ' . $request->session()->getId());
            Log::info('Token In Session: ' . $sessionToken);
            Log::info('Token From Request: ' . $requestToken);
            Log::info('Tokens Match?: ' . ($sessionToken === $requestToken ? 'Yes' : 'No'));
            Log::info('--- CSRF DEBUG END ---');
        }

        return parent::handle($request, $next);
    }
}

