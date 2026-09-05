<?php

use App\Models\AuditEvent;
use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

it('uses the same response for known and unknown password-reset addresses', function () {
    Notification::fake();
    $user = User::factory()->create();
    $known = $this->post(route('password.email'), ['email' => $user->email]);
    $unknown = $this->post(route('password.email'), ['email' => 'missing@example.gov.gh']);

    expect($known->getSession()->get('status'))->toBe($unknown->getSession()->get('status'));
    Notification::assertSentTo($user, ResetPassword::class);
});

it('does not issue recovery links for disabled accounts', function () {
    Notification::fake();
    $user = User::factory()->create(['is_active' => false]);
    $this->post(route('password.email'), ['email' => $user->email])->assertSessionHas('status');
    Notification::assertNothingSent();
});

it('resets a password once, revokes sessions and preserves MFA', function () {
    Notification::fake();
    $user = User::factory()->create([
        'password' => Hash::make('Old-Password-2026!'),
        'two_factor_secret' => encrypt('MFA-SECRET'), 'two_factor_confirmed_at' => now(),
    ]);
    DB::table('sessions')->insert(['id' => 'old-session', 'user_id' => $user->id, 'payload' => 'x', 'last_activity' => time()]);
    $token = null;
    $this->post(route('password.email'), ['email' => $user->email]);
    Notification::assertSentTo($user, ResetPassword::class, function (ResetPassword $notification) use (&$token): bool {
        $token = $notification->token;
        return true;
    });

    $payload = ['token' => $token, 'email' => $user->email, 'password' => 'New-Secure-Password-2026!', 'password_confirmation' => 'New-Secure-Password-2026!'];
    $this->post(route('password.update'), $payload)->assertRedirect(route('login'));
    expect(Hash::check($payload['password'], $user->refresh()->password))->toBeTrue()
        ->and($user->two_factor_confirmed_at)->not->toBeNull()
        ->and(DB::table('sessions')->where('user_id', $user->id)->exists())->toBeFalse()
        ->and(AuditEvent::where('action', 'auth.password_reset.completed')->exists())->toBeTrue();

    $this->post(route('password.update'), $payload)->assertSessionHasErrors('email');
});

it('rejects weak replacement passwords', function () {
    $user = User::factory()->create();
    $this->post(route('password.update'), [
        'token' => 'invalid', 'email' => $user->email, 'password' => 'weakpassword', 'password_confirmation' => 'weakpassword',
    ])->assertSessionHasErrors('password');
});

it('rejects expired reset tokens', function () {
    Notification::fake();
    $user = User::factory()->create();
    $token = null;
    $this->post(route('password.email'), ['email' => $user->email]);
    Notification::assertSentTo($user, ResetPassword::class, function (ResetPassword $notification) use (&$token): bool {
        $token = $notification->token;
        return true;
    });
    DB::table('password_reset_tokens')->where('email', $user->email)->update(['created_at' => now()->subMinutes(31)]);

    $this->post(route('password.update'), [
        'token' => $token, 'email' => $user->email, 'password' => 'New-Secure-Password-2026!',
        'password_confirmation' => 'New-Secure-Password-2026!',
    ])->assertSessionHasErrors('email');
});

it('requires a second administrator for lockout recovery and revokes security state', function () {
    Permission::findOrCreate('users.manage');
    $role = Role::findOrCreate('CMS Administrator');
    $role->givePermissionTo('users.manage');
    $admin = User::factory()->create(['two_factor_confirmed_at' => now()]);
    $admin->assignRole($role);
    $locked = User::factory()->create([
        'two_factor_secret' => encrypt('LOST-SECRET'), 'two_factor_recovery_codes' => encrypt(json_encode(['lost-code'])),
        'two_factor_confirmed_at' => now(), 'must_change_password' => false,
    ]);
    DB::table('sessions')->insert(['id' => 'locked-session', 'user_id' => $locked->id, 'payload' => 'x', 'last_activity' => time()]);
    DB::table('password_reset_tokens')->insert(['email' => $locked->email, 'token' => Hash::make('token'), 'created_at' => now()]);

    $this->actingAs($admin)->withSession(['mfa_passed' => true])->post(route('cms.users.reset-security', $locked))->assertRedirect();
    $locked->refresh();
    expect($locked->two_factor_secret)->toBeNull()->and($locked->two_factor_recovery_codes)->toBeNull()
        ->and($locked->two_factor_confirmed_at)->toBeNull()->and($locked->must_change_password)->toBeTrue()
        ->and(DB::table('sessions')->where('user_id', $locked->id)->exists())->toBeFalse()
        ->and(DB::table('password_reset_tokens')->where('email', $locked->email)->exists())->toBeFalse()
        ->and(AuditEvent::where('action', 'user.security_recovery.initiated')->where('actor_id', $admin->id)->exists())->toBeTrue();
});

it('prevents administrators from resetting their own MFA', function () {
    Permission::findOrCreate('users.manage');
    $role = Role::findOrCreate('CMS Administrator');
    $role->givePermissionTo('users.manage');
    $admin = User::factory()->create(['two_factor_confirmed_at' => now()]);
    $admin->assignRole($role);
    $this->actingAs($admin)->withSession(['mfa_passed' => true])->post(route('cms.users.reset-security', $admin))->assertStatus(422);
});
