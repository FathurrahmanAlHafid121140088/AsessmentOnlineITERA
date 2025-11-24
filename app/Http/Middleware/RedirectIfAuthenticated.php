<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                // Redirect berdasarkan guard yang digunakan
                if ($guard === 'admin') {
                    return redirect('/admin/mental-health')->with('success', 'Anda sudah login sebagai admin!');
                }
                // Untuk guard web atau null (user biasa)
                return redirect('/user/mental-health')->with('success', 'Anda sudah login!');
            }
        }

        return $next($request);
    }
}
