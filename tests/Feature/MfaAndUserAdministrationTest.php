<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use PragmaRX\Google2FA\Google2FA;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

it('requires and confirms authenticator enrollment', function () {
    $user = User::factory()->create(['password' => Hash::make('Correct-Horse-2026'), 'must_change_password' => false]);
    $this->actingAs($user)->post(route('mfa.enable'), ['password' => 'Correct-Horse-2026'])->assertRedirect();
    $user->refresh();
    expect($user->two_factor_secret)->not->toBeNull()->and($user->two_factor_confirmed_at)->toBeNull();

    $code = app(Google2FA::class)->getCurrentOtp(decrypt($user->two_factor_secret));
    $this->post(route('mfa.confirm'), ['code' => $code])->assertRedirect();
    expect($user->refresh()->two_factor_confirmed_at)->not->toBeNull();
});

it('blocks disabled accounts even when a session already exists', function () {
    $user = User::factory()->create(['is_active' => false]);
    $this->actingAs($user)->get(route('mfa.settings'))->assertRedirect(route('login'));
    $this->assertGuest();
});

it('forces staff to replace temporary passwords', function () {
    $user = User::factory()->create(['must_change_password' => true]);
    $this->actingAs($user)->get('/cms')->assertRedirect(route('password.change'));
});

it('lets authorized administrators provision a role-bound staff account', function () {
    Permission::findOrCreate('users.manage');
    Role::findOrCreate('Editor');
    $adminRole = Role::findOrCreate('CMS Administrator');
    $adminRole->givePermissionTo('users.manage');
    $admin = User::factory()->create(['two_factor_confirmed_at' => now()]);
    $admin->assignRole($adminRole);

    $this->actingAs($admin)->withSession(['mfa_passed' => true])->post(route('cms.users.store'), [
        'name' => 'Ama Mensah', 'email' => 'ama@example.gov.gh', 'role' => 'Editor',
        'password' => 'Temporary-Access-2026', 'password_confirmation' => 'Temporary-Access-2026',
    ])->assertRedirect(route('cms.users.index'));

    $staff = User::whereEmail('ama@example.gov.gh')->firstOrFail();
    expect($staff->hasRole('Editor'))->toBeTrue()->and($staff->must_change_password)->toBeTrue();
});

it('prevents administrators from disabling their own account', function () {
    Permission::findOrCreate('users.manage');
    $adminRole = Role::findOrCreate('CMS Administrator');
    $adminRole->givePermissionTo('users.manage');
    $admin = User::factory()->create(['two_factor_confirmed_at' => now()]);
    $admin->assignRole($adminRole);
    $this->actingAs($admin)->withSession(['mfa_passed' => true])->post(route('cms.users.disable', $admin))->assertStatus(422);
    expect($admin->refresh()->is_active)->toBeTrue();
});
