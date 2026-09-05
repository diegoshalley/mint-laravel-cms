<?php

use App\Enums\PublishingStatus;
use App\Models\ContentItem;
use App\Models\MediaAsset;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

function authorizationUser(string $role): User
{
    test()->seed(RolePermissionSeeder::class);

    $user = User::factory()->create(['two_factor_confirmed_at' => now()]);
    $user->assignRole($role);

    return $user;
}

function authorizationSession(User $user): mixed
{
    return test()->actingAs($user)->withSession(['mfa_passed' => true]);
}

it('enforces the role matrix on CMS read surfaces', function (string $role, array $allowed, array $denied) {
    $user = authorizationUser($role);
    $item = ContentItem::factory()->create();
    $routes = [
        'dashboard' => route('cms.dashboard'),
        'content' => route('cms.content.index'),
        'content-create' => route('cms.content.create'),
        'content-edit' => route('cms.content.edit', $item),
        'audit' => route('cms.audit.index'),
        'users' => route('cms.users.index'),
        'media' => route('cms.media.index'),
        'media-create' => route('cms.media.create'),
        'navigation' => route('cms.navigation.index'),
        'redirects' => route('cms.redirects.index'),
    ];

    foreach ($allowed as $name) {
        authorizationSession($user)->get($routes[$name])->assertOk();
    }

    foreach ($denied as $name) {
        authorizationSession($user)->get($routes[$name])->assertForbidden();
    }
})->with([
    'Editor' => ['Editor', ['dashboard', 'content', 'content-create', 'content-edit'], ['audit', 'users', 'media', 'media-create', 'navigation', 'redirects']],
    'Reviewer' => ['Reviewer', ['dashboard', 'content', 'content-edit'], ['content-create', 'audit', 'users', 'media', 'media-create', 'navigation', 'redirects']],
    'Publisher' => ['Publisher', ['dashboard', 'content', 'content-edit', 'audit', 'media'], ['content-create', 'users', 'media-create', 'navigation', 'redirects']],
    'Media Manager' => ['Media Manager', ['dashboard', 'content', 'content-edit', 'media', 'media-create'], ['content-create', 'audit', 'users', 'navigation', 'redirects']],
    'Auditor' => ['Auditor', ['dashboard', 'content', 'content-edit', 'audit'], ['content-create', 'users', 'media', 'media-create', 'navigation', 'redirects']],
    'CMS Administrator' => ['CMS Administrator', ['dashboard', 'content', 'content-create', 'content-edit', 'audit', 'users', 'media', 'media-create', 'navigation', 'redirects'], []],
]);

it('rejects direct workflow mutations outside the actors permission', function () {
    $reviewer = authorizationUser('Reviewer');
    $draft = ContentItem::factory()->create(['status' => PublishingStatus::Draft]);

    authorizationSession($reviewer)->post(route('cms.content.transition', $draft), [
        'action' => 'submit',
    ])->assertForbidden();

    expect($draft->refresh()->status)->toBe(PublishingStatus::Draft);
});

it('rejects direct administrative mutations from an editor', function () {
    $editor = authorizationUser('Editor');
    $target = User::factory()->create();

    authorizationSession($editor)->post(route('cms.users.disable', $target))->assertForbidden();
    authorizationSession($editor)->post(route('cms.navigation.store'), [
        'location' => 'primary', 'label' => 'Services', 'destination' => '/services', 'position' => 10,
    ])->assertForbidden();
    authorizationSession($editor)->post(route('cms.redirects.store'), [
        'source_path' => '/old', 'destination_path' => '/new', 'status_code' => 301,
    ])->assertForbidden();

    expect($target->refresh()->is_active)->toBeTrue();
});

it('returns forbidden for unauthorized media approval and retirement requests', function () {
    $editor = authorizationUser('Editor');
    $asset = MediaAsset::create([
        'original_name' => 'notice.pdf', 'kind' => 'document', 'extension' => 'pdf',
        'mime_type' => 'application/pdf', 'size_bytes' => 100, 'sha256' => str_repeat('a', 64),
        'disk' => 'quarantine', 'path' => 'pending/notice.pdf', 'title' => 'Notice',
        'language' => 'en', 'scan_status' => 'clean', 'status' => 'draft', 'uploaded_by' => User::factory()->create()->id,
    ]);

    authorizationSession($editor)->post(route('cms.media.approve', $asset))->assertForbidden();

    $asset->update(['status' => 'approved']);
    authorizationSession($editor)->post(route('cms.media.retire', $asset))->assertForbidden();
});
