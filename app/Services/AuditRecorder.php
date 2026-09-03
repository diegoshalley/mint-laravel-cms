<?php

namespace App\Services;

use App\Models\AuditEvent;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class AuditRecorder
{
    public function record(string $action, ?User $actor, ?Model $subject = null, ?array $before = null, ?array $after = null): AuditEvent
    {
        return AuditEvent::create([
            'actor_id' => $actor?->getKey(), 'action' => $action,
            'subject_type' => $subject?->getMorphClass(), 'subject_id' => $subject?->getKey(),
            'ip_address' => request()?->ip(), 'user_agent' => request()?->userAgent(),
            'before' => $before, 'after' => $after, 'created_at' => now(),
        ]);
    }
}
