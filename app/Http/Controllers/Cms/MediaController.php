<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use App\Models\MediaAsset;
use App\Services\AuditRecorder;
use App\Services\MediaWorkflow;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\File;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class MediaController extends Controller
{
    public function index(): View
    {
        abort_unless(request()->user()->can('media.manage') || request()->user()->can('media.approve'), 403);
        return view('cms.media.index', ['assets' => MediaAsset::with(['uploader', 'approver'])->latest()->paginate(20)]);
    }

    public function create(): View
    {
        abort_unless(request()->user()->can('media.manage'), 403);
        return view('cms.media.form');
    }

    public function store(Request $request, AuditRecorder $audit): RedirectResponse
    {
        abort_unless($request->user()->can('media.manage'), 403);
        $allowed = config('media.allowed_extensions');
        $data = $request->validate([
            'file' => ['required', File::types($allowed)->max(config('media.max_upload_kb'))],
            'title' => ['required', 'string', 'max:255'],
            'alt_text' => ['nullable', 'string', 'max:500'],
            'is_decorative' => ['nullable', 'boolean'],
            'description' => ['nullable', 'string', 'max:2000'],
            'credit' => ['nullable', 'string', 'max:255'],
            'language' => ['required', 'in:en'],
            'accessibility_notes' => ['nullable', 'string', 'max:2000'],
        ]);

        $file = $data['file'];
        $extension = strtolower($file->getClientOriginalExtension());
        if (! in_array($extension, $allowed, true)) throw ValidationException::withMessages(['file' => 'The filename extension is not allowed.']);
        $kind = in_array($extension, config('media.image_extensions'), true) ? 'image' : 'document';
        $decorative = $request->boolean('is_decorative');
        if ($kind === 'image' && ! $decorative && blank($data['alt_text'] ?? null)) {
            throw ValidationException::withMessages(['alt_text' => 'Describe meaningful images, or mark the image as decorative.']);
        }

        $id = (string) Str::uuid();
        $path = 'pending/'.$id.'.'.$extension;
        Storage::disk('quarantine')->putFileAs('pending', $file, $id.'.'.$extension);
        $asset = MediaAsset::create([
            'id' => $id, 'original_name' => $file->getClientOriginalName(), 'kind' => $kind,
            'extension' => $extension, 'mime_type' => $file->getMimeType(), 'size_bytes' => $file->getSize(),
            'sha256' => hash_file('sha256', Storage::disk('quarantine')->path($path)), 'disk' => 'quarantine', 'path' => $path,
            'title' => $data['title'], 'alt_text' => $data['alt_text'] ?? null, 'is_decorative' => $decorative,
            'description' => $data['description'] ?? null, 'credit' => $data['credit'] ?? null,
            'language' => $data['language'], 'accessibility_notes' => $data['accessibility_notes'] ?? null,
            'scan_status' => 'pending', 'status' => 'draft', 'uploaded_by' => $request->user()->id,
        ]);
        $audit->record('media.uploaded', $request->user(), $asset, null, $asset->only(['original_name', 'mime_type', 'size_bytes', 'sha256', 'scan_status']));

        return redirect()->route('cms.media.index')->with('status', 'File quarantined. It cannot be published until scanning and independent approval are complete.');
    }

    public function approve(MediaAsset $media, Request $request, MediaWorkflow $workflow): RedirectResponse
    {
        $workflow->approve($media, $request->user());
        return back()->with('status', 'Clean file approved and released to public storage.');
    }

    public function retire(MediaAsset $media, Request $request, MediaWorkflow $workflow): RedirectResponse
    {
        $workflow->retire($media, $request->user());
        return back()->with('status', 'Media asset retired.');
    }
}
