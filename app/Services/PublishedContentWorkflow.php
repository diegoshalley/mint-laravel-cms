<?php

namespace App\Services;

use App\Enums\PublishingStatus;
use App\Models\ContentItem;
use App\Models\ContentRevision;
use App\Models\ContentUpdate;
use App\Models\ContentUpdateRevision;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PublishedContentWorkflow
{
    public function __construct(private readonly AuditRecorder $audit) {}

    public function begin(ContentItem $item, User $actor): ContentUpdate
    {
        $this->authorize($actor, 'content.edit');
        if ($item->status !== PublishingStatus::Published) $this->fail('Only published content can use the replacement workflow.');

        return DB::transaction(function () use ($item, $actor): ContentUpdate {
            $locked = ContentItem::query()->lockForUpdate()->findOrFail($item->id);
            $active = $locked->updates()->whereIn('status', ['draft', 'in_review', 'approved', 'scheduled'])->exists();
            if ($active) $this->fail('An active replacement already exists for this content.');
            $update = ContentUpdate::create([
                'content_item_id' => $locked->id, 'status' => PublishingStatus::Draft,
                'proposed' => $this->liveSnapshot($locked), 'revision' => 1, 'author_id' => $actor->id,
            ]);
            $this->snapshot($update, $actor, 'Replacement draft created from the live version.');
            $this->audit->record('content.replacement.created', $actor, $update, null, ['content_item_id' => $locked->id]);
            return $update;
        });
    }

    public function save(ContentUpdate $update, User $actor, array $proposed, string $note): ContentUpdate
    {
        $this->authorize($actor, 'content.edit');
        $this->requireStatus($update, PublishingStatus::Draft);
        if (trim($note) === '') throw ValidationException::withMessages(['change_note' => 'A change note is required.']);
        return DB::transaction(function () use ($update, $actor, $proposed, $note): ContentUpdate {
            $update->update(['proposed' => $proposed, 'revision' => $update->revision + 1]);
            $this->snapshot($update->refresh(), $actor, $note);
            $this->audit->record('content.replacement.revised', $actor, $update, null, ['revision' => $update->revision, 'change_note' => $note]);
            return $update->refresh();
        });
    }

    public function submit(ContentUpdate $update, User $actor): ContentUpdate
    {
        $this->authorize($actor, 'content.submit');
        $this->requireStatus($update, PublishingStatus::Draft);
        return $this->transition($update, $actor, PublishingStatus::InReview, ['submitted_at' => now()]);
    }

    public function returnToDraft(ContentUpdate $update, User $actor, string $reason): ContentUpdate
    {
        $this->authorize($actor, 'content.review');
        $this->requireStatus($update, PublishingStatus::InReview);
        if (trim($reason) === '') throw ValidationException::withMessages(['reason' => 'A return reason is required.']);
        return $this->transition($update, $actor, PublishingStatus::Draft, [], $reason);
    }

    public function approve(ContentUpdate $update, User $actor): ContentUpdate
    {
        $this->authorize($actor, 'content.approve');
        $this->requireStatus($update, PublishingStatus::InReview);
        $this->requireIndependentActor($update, $actor, 'approve');
        return $this->transition($update, $actor, PublishingStatus::Approved, ['reviewer_id' => $actor->id, 'approved_at' => now()]);
    }

    public function publish(ContentUpdate $update, User $actor, ?\DateTimeInterface $when = null): ContentUpdate
    {
        $this->authorize($actor, 'content.publish');
        $this->requireStatus($update, PublishingStatus::Approved);
        $this->requireIndependentActor($update, $actor, 'publish');
        $when ??= now();
        if ($when->getTimestamp() > now()->getTimestamp()) {
            return $this->transition($update, $actor, PublishingStatus::Scheduled, ['publisher_id' => $actor->id, 'effective_at' => $when]);
        }
        $update->update(['publisher_id' => $actor->id, 'effective_at' => $when]);
        return $this->apply($update, $actor);
    }

    public function applyScheduled(ContentUpdate $update): ContentUpdate
    {
        $this->requireStatus($update, PublishingStatus::Scheduled);
        if (! $update->effective_at || $update->effective_at->isFuture()) $this->fail('This replacement is not due.');
        return $this->apply($update, $update->publisher()->firstOrFail());
    }

    private function apply(ContentUpdate $update, User $actor): ContentUpdate
    {
        return DB::transaction(function () use ($update, $actor): ContentUpdate {
            $lockedUpdate = ContentUpdate::query()->lockForUpdate()->findOrFail($update->id);
            if (! in_array($lockedUpdate->status, [PublishingStatus::Approved, PublishingStatus::Scheduled], true)) $this->fail('This replacement is not ready.');
            $item = ContentItem::query()->lockForUpdate()->findOrFail($lockedUpdate->content_item_id);
            if ($item->status !== PublishingStatus::Published) $this->fail('The live content is no longer published.');

            ContentRevision::firstOrCreate(
                ['content_item_id' => $item->id, 'revision' => $item->current_revision],
                ['snapshot' => $this->liveSnapshot($item), 'created_by' => $actor->id, 'change_note' => 'Live version before replacement', 'created_at' => now()],
            );
            $before = $this->liveSnapshot($item);
            $nextRevision = $item->current_revision + 1;
            $item->update([...$lockedUpdate->proposed, 'current_revision' => $nextRevision, 'publisher_id' => $actor->id, 'published_at' => now()]);
            ContentRevision::create([
                'content_item_id' => $item->id, 'revision' => $nextRevision, 'snapshot' => $this->liveSnapshot($item->refresh()),
                'created_by' => $actor->id, 'change_note' => "Applied replacement {$lockedUpdate->id}", 'created_at' => now(),
            ]);
            $lockedUpdate->update(['status' => PublishingStatus::Published, 'applied_at' => now()]);
            $this->audit->record('content.replacement.published', $actor, $item, $before, $this->liveSnapshot($item->refresh()));
            return $lockedUpdate->refresh();
        });
    }

    private function transition(ContentUpdate $update, User $actor, PublishingStatus $status, array $extra = [], ?string $reason = null): ContentUpdate
    {
        $from = $update->status;
        $update->update(['status' => $status, ...$extra]);
        $this->audit->record('content.replacement.transitioned', $actor, $update, ['status' => $from->value], ['status' => $status->value, 'reason' => $reason]);
        return $update->refresh();
    }

    private function snapshot(ContentUpdate $update, User $actor, string $note): void
    {
        ContentUpdateRevision::create(['content_update_id' => $update->id, 'revision' => $update->revision, 'proposed' => $update->proposed, 'created_by' => $actor->id, 'change_note' => $note, 'created_at' => now()]);
    }

    private function liveSnapshot(ContentItem $item): array
    {
        return ['type' => $item->type->value, 'title' => $item->title, 'slug' => $item->slug, 'summary' => $item->summary, 'content' => $item->content, 'locale' => $item->locale, 'status' => PublishingStatus::Published->value];
    }

    private function requireIndependentActor(ContentUpdate $update, User $actor, string $action): void
    {
        if ($update->author_id === $actor->id) $this->fail("Authors cannot {$action} their own replacement.");
    }

    private function authorize(User $actor, string $ability): void
    {
        if (! $actor->can($ability)) throw new AuthorizationException;
    }

    private function requireStatus(ContentUpdate $update, PublishingStatus $status): void
    {
        if ($update->status !== $status) $this->fail("This action requires {$status->value} status.");
    }

    private function fail(string $message): never
    {
        throw ValidationException::withMessages(['workflow' => $message]);
    }
}
