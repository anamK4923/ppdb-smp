<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Debug: Cek apakah user sudah login
        if (!Auth::check()) {
            return redirect('/login')->with('error', 'Silakan login terlebih dahulu');
        }

        $user = Auth::user();

        // Debug: Cek role user
        if ($user->role !== 'admin') {
            return redirect('/')->with('error', 'Akses ditolak. Anda bukan admin.');
        }

        return $next($request);
    }
}
