<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;

class AdminAuth
{
    public function handle($request, Closure $next)
    {
        if (!Auth::guard('admin')->check()) {
            return redirect('/login')->with('error', 'Silakan login sebagai admin.');
        }

        // Ambil timestamp terakhir aktivitas admin dari session
        $lastActivity = Session::get('last_activity_admin');
        $now = Carbon::now();

        if ($lastActivity && $now->diffInMinutes(Carbon::parse($lastActivity)) > 60) {
            Auth::guard('admin')->logout();
            Session::forget('last_activity_admin');

            return redirect('/login')
                ->with('expired', true)
                ->with('error', 'Sesi Anda telah habis karena tidak ada aktivitas selama 1 jam.');
        }

        // Perbarui waktu aktivitas terakhir
        Session::put('last_activity_admin', $now);

        return $next($request);
    }
}
