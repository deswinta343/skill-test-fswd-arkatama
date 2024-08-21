<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckCamabaFormulirExist
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->user()->register?->formulir == null) {
            return redirect()->route('formulir-f07.identitas-diri.index')->with('error', 'Mohon untuk melengkapi identitas diri terlebih dahulu');
        } else {
            $formulir = auth()->user()->register->formulir->toArray();

            foreach ($formulir as $key => $value) {
                if ($key == 'status_kelulusan' || $key == 'deleted_at' || $key = 'keterangan') {
                    continue;
                }
                if ($value == null) {
                    return redirect()->route('formulir-f07.identitas-diri.index')->with('error', 'Mohon untuk melengkapi identitas diri terlebih dahulu');
                }
            }

        }

        return $next($request);
    }
}
