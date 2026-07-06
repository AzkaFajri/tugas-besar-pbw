<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class OwnerMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!session()->has('pegawai')) {
            return redirect()->route('login');
        }

        $pegawai = session()->get('pegawai');
        if ($pegawai['jabatan'] !== 'Owner') {
            abort(403, 'Unauthorized. Owner access required.');
        }

        return $next($request);
    }
}
