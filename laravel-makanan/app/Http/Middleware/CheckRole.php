<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$roles
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!Auth::check()) {
            // Jika pengguna belum login, arahkan ke halaman login
            return redirect()->route('login');
        }

        $user = Auth::user();

        // Jika pengguna adalah 'master', izinkan akses ke mana pun
        if ($user->isMaster()) {
            return $next($request);
        }

        // Periksa apakah pengguna memiliki salah satu peran yang diizinkan
        if (in_array($user->role, $roles)) {
            return $next($request);
        }

        // Jika tidak memiliki peran yang diizinkan, arahkan ke dashboard
        // dan kirimkan session flash untuk memicu modal
        return redirect('/dashboard')->with('error', 'Anda tidak memiliki akses ke halaman ini.')->with('showAccessDeniedModal', true);
    }
}