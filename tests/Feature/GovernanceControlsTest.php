<?php

use App\Enums\PublishingStatus;
use App\Models\AuditEvent;
use App\Models\ContentItem;
use App\Models\EditorialComment;
use App\Models\User;
use App\Services\ContentWorkflow;
use Illuminate\Foundation\Testing\RefreshDatabase;
use LogicException;
use Spatie\Permission\Models\Permission;

uses(RefreshDatabase::class);

it('records workflow transitions in the audit log', function () {
    Permission::findOrCreate('content.submit');
    $author = User::factory()->create();
    $author->givePermissionTo('content.submit');
    $item = ContentItem::factory()->create(['author_id' => $author->id]);

    app(ContentWorkflow::class)->submit($item, $author);

    expect(AuditEvent::query()->where('action', 'content.workflow.transitioned')->count())->toBe(1);
});

it('does not allow audit events to be edited or deleted', function () {
    $event = AuditEvent::create(['action' => 'test.event', 'created_at' => now()]);
    expect(fn () => $event->update(['action' => 'changed']))->toThrow(LogicException::class)
        ->and(fn () => $event->delete())->toThrow(LogicException::class);
});

it('restores a historical draft as a new revision', function () {
    Permission::findOrCreate('content.rollback');
    $publisher = User::factory()->create();
    $publisher->givePermissionTo('content.rollback');
    $item = ContentItem::factory()->create(['status' => PublishingStatus::Draft]);
    $workflow = app(ContentWorkflow::class);
    $workflow->snapshot($item, $publisher, 'Original');
    $revision = $item->revisions()->firstOrFail();
    $item->update(['title' => 'Changed title', 'current_revision' => 2]);
    $workflow->snapshot($item, $publisher, 'Changed');

    $workflow->restoreDraftRevision($item, $revision, $publisher, 'Restore approved wording');

    expect($item->refresh()->title)->toBe($revision->snapshot['title'])
        ->and($item->current_revision)->toBe(3)
        ->and($item->revisions()->count())->toBe(3);
});

it('stores attributable reviewer recommendations', function () {
    $reviewer = User::factory()->create();
    $item = ContentItem::factory()->create(['status' => PublishingStatus::InReview]);
    $comment = EditorialComment::create([
        'content_item_id' => $item->id, 'author_id' => $reviewer->id,
        'kind' => 'recommendation', 'body' => 'Requirements and wording verified.',
    ]);
    expect($comment->author->is($reviewer))->toBeTrue()->and($item->comments()->count())->toBe(1);
});
