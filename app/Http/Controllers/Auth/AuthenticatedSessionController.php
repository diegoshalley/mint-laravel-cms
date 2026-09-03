<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\AuditRecorder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    public function create(): View { return view('auth.login'); }

    public function store(Request $request, AuditRecorder $audit): RedirectResponse
    {
        $credentials = $request->validate(['email' => ['required', 'email'], 'password' => ['required', 'string']]);
        $credentials['is_active'] = true;
        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()->withErrors(['email' => 'The supplied credentials are invalid.'])->onlyInput('email');
        }
        $request->session()->regenerate();
        $request->session()->put('mfa_passed', ! $request->user()->two_factor_confirmed_at);
        $request->user()->forceFill(['last_login_at' => now()])->save();
        $audit->record('auth.login.succeeded', $request->user());
        if ($request->user()->two_factor_confirmed_at) return redirect()->route('mfa.challenge');
        return redirect()->intended(route('cms.dashboard'));
    }

    public function destroy(Request $request, AuditRecorder $audit): RedirectResponse
    {
        $audit->record('auth.logout', $request->user());
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('home');
    }
}
