<?php

use App\Models\AuditEvent;
use App\Models\NavigationItem;
use App\Models\Redirect;
use App\Models\User;
use App\Services\RedirectGuard;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

function siteManager(): User
{
    foreach (['navigation.manage', 'redirects.manage'] as $permission) Permission::findOrCreate($permission);
    $role = Role::findOrCreate('CMS Administrator');
    $role->syncPermissions(['navigation.manage', 'redirects.manage']);
    $user = User::factory()->create(['two_factor_confirmed_at' => now()]);
    $user->assignRole($role);
    return $user;
}

it('renders visible navigation in its configured order and hides disabled links', function () {
    NavigationItem::create(['label' => 'Agencies', 'destination' => '/agencies', 'location' => 'primary', 'position' => 20, 'is_visible' => true]);
    NavigationItem::create(['label' => 'Services', 'destination' => '/services', 'location' => 'primary', 'position' => 10, 'is_visible' => true]);
    NavigationItem::create(['label' => 'Hidden operation', 'destination' => '/hidden', 'location' => 'primary', 'position' => 5, 'is_visible' => false]);

    $response = $this->get('/')->assertOk()->assertSee('Services')->assertSee('Agencies')->assertDontSee('Hidden operation');
    expect(strpos($response->getContent(), 'Services'))->toBeLessThan(strpos($response->getContent(), 'Agencies'));
});

it('rejects unsafe navigation destinations', function () {
    $manager = siteManager();
    $this->actingAs($manager)->withSession(['mfa_passed' => true])->post(route('cms.navigation.store'), [
        'location' => 'primary', 'label' => 'Unsafe', 'destination' => 'javascript:alert(1)', 'position' => 10, 'is_visible' => 1,
    ])->assertSessionHasErrors('destination');
    expect(NavigationItem::count())->toBe(0);
});

it('serves an active legacy redirect and records its usage', function () {
    $redirect = Redirect::create(['source_path' => '/old-news', 'destination_path' => '/news-notices', 'status_code' => 301, 'is_active' => true]);

    $this->get('/old-news')->assertRedirect('/news-notices')->assertStatus(301);
    expect($redirect->refresh()->hit_count)->toBe(1)->and($redirect->last_hit_at)->not->toBeNull();
});

it('does not serve disabled redirects', function () {
    Redirect::create(['source_path' => '/retired', 'destination_path' => '/documents', 'status_code' => 301, 'is_active' => false]);
    $this->get('/retired')->assertNotFound();
});

it('rejects redirect loops including indirect cycles', function () {
    Redirect::create(['source_path' => '/first', 'destination_path' => '/second', 'status_code' => 301, 'is_active' => true]);
    expect(fn () => app(RedirectGuard::class)->validateChain('/second', '/first'))->toThrow(ValidationException::class)
        ->and(fn () => app(RedirectGuard::class)->validateChain('/same', '/same'))->toThrow(ValidationException::class);
});

it('records attributable navigation and redirect changes', function () {
    $manager = siteManager();
    $session = $this->actingAs($manager)->withSession(['mfa_passed' => true]);
    $session->post(route('cms.navigation.store'), [
        'location' => 'primary', 'label' => 'Services', 'destination' => '/services', 'position' => 10, 'is_visible' => 1,
    ])->assertRedirect(route('cms.navigation.index'));
    $session->post(route('cms.redirects.store'), [
        'source_path' => '/old-services', 'destination_path' => '/services', 'status_code' => 301, 'is_active' => 1,
    ])->assertRedirect(route('cms.redirects.index'));

    expect(AuditEvent::where('action', 'navigation.created')->where('actor_id', $manager->id)->exists())->toBeTrue()
        ->and(AuditEvent::where('action', 'redirect.created')->where('actor_id', $manager->id)->exists())->toBeTrue();
});
