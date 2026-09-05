@extends('layouts.cms')
@section('title','Content')
@section('content')
<div class="page-title"><div><p class="eyebrow">Content library</p><h1>All content</h1></div>@can('content.create')<a class="button" href="{{ route('cms.content.create') }}">Create content</a>@endcan</div>
@if($errors->any())<div class="error-box"><ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
<section class="panel"><div class="table-wrap"><table><thead><tr><th>Title</th><th>Type</th><th>Status</th><th>Author</th><th>Updated</th><th>Live replacement</th></tr></thead><tbody>
@forelse($items as $item)<tr><td><a href="{{ route('cms.content.edit',$item) }}">{{ $item->title }}</a></td><td>{{ $item->type->value }}</td><td><span class="badge">{{ str_replace('_',' ',$item->status->value) }}</span></td><td>{{ $item->author->name }}</td><td>{{ $item->updated_at->format('j M Y') }}</td><td>
@if($active = $item->updates->first())<a href="{{ route('cms.replacements.edit',$active) }}">Revision {{ $active->revision }} · {{ str_replace('_',' ',$active->status->value) }}</a>
@elseif($item->status->value === 'published')@can('content.edit')<form method="post" action="{{ route('cms.content.replacements.store',$item) }}">@csrf<button class="button secondary">Prepare replacement</button></form>@endcan
@else<span class="meta">Not published</span>@endif
</td></tr>@empty<tr><td colspan="6">No content has been created.</td></tr>@endforelse
</tbody></table></div>{{ $items->links() }}</section>
@endsection
