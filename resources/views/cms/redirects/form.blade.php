@extends('layouts.cms') @section('title',$redirect->exists ? 'Edit redirect' : 'Add redirect') @section('content')
<div class="page-title"><div><p class="eyebrow">URL preservation</p><h1>{{ $redirect->exists ? 'Edit redirect' : 'Add redirect' }}</h1></div><a href="{{ route('cms.redirects.index') }}">← Redirects</a></div>
@if($errors->any())<div class="error-box"><ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
<form class="panel form narrow" method="post" action="{{ $redirect->exists ? route('cms.redirects.update',$redirect) : route('cms.redirects.store') }}">@csrf @if($redirect->exists)@method('put')@endif
<label>Old path<input name="source_path" value="{{ old('source_path',$redirect->source_path) }}" required placeholder="/old-page"><small>Path only. Do not enter the domain or a query string.</small></label>
<label>Replacement path<input name="destination_path" value="{{ old('destination_path',$redirect->destination_path) }}" required placeholder="/new-page"></label>
<label>Redirect type<select name="status_code"><option value="301" @selected((int)old('status_code',$redirect->status_code ?: 301)===301)>301 — Permanent</option><option value="302" @selected((int)old('status_code',$redirect->status_code ?: 301)===302)>302 — Temporary</option></select></label>
<label class="check"><input type="checkbox" name="is_active" value="1" @checked(old('is_active',$redirect->exists ? $redirect->is_active : true))> Active</label>
<button class="button">Save redirect</button></form>
@endsection
