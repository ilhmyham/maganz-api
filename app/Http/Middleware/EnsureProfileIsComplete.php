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

        $profileIncomplete = !$user->profile || !$user->profile->company_name;

        if ($user && $user->role_id == 2 && $profileIncomplete) {

            if (! $request->routeIs('profile.create') && ! $request->routeIs('profile.store') && ! $request->routeIs('logout')) {
                return redirect()->route('profile.create')->with('warning', 'Harap lengkapi profil perusahaan Anda terlebih dahulu.');
            }
        }
        return $next($request);
    }
}
