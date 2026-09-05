<?php

namespace App\Services;

use App\Models\MediaAsset;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use LogicException;

class MediaWorkflow
{
    public function __construct(private readonly AuditRecorder $audit, private readonly MalwareScanner $scanner) {}

    public function scan(MediaAsset $asset): MediaAsset
    {
        if ($asset->status !== 'draft' || $asset->disk !== 'quarantine') {
            throw new LogicException('Only quarantined draft files can be scanned.');
        }

        $result = $this->scanner->scan(Storage::disk('quarantine')->path($asset->path));
        $asset->update(['scan_status' => $result['status'], 'scan_message' => $result['message'], 'scanned_at' => now()]);
        $this->audit->record('media.scan.'.$result['status'], null, $asset, null, ['message' => $result['message']]);

        return $asset->refresh();
    }

    public function approve(MediaAsset $asset, User $actor): MediaAsset
    {
        if (! $actor->can('media.approve')) throw new AuthorizationException;
        if ($asset->uploaded_by === $actor->id) throw new LogicException('Uploaders cannot approve their own files.');
        if ($asset->status !== 'draft' || $asset->scan_status !== 'clean') throw new LogicException('Only clean draft files can be approved.');

        return DB::transaction(function () use ($asset, $actor): MediaAsset {
            $locked = MediaAsset::query()->lockForUpdate()->findOrFail($asset->id);
            if ($locked->status !== 'draft' || $locked->scan_status !== 'clean') throw new LogicException('The file is not ready for approval.');
            $destination = now()->format('Y/m').'/'.$locked->id.'.'.$locked->extension;
            Storage::disk('public_media')->writeStream($destination, Storage::disk('quarantine')->readStream($locked->path));
            Storage::disk('quarantine')->delete($locked->path);
            $before = $locked->only(['disk', 'path', 'status']);
            $locked->update(['disk' => 'public_media', 'path' => $destination, 'status' => 'approved', 'approved_by' => $actor->id, 'approved_at' => now()]);
            $this->audit->record('media.approved', $actor, $locked, $before, $locked->only(['disk', 'path', 'status']));
            return $locked->refresh();
        });
    }

    public function retire(MediaAsset $asset, User $actor): MediaAsset
    {
        if (! $actor->can('media.approve')) throw new AuthorizationException;
        if ($asset->status !== 'approved') throw new LogicException('Only approved files can be retired.');
        $asset->update(['status' => 'retired', 'retired_at' => now()]);
        $this->audit->record('media.retired', $actor, $asset, ['status' => 'approved'], ['status' => 'retired']);
        return $asset->refresh();
    }
}
