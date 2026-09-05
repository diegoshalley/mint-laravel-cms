<?php

use App\Enums\PublishingStatus;
use App\Models\AuditEvent;
use App\Models\ContentItem;
use App\Models\ContentRevision;
use App\Models\User;
use App\Services\PublishedContentWorkflow;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;
use Spatie\Permission\Models\Permission;

uses(RefreshDatabase::class);

function replacementActor(array $permissions): User
{
    $user = User::factory()->create();
    foreach ($permissions as $permission) Permission::findOrCreate($permission);
    $user->givePermissionTo($permissions);
    return $user;
}

function liveContent(): ContentItem
{
    return ContentItem::factory()->create([
        'status' => PublishingStatus::Published, 'title' => 'Live safety notice',
        'content' => ['body' => 'Original public instructions.'], 'published_at' => now()->subDay(),
    ]);
}

it('creates an isolated replacement without changing live content', function () {
    $editor = replacementActor(['content.edit']);
    $item = liveContent();
    $update = app(PublishedContentWorkflow::class)->begin($item, $editor);

    app(PublishedContentWorkflow::class)->save($update, $editor, [
        ...$update->proposed, 'title' => 'Proposed safety notice', 'content' => ['body' => 'New instructions.'],
    ], 'Correct emergency instructions');

    expect($item->refresh()->title)->toBe('Live safety notice')
        ->and($item->content['body'])->toBe('Original public instructions.')
        ->and($update->refresh()->proposed['title'])->toBe('Proposed safety notice')
        ->and($update->revisions()->count())->toBe(2);
});

it('allows only one active replacement per published item', function () {
    $editor = replacementActor(['content.edit']);
    $item = liveContent();
    app(PublishedContentWorkflow::class)->begin($item, $editor);
    expect(fn () => app(PublishedContentWorkflow::class)->begin($item, $editor))->toThrow(ValidationException::class);
});

it('prevents the replacement author from approving or publishing it', function () {
    $author = replacementActor(['content.edit', 'content.submit', 'content.approve', 'content.publish']);
    $update = app(PublishedContentWorkflow::class)->begin(liveContent(), $author);
    app(PublishedContentWorkflow::class)->submit($update, $author);
    expect(fn () => app(PublishedContentWorkflow::class)->approve($update, $author))->toThrow(ValidationException::class);
});

it('atomically replaces live content after approval and preserves both versions', function () {
    $editor = replacementActor(['content.edit', 'content.submit']);
    $publisher = replacementActor(['content.approve', 'content.publish']);
    $item = liveContent();
    $update = app(PublishedContentWorkflow::class)->begin($item, $editor);
    app(PublishedContentWorkflow::class)->save($update, $editor, [
        ...$update->proposed, 'title' => 'Updated safety notice', 'content' => ['body' => 'Verified replacement instructions.'],
    ], 'Update verified emergency guidance');
    app(PublishedContentWorkflow::class)->submit($update, $editor);
    app(PublishedContentWorkflow::class)->approve($update, $publisher);
    app(PublishedContentWorkflow::class)->publish($update, $publisher);

    expect($item->refresh()->title)->toBe('Updated safety notice')
        ->and($item->content['body'])->toBe('Verified replacement instructions.')
        ->and($item->current_revision)->toBe(2)
        ->and($update->refresh()->status)->toBe(PublishingStatus::Published)
        ->and(ContentRevision::where('content_item_id', $item->id)->count())->toBe(2)
        ->and(ContentRevision::where('content_item_id', $item->id)->where('revision', 1)->first()->snapshot['title'])->toBe('Live safety notice')
        ->and(AuditEvent::where('action', 'content.replacement.published')->exists())->toBeTrue();
});

it('keeps scheduled replacements private until their due time', function () {
    $editor = replacementActor(['content.edit', 'content.submit']);
    $publisher = replacementActor(['content.approve', 'content.publish']);
    $item = liveContent();
    $update = app(PublishedContentWorkflow::class)->begin($item, $editor);
    app(PublishedContentWorkflow::class)->save($update, $editor, [...$update->proposed, 'title' => 'Future notice'], 'Prepare future wording');
    app(PublishedContentWorkflow::class)->submit($update, $editor);
    app(PublishedContentWorkflow::class)->approve($update, $publisher);
    app(PublishedContentWorkflow::class)->publish($update, $publisher, now()->addHour());

    expect($item->refresh()->title)->toBe('Live safety notice')
        ->and($update->refresh()->status)->toBe(PublishingStatus::Scheduled)
        ->and(fn () => app(PublishedContentWorkflow::class)->applyScheduled($update))->toThrow(ValidationException::class);
});
