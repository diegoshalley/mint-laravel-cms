<?php

namespace App\Http\Controllers\Cms;

use App\Enums\ContentType;
use App\Http\Controllers\Controller;
use App\Models\ContentItem;
use App\Models\ContentUpdate;
use App\Services\PublishedContentWorkflow;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ContentReplacementController extends Controller
{
    public function store(ContentItem $content, Request $request, PublishedContentWorkflow $workflow): RedirectResponse
    {
        $replacement = $workflow->begin($content, $request->user());
        return redirect()->route('cms.replacements.edit', $replacement)->with('status', 'Replacement draft created. The live page has not changed.');
    }

    public function edit(ContentUpdate $replacement): View
    {
        return view('cms.content.replacement', ['replacement' => $replacement->load(['contentItem', 'author', 'revisions']), 'types' => ContentType::cases()]);
    }

    public function update(ContentUpdate $replacement, Request $request, PublishedContentWorkflow $workflow): RedirectResponse
    {
        $data = $request->validate([
            'type' => ['required', Rule::enum(ContentType::class)], 'title' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'alpha_dash', 'max:255', Rule::unique('content_items', 'slug')->ignore($replacement->content_item_id)],
            'summary' => ['nullable', 'string', 'max:600'], 'body' => ['required', 'string'], 'locale' => ['required', 'in:en'],
            'change_note' => ['required', 'string', 'max:1000'],
        ]);
        $workflow->save($replacement, $request->user(), [
            'type' => $data['type'], 'title' => $data['title'], 'slug' => $data['slug'], 'summary' => $data['summary'] ?? null,
            'content' => ['body' => $data['body']], 'locale' => $data['locale'], 'status' => 'published',
        ], $data['change_note']);
        return back()->with('status', 'Replacement saved. The live page remains unchanged.');
    }

    public function transition(ContentUpdate $replacement, Request $request, PublishedContentWorkflow $workflow): RedirectResponse
    {
        $data = $request->validate(['action' => ['required', 'in:submit,return,approve,publish'], 'reason' => ['nullable', 'string', 'max:1000'], 'publish_at' => ['nullable', 'date']]);
        match ($data['action']) {
            'submit' => $workflow->submit($replacement, $request->user()),
            'return' => $workflow->returnToDraft($replacement, $request->user(), $data['reason'] ?? ''),
            'approve' => $workflow->approve($replacement, $request->user()),
            'publish' => $workflow->publish($replacement, $request->user(), $request->date('publish_at')),
        };
        return back()->with('status', 'Replacement workflow updated.');
    }
}
