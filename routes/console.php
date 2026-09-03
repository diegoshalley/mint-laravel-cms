<?php

use App\Enums\PublishingStatus;
use App\Models\ContentItem;
use App\Models\MediaAsset;
use App\Services\MediaWorkflow;
use App\Models\WorkflowTransition;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schedule;

Artisan::command('content:publish-scheduled', function (): int {
    ContentItem::query()
        ->where('status', PublishingStatus::Scheduled)
        ->where('published_at', '<=', now())
        ->chunkById(100, function ($items): void {
            foreach ($items as $item) {
                DB::transaction(function () use ($item): void {
                    $locked = ContentItem::query()->lockForUpdate()->findOrFail($item->id);
                    if ($locked->status !== PublishingStatus::Scheduled || $locked->published_at->isFuture()) return;
                    $locked->update(['status' => PublishingStatus::Published]);
                    WorkflowTransition::create([
                        'content_item_id' => $locked->id, 'from_status' => PublishingStatus::Scheduled->value,
                        'to_status' => PublishingStatus::Published->value, 'performed_by' => $locked->publisher_id,
                        'reason' => 'Scheduled publication time reached.', 'context' => ['source' => 'scheduler'], 'created_at' => now(),
                    ]);
                });
            }
        });
    return 0;
})->purpose('Publish approved content whose scheduled time has arrived');

Schedule::command('content:publish-scheduled')->everyMinute()->withoutOverlapping();

Artisan::command('media:scan-pending', function (MediaWorkflow $workflow): int {
    MediaAsset::query()->where('status', 'draft')->whereIn('scan_status', ['pending', 'failed'])
        ->oldest()->limit(100)->get()->each(fn (MediaAsset $asset) => $workflow->scan($asset));
    return 0;
})->purpose('Scan quarantined media and documents for malware');

Schedule::command('media:scan-pending')->everyMinute()->withoutOverlapping();
