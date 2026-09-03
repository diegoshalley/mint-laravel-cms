<?php

use App\Models\AuditEvent;
use App\Models\MediaAsset;
use App\Models\User;
use App\Services\MalwareScanner;
use App\Services\MediaWorkflow;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use LogicException;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

function mediaUser(string $permission, string $role): User
{
    Permission::findOrCreate($permission);
    $roleModel = Role::findOrCreate($role);
    $roleModel->givePermissionTo($permission);
    $user = User::factory()->create(['two_factor_confirmed_at' => now()]);
    $user->assignRole($roleModel);
    return $user;
}

function quarantinedAsset(User $uploader, string $scan = 'pending'): MediaAsset
{
    Storage::disk('quarantine')->put('pending/test.pdf', '%PDF-1.4 test document');
    return MediaAsset::create([
        'original_name' => 'official-notice.pdf', 'kind' => 'document', 'extension' => 'pdf',
        'mime_type' => 'application/pdf', 'size_bytes' => 22, 'sha256' => hash('sha256', '%PDF-1.4 test document'),
        'disk' => 'quarantine', 'path' => 'pending/test.pdf', 'title' => 'Official notice', 'language' => 'en',
        'scan_status' => $scan, 'status' => 'draft', 'uploaded_by' => $uploader->id,
    ]);
}

beforeEach(function () {
    Storage::fake('quarantine');
    Storage::fake('public_media');
});

it('quarantines an allowed upload with a checksum and audit event', function () {
    $uploader = mediaUser('media.manage', 'Media Manager');
    $response = $this->actingAs($uploader)->withSession(['mfa_passed' => true])->post(route('cms.media.store'), [
        'file' => UploadedFile::fake()->image('briefing.jpg', 600, 400),
        'title' => 'Ministerial briefing', 'alt_text' => 'The Minister addressing regional security commanders.', 'language' => 'en',
    ]);

    $response->assertRedirect(route('cms.media.index'));
    $asset = MediaAsset::firstOrFail();
    Storage::disk('quarantine')->assertExists($asset->path);
    expect($asset->scan_status)->toBe('pending')->and($asset->disk)->toBe('quarantine')
        ->and($asset->sha256)->toHaveLength(64)
        ->and(AuditEvent::where('action', 'media.uploaded')->exists())->toBeTrue();
});

it('requires alternative text for meaningful images', function () {
    $uploader = mediaUser('media.manage', 'Media Manager');
    $this->actingAs($uploader)->withSession(['mfa_passed' => true])->from(route('cms.media.create'))->post(route('cms.media.store'), [
        'file' => UploadedFile::fake()->image('briefing.png'), 'title' => 'Briefing image', 'language' => 'en',
    ])->assertSessionHasErrors('alt_text');
    expect(MediaAsset::count())->toBe(0);
});

it('blocks executable uploads', function () {
    $uploader = mediaUser('media.manage', 'Media Manager');
    $this->actingAs($uploader)->withSession(['mfa_passed' => true])->post(route('cms.media.store'), [
        'file' => UploadedFile::fake()->create('payload.php', 2, 'application/x-php'), 'title' => 'Payload', 'language' => 'en',
    ])->assertSessionHasErrors('file');
    expect(MediaAsset::count())->toBe(0);
});

it('records malware scan results and never releases infected files', function () {
    $uploader = User::factory()->create();
    $asset = quarantinedAsset($uploader);
    app()->bind(MalwareScanner::class, fn () => new class implements MalwareScanner {
        public function scan(string $absolutePath): array { return ['status' => 'infected', 'message' => 'EICAR-Test-Signature FOUND']; }
    });

    app(MediaWorkflow::class)->scan($asset);
    expect($asset->refresh()->scan_status)->toBe('infected')
        ->and(AuditEvent::where('action', 'media.scan.infected')->exists())->toBeTrue();
    Storage::disk('public_media')->assertMissing('test.pdf');
});

it('requires a different authorized approver and a clean scan before release', function () {
    $uploader = mediaUser('media.approve', 'Publisher');
    $approver = mediaUser('media.approve', 'CMS Administrator');
    $pending = quarantinedAsset($uploader);

    expect(fn () => app(MediaWorkflow::class)->approve($pending, $approver))->toThrow(LogicException::class);
    $pending->update(['scan_status' => 'clean', 'scanned_at' => now()]);
    expect(fn () => app(MediaWorkflow::class)->approve($pending, $uploader))->toThrow(LogicException::class);

    $released = app(MediaWorkflow::class)->approve($pending, $approver);
    expect($released->status)->toBe('approved')->and($released->disk)->toBe('public_media')
        ->and($released->approved_by)->toBe($approver->id)
        ->and(AuditEvent::where('action', 'media.approved')->exists())->toBeTrue();
    Storage::disk('public_media')->assertExists($released->path);
    Storage::disk('quarantine')->assertMissing('pending/test.pdf');
});
