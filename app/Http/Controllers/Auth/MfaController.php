<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\AuditRecorder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Laravel\Fortify\Actions\ConfirmTwoFactorAuthentication;
use Laravel\Fortify\Actions\DisableTwoFactorAuthentication;
use Laravel\Fortify\Actions\EnableTwoFactorAuthentication;
use Laravel\Fortify\Contracts\TwoFactorAuthenticationProvider;

class MfaController extends Controller
{
    public function settings(Request $request): View
    {
        return view('auth.mfa-settings', ['user' => $request->user()]);
    }

    public function enable(Request $request, EnableTwoFactorAuthentication $enable, AuditRecorder $audit): RedirectResponse
    {
        $this->validatePassword($request);
        $enable($request->user());
        $audit->record('auth.mfa.enrollment_started', $request->user());
        return back()->with('status', 'Scan the QR code, then enter a code to confirm MFA.');
    }

    public function confirm(Request $request, ConfirmTwoFactorAuthentication $confirm, AuditRecorder $audit): RedirectResponse
    {
        $data = $request->validate(['code' => ['required', 'string', 'size:6']]);
        $confirm($request->user(), $data['code']);
        $request->session()->put('mfa_passed', true);
        $audit->record('auth.mfa.enabled', $request->user());
        return back()->with('status', 'Multi-factor authentication is active. Store the recovery codes securely.');
    }

    public function challenge(Request $request, TwoFactorAuthenticationProvider $provider, AuditRecorder $audit): RedirectResponse
    {
        $data = $request->validate(['code' => ['nullable', 'string'], 'recovery_code' => ['nullable', 'string']]);
        $user = $request->user();
        $valid = filled($data['code'] ?? null)
            ? $provider->verify(decrypt($user->two_factor_secret), $data['code'])
            : $this->consumeRecoveryCode($user, $data['recovery_code'] ?? '');
        if (! $valid) throw ValidationException::withMessages(['code' => 'The authentication code is invalid.']);
        $request->session()->put('mfa_passed', true);
        $audit->record('auth.mfa.challenge_passed', $user);
        return redirect()->intended(route('cms.dashboard'));
    }

    public function disable(Request $request, DisableTwoFactorAuthentication $disable, AuditRecorder $audit): RedirectResponse
    {
        $this->validatePassword($request);
        $disable($request->user());
        $request->session()->put('mfa_passed', true);
        $audit->record('auth.mfa.disabled', $request->user());
        return back()->with('status', 'Multi-factor authentication has been disabled.');
    }

    private function validatePassword(Request $request): void
    {
        $request->validate(['password' => ['required', 'string']]);
        if (! Hash::check($request->string('password'), $request->user()->password)) {
            throw ValidationException::withMessages(['password' => 'The password is incorrect.']);
        }
    }

    private function consumeRecoveryCode($user, string $code): bool
    {
        if ($code === '' || ! in_array($code, $user->recoveryCodes(), true)) return false;
        $user->replaceRecoveryCode($code);
        return true;
    }
}
