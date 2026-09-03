<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureMfaPassed
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user()?->mfa_enabled && ! $request->session()->boolean('mfa_passed')) {
            return redirect()->route('mfa.challenge');
        }

        return $next($request);
    }
}
