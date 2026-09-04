<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\AuditRecorder;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password as PasswordRule;
use Illuminate\View\View;

class PasswordResetController extends Controller
{
    public function requestForm(): View { return view('auth.forgot-password'); }

    public function sendLink(Request $request, AuditRecorder $audit): RedirectResponse
    {
        $data = $request->validate(['email' => ['required', 'email']]);
        $user = User::query()->where('email', $data['email'])->where('is_active', true)->first();
        if ($user) {
            Password::sendResetLink(['email' => $user->email]);
            $audit->record('auth.password_reset.requested', null, $user);
        }
        return back()->with('status', 'If an active account matches that address, password-reset instructions have been sent.');
    }

    public function resetForm(Request $request, string $token): View
    {
        return view('auth.reset-password', ['token' => $token, 'email' => $request->string('email')->toString()]);
    }

    public function reset(Request $request, AuditRecorder $audit): RedirectResponse
    {
        $data = $request->validate([
            'token' => ['required'], 'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', PasswordRule::min(12)->mixedCase()->numbers()->symbols()],
        ]);
        if (! User::query()->where('email', $data['email'])->where('is_active', true)->exists()) {
            return back()->withInput($request->only('email'))->withErrors(['email' => __('passwords.token')]);
        }
        $status = Password::reset($data, function (User $user, string $password) use ($audit): void {
            DB::transaction(function () use ($user, $password, $audit): void {
                $user->forceFill(['password' => Hash::make($password), 'remember_token' => Str::random(60), 'must_change_password' => false])->save();
                DB::table('sessions')->where('user_id', $user->id)->delete();
                $audit->record('auth.password_reset.completed', $user, $user);
            });
            event(new PasswordReset($user));
        });
        if ($status !== Password::PASSWORD_RESET) return back()->withInput($request->only('email'))->withErrors(['email' => __($status)]);
        return redirect()->route('login')->with('status', 'Password reset. Sign in again and complete MFA.');
    }
}
