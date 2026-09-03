<?php

namespace App\Services;

use App\Enums\PublishingStatus;
use App\Models\ContentItem;
use App\Models\ContentRevision;
use App\Models\User;
use App\Models\WorkflowTransition;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ContentWorkflow
{
    public function submit(ContentItem $item, User $actor): ContentItem
    {
        $this->authorize($actor, 'content.submit');
        $this->requireStatus($item, PublishingStatus::Draft);
        return $this->transition($item, $actor, PublishingStatus::InReview);
    }

    public function returnToDraft(ContentItem $item, User $actor, string $reason): ContentItem
    {
        $this->authorize($actor, 'content.review');
        $this->requireStatus($item, PublishingStatus::InReview);
        if (trim($reason) === '') throw ValidationException::withMessages(['reason' => 'A return reason is required.']);
        return $this->transition($item, $actor, PublishingStatus::Draft, $reason);
    }

    public function approve(ContentItem $item, User $actor): ContentItem
    {
        $this->authorize($actor, 'content.approve');
        $this->requireStatus($item, PublishingStatus::InReview);
        if ($item->author_id === $actor->getKey()) {
            throw ValidationException::withMessages(['workflow' => 'Authors cannot approve their own content.']);
        }
        return $this->transition($item, $actor, PublishingStatus::Approved);
    }

    public function publish(ContentItem $item, User $actor, ?\DateTimeInterface $when = null): ContentItem
    {
        $this->authorize($actor, 'content.publish');
        $this->requireStatus($item, PublishingStatus::Approved);
        if ($item->author_id === $actor->getKey()) {
            throw ValidationException::withMessages(['workflow' => 'Authors cannot publish their own content.']);
        }
        $when ??= now();
        $future = $when->getTimestamp() > now()->getTimestamp();
        return $this->transition($item, $actor, $future ? PublishingStatus::Scheduled : PublishingStatus::Published, null, $when);
    }

    private function transition(ContentItem $item, User $actor, PublishingStatus $to, ?string $reason = null, ?\DateTimeInterface $when = null): ContentItem
    {
        return DB::transaction(function () use ($item, $actor, $to, $reason, $when): ContentItem {
            $from = $item->status;
            $updates = ['status' => $to];
            if ($to === PublishingStatus::InReview) $updates['submitted_at'] = now();
            if ($to === PublishingStatus::Approved) {
                $updates['reviewer_id'] = $actor->getKey();
                $updates['approved_at'] = now();
            }
            if (in_array($to, [PublishingStatus::Scheduled, PublishingStatus::Published], true)) {
                $updates['publisher_id'] = $actor->getKey();
                $updates['published_at'] = $when ?? now();
            }
            $item->update($updates);
            WorkflowTransition::create([
                'content_item_id' => $item->getKey(), 'from_status' => $from->value,
                'to_status' => $to->value, 'performed_by' => $actor->getKey(),
                'reason' => $reason, 'context' => ['ip' => request()?->ip()], 'created_at' => now(),
            ]);
            return $item->refresh();
        });
    }

    public function snapshot(ContentItem $item, User $actor, ?string $note = null): void
    {
        ContentRevision::create([
            'content_item_id' => $item->getKey(), 'revision' => $item->current_revision,
            'snapshot' => $item->only(['type', 'status', 'title', 'slug', 'summary', 'content', 'locale']),
            'created_by' => $actor->getKey(), 'change_note' => $note, 'created_at' => now(),
        ]);
    }

    private function authorize(User $actor, string $ability): void
    {
        if (! $actor->can($ability)) throw new AuthorizationException;
    }

    private function requireStatus(ContentItem $item, PublishingStatus $expected): void
    {
        if ($item->status !== $expected) {
            throw ValidationException::withMessages(['workflow' => "This action requires {$expected->value} status."]);
        }
    }
}
