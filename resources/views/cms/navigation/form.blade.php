@extends('layouts.cms') @section('title',$item->exists ? 'Edit navigation item' : 'Add navigation item') @section('content')
<div class="page-title"><div><p class="eyebrow">Site structure</p><h1>{{ $item->exists ? 'Edit navigation item' : 'Add navigation item' }}</h1></div><a href="{{ route('cms.navigation.index') }}">← Navigation</a></div>
@if($errors->any())<div class="error-box"><ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
<form class="panel form narrow" method="post" action="{{ $item->exists ? route('cms.navigation.update',$item) : route('cms.navigation.store') }}">@csrf @if($item->exists)@method('put')@endif
<label>Menu<select name="location"><option value="primary" @selected(old('location',$item->location)==='primary')>Primary header</option><option value="footer" @selected(old('location',$item->location)==='footer')>Footer</option></select></label>
<label>Label<input name="label" value="{{ old('label',$item->label) }}" required maxlength="100"></label>
<label>Destination<input name="destination" value="{{ old('destination',$item->destination) }}" required placeholder="/services"><small>Use an internal path beginning with / or a complete https:// address.</small></label>
<label>Parent item<select name="parent_id"><option value="">None — top level</option>@foreach($parents as $parent)<option value="{{ $parent->id }}" @selected(old('parent_id',$item->parent_id)===$parent->id)>{{ $parent->label }} ({{ $parent->location }})</option>@endforeach</select></label>
<label>Position<input type="number" name="position" value="{{ old('position',$item->position ?? 10) }}" min="0" max="999" required></label>
<label class="check"><input type="checkbox" name="is_visible" value="1" @checked(old('is_visible',$item->exists ? $item->is_visible : true))> Visible on the public website</label>
<label class="check"><input type="checkbox" name="open_in_new_tab" value="1" @checked(old('open_in_new_tab',$item->open_in_new_tab))> Open in a new tab</label>
<button class="button">Save navigation item</button></form>
@endsection
