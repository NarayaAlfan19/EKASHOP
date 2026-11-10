<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        // Cek apakah user login dan rolenya adalah 'admin'
        if (Auth::check() && Auth::user()->role === 'admin') {
            return $next($request);
        }

        // Jika gagal, logout dan arahkan kembali ke halaman login dengan pesan error
        Auth::logout();
        
        return redirect()->route('admin.login')->withErrors([
            'email' => 'Anda tidak memiliki akses ke area ini.'
        ]);
    }
}