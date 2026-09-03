<?php

namespace App\Http\Controllers\Cms;

use App\Enums\ContentType;
use App\Enums\PublishingStatus;
use App\Http\Controllers\Controller;
use App\Models\ContentItem;
use App\Models\ContentRevision;
use App\Services\AuditRecorder;
use App\Services\ContentWorkflow;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ContentController extends Controller
{
    public function index(): View
    {
        return view('cms.content.index', ['items' => ContentItem::with('author')->latest()->paginate(20)]);
    }

    public function create(): View { return view('cms.content.form', ['item' => new ContentItem, 'types' => ContentType::cases()]); }

    public function store(Request $request, ContentWorkflow $workflow, AuditRecorder $audit): RedirectResponse
    {
        abort_unless($request->user()->can('content.create'), 403);
        $data = $this->validated($request);
        $item = DB::transaction(function () use ($data, $request, $workflow, $audit) {
            $item = ContentItem::create([...$data, 'slug' => $data['slug'] ?: Str::slug($data['title']), 'content' => ['body' => $data['body']], 'author_id' => $request->user()->id, 'status' => PublishingStatus::Draft]);
            $workflow->snapshot($item, $request->user(), 'Initial draft');
            $audit->record('content.created', $request->user(), $item, null, $item->only(['type', 'title', 'slug', 'locale']));
            return $item;
        });
        return redirect()->route('cms.content.edit', $item)->with('status', 'Draft created.');
    }

    public function edit(ContentItem $content): View { return view('cms.content.form', ['item' => $content, 'types' => ContentType::cases()]); }

    public function update(Request $request, ContentItem $content, ContentWorkflow $workflow, AuditRecorder $audit): RedirectResponse
    {
        abort_unless($request->user()->can('content.edit') && $content->status === PublishingStatus::Draft, 403);
        $data = $this->validated($request, $content);
        DB::transaction(function () use ($content, $data, $request, $workflow, $audit) {
            $before = $content->only(['type', 'title', 'slug', 'summary', 'content', 'locale', 'current_revision']);
            $content->increment('current_revision');
            $content->update([...$data, 'slug' => $data['slug'] ?: Str::slug($data['title']), 'content' => ['body' => $data['body']]]);
            $workflow->snapshot($content->refresh(), $request->user(), $request->string('change_note')->toString());
            $audit->record('content.updated', $request->user(), $content, $before, $content->only(['type', 'title', 'slug', 'summary', 'content', 'locale', 'current_revision']));
        });
        return back()->with('status', 'Draft saved as a new revision.');
    }

    public function restore(Request $request, ContentItem $content, ContentRevision $revision, ContentWorkflow $workflow): RedirectResponse
    {
        $data = $request->validate(['reason' => ['required', 'string', 'max:1000']]);
        $workflow->restoreDraftRevision($content, $revision, $request->user(), $data['reason']);
        return back()->with('status', "Revision {$revision->revision} restored as a new draft revision.");
    }

    public function transition(Request $request, ContentItem $content, ContentWorkflow $workflow): RedirectResponse
    {
        $action = $request->validate(['action' => ['required', 'in:submit,return,approve,publish'], 'reason' => ['nullable', 'string', 'max:1000'], 'publish_at' => ['nullable', 'date']])['action'];
        match ($action) {
            'submit' => $workflow->submit($content, $request->user()),
            'return' => $workflow->returnToDraft($content, $request->user(), $request->string('reason')->toString()),
            'approve' => $workflow->approve($content, $request->user()),
            'publish' => $workflow->publish($content, $request->user(), $request->date('publish_at')),
        };
        return back()->with('status', 'Workflow updated.');
    }

    private function validated(Request $request, ?ContentItem $item = null): array
    {
        return $request->validate([
            'type' => ['required', Rule::enum(ContentType::class)], 'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'alpha_dash', 'max:255', 'unique:content_items,slug'.($item ? ','.$item->id : '')],
            'summary' => ['nullable', 'string', 'max:600'], 'body' => ['required', 'string'], 'locale' => ['required', 'in:en'],
        ]);
    }
}
