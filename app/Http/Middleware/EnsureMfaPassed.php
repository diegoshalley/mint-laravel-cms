<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureMfaPassed
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user()?->two_factor_confirmed_at && ! (bool) $request->session()->get('mfa_passed', false)) {
            return redirect()->route('mfa.challenge');
        }

        return $next($request);
    }
}
