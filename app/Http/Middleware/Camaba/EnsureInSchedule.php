<?php

namespace App\Http\Middleware\Camaba;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureInSchedule
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $message = 'Mohon maaf, pendaftaran telah ditutup. Silakan hubungi panitia PMB untuk bantuan lebih lanjut';
        if (! checkJadwalPendaftaran()) {
            if ($request->expectsJson()) {
                return \ResponseFormatter::error($message);
            } else {
                return redirect()->route('dashboard')->with('error', $message);
            }
        }

        return $next($request);
    }
}
