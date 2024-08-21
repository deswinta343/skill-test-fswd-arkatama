<?php

namespace App\Http\Middleware\Camaba;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsurePaymentIsCompleted
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $message = 'Mohon untuk melakukan pembayaran terlebih dahulu';
        if (! checkIfPembayaranLunas()) {
            if ($request->expectsJson()) {
                return \ResponseFormatter::error($message);
            } else {
                return redirect()->route('dashboard')->with('error', $message);
            }
        }

        return $next($request);
    }
}
