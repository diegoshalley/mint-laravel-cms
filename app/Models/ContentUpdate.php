<?php

namespace App\Models;

use App\Enums\PublishingStatus;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ContentUpdate extends Model
{
    use HasUuids;

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'status' => PublishingStatus::class, 'proposed' => 'array', 'submitted_at' => 'immutable_datetime',
            'approved_at' => 'immutable_datetime', 'effective_at' => 'immutable_datetime', 'applied_at' => 'immutable_datetime',
        ];
    }

    public function contentItem(): BelongsTo { return $this->belongsTo(ContentItem::class); }
    public function author(): BelongsTo { return $this->belongsTo(User::class, 'author_id'); }
    public function publisher(): BelongsTo { return $this->belongsTo(User::class, 'publisher_id'); }
    public function revisions(): HasMany { return $this->hasMany(ContentUpdateRevision::class); }
}
