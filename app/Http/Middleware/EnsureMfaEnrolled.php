<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureMfaEnrolled
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user()?->two_factor_confirmed_at) return redirect()->route('mfa.settings')->with('status', 'MFA enrollment is required before accessing the CMS.');
        return $next($request);
    }
}
