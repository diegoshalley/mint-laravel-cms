@extends('layouts.cms')
@section('title','Replace published content')
@section('content')
<div class="page-title"><div><p class="eyebrow">Safe live replacement · Revision {{ $replacement->revision }}</p><h1>{{ $replacement->proposed['title'] }}</h1><span class="badge">{{ str_replace('_',' ',$replacement->status->value) }}</span></div><a href="{{ route('cms.content.index') }}">← Content library</a></div>
<div class="warning"><strong>The public website still shows the approved live version.</strong> This replacement becomes public only after independent approval and publication.</div>
@if($errors->any())<div class="error-box"><strong>Correct the highlighted issues.</strong><ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
<section class="comparison-grid" aria-labelledby="comparison-heading"><h2 id="comparison-heading" class="sr-only">Live and proposed content comparison</h2><article class="panel"><p class="eyebrow">Currently live</p><h3>{{ $replacement->contentItem->title }}</h3><p>{{ $replacement->contentItem->summary }}</p><div class="comparison-body">{{ $replacement->contentItem->content['body'] ?? '' }}</div></article><article class="panel proposed"><p class="eyebrow">Proposed replacement</p><h3>{{ $replacement->proposed['title'] }}</h3><p>{{ $replacement->proposed['summary'] }}</p><div class="comparison-body">{{ $replacement->proposed['content']['body'] ?? '' }}</div></article></section>
<div class="editor-grid"><form class="panel form" method="post" action="{{ route('cms.replacements.update',$replacement) }}">@csrf @method('put')
<div class="form-row"><label>Content type<select name="type" required>@foreach($types as $type)<option value="{{ $type->value }}" @selected(old('type',$replacement->proposed['type'])===$type->value)>{{ ucfirst($type->value) }}</option>@endforeach</select></label><label>Language<select name="locale"><option value="en">English</option></select></label></div>
<label>Title<input name="title" value="{{ old('title',$replacement->proposed['title']) }}" required></label>
<label>URL slug<input name="slug" value="{{ old('slug',$replacement->proposed['slug']) }}" required></label>
<label>Summary<textarea name="summary" rows="3" maxlength="600">{{ old('summary',$replacement->proposed['summary']) }}</textarea></label>
<label>Body<textarea name="body" rows="14" required>{{ old('body',$replacement->proposed['content']['body'] ?? '') }}</textarea></label>
<label>Required change note<input name="change_note" required placeholder="Explain exactly what changed"></label>
@if($replacement->status->value === 'draft')<button class="button">Save replacement revision</button>@endif
</form>
<aside class="panel workflow"><h2>Replacement workflow</h2><p>Author: {{ $replacement->author->name }}</p><form method="post" action="{{ route('cms.replacements.transition',$replacement) }}">@csrf
@if($replacement->status->value === 'draft')<button class="button" name="action" value="submit">Submit for review</button>
@elseif($replacement->status->value === 'in_review')<label>Reason for returning<textarea name="reason" rows="3"></textarea></label><button class="button secondary" name="action" value="return">Return to author</button>@can('content.approve')<button class="button" name="action" value="approve">Approve replacement</button>@endcan
@elseif($replacement->status->value === 'approved')<label>Go-live date (optional)<input type="datetime-local" name="publish_at"></label><button class="button" name="action" value="publish">Publish or schedule replacement</button>
@elseif($replacement->status->value === 'scheduled')<p>Scheduled for {{ $replacement->effective_at->format('j M Y, H:i') }}.</p>
@else<p>Applied {{ $replacement->applied_at?->format('j M Y, H:i') }}.</p>@endif</form>
<h3>Draft history</h3><ol>@foreach($replacement->revisions->sortByDesc('revision') as $revision)<li>Revision {{ $revision->revision }}<small>{{ $revision->change_note }} · {{ $revision->created_at->format('j M Y, H:i') }}</small></li>@endforeach</ol>
</aside></div>
@endsection
