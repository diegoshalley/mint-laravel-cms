<?php

namespace App\Models;

use App\Enums\ContentType;
use App\Enums\PublishingStatus;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ContentItem extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'type' => ContentType::class,
            'status' => PublishingStatus::class,
            'content' => 'array',
            'submitted_at' => 'immutable_datetime',
            'approved_at' => 'immutable_datetime',
            'published_at' => 'immutable_datetime',
            'expires_at' => 'immutable_datetime',
        ];
    }

    public function author(): BelongsTo { return $this->belongsTo(User::class, 'author_id'); }
    public function reviewer(): BelongsTo { return $this->belongsTo(User::class, 'reviewer_id'); }
    public function publisher(): BelongsTo { return $this->belongsTo(User::class, 'publisher_id'); }
    public function revisions(): HasMany { return $this->hasMany(ContentRevision::class); }
    public function transitions(): HasMany { return $this->hasMany(WorkflowTransition::class); }
    public function comments(): HasMany { return $this->hasMany(EditorialComment::class); }
    public function updates(): HasMany { return $this->hasMany(ContentUpdate::class); }

    public function scopeVisible($query)
    {
        return $query->where('status', PublishingStatus::Published)
            ->where('published_at', '<=', now())
            ->where(fn ($q) => $q->whereNull('expires_at')->orWhere('expires_at', '>', now()));
    }
}
