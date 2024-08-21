<?php

namespace App\Http\Middleware;

use App\Models\Akademik\TahunAjaran;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckYearAcedemic
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();
        if ($user->roles->pluck('name')[0] === 'camaba') {
            if ($user->register->tahun_ajaran_id !== TahunAjaran::getCurrent()['id']) {
                Auth::logout();

                return redirect()->route('login');
            } else {
                return $next($request);
            }
        } else {
            return $next($request);
        }
    }
}
