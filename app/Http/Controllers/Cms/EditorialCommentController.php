<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use App\Models\ContentItem;
use App\Models\EditorialComment;
use App\Services\AuditRecorder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class EditorialCommentController extends Controller
{
    public function store(Request $request, ContentItem $content, AuditRecorder $audit): RedirectResponse
    {
        abort_unless($request->user()->can('content.review'), 403);
        $data = $request->validate(['kind' => ['required', 'in:comment,change_request,recommendation'], 'body' => ['required', 'string', 'max:3000']]);
        $comment = EditorialComment::create([...$data, 'content_item_id' => $content->id, 'author_id' => $request->user()->id]);
        $audit->record('content.comment.created', $request->user(), $content, null, ['comment_id' => $comment->id, 'kind' => $comment->kind]);
        return back()->with('status', 'Editorial note added.');
    }
}
