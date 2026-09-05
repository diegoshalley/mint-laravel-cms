<?php

use App\Enums\ContentType;
use App\Enums\PublishingStatus;
use App\Models\ContentItem;
use App\Models\User;
use App\Services\ContentWorkflow;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;
use Spatie\Permission\Models\Permission;

uses(RefreshDatabase::class);

it('prevents an author from approving their own work', function () {
    Permission::findOrCreate('content.approve');
    $author = User::factory()->create();
    $author->givePermissionTo('content.approve');
    $item = ContentItem::factory()->create(['author_id' => $author->id, 'status' => PublishingStatus::InReview]);
    expect(fn () => app(ContentWorkflow::class)->approve($item, $author))->toThrow(ValidationException::class);
});

it('records a reviewer approval transition', function () {
    Permission::findOrCreate('content.approve');
    $author = User::factory()->create();
    $reviewer = User::factory()->create();
    $reviewer->givePermissionTo('content.approve');
    $item = ContentItem::factory()->create(['author_id' => $author->id, 'type' => ContentType::News, 'status' => PublishingStatus::InReview]);
    app(ContentWorkflow::class)->approve($item, $reviewer);
    expect($item->refresh()->status)->toBe(PublishingStatus::Approved)
        ->and($item->transitions()->count())->toBe(1);
});
