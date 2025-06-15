<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureProfileIsComplete
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        // Kita definisikan "profil tidak lengkap" sebagai:
        // 1. Relasi profile belum ada, ATAU
        // 2. Kolom company_name masih kosong.
        $profileIncomplete = !$user->profile || !$user->profile->company_name;

        // Cek hanya jika user adalah perusahaan (misal role_id = 2)
        // dan profilnya tidak lengkap
        if ($user && $user->role_id == 2 && $profileIncomplete) {

            // PENTING: Beri pengecualian agar tidak terjadi redirect loop.
            // Pengguna harus bisa mengakses halaman untuk melengkapi profil dan halaman logout.
            if (! $request->routeIs('profile.create') && ! $request->routeIs('profile.store') && ! $request->routeIs('logout')) {
                // Jika mencoba akses halaman lain, paksa redirect ke halaman lengkapi profil
                return redirect()->route('profile.create')->with('warning', 'Harap lengkapi profil perusahaan Anda terlebih dahulu.');
            }
        }

        // Jika semua syarat terpenuhi, izinkan pengguna melanjutkan ke halaman tujuannya.
        return $next($request);
    }
}
