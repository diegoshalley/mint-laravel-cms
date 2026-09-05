@extends('layouts.cms')
@section('title', 'Upload media')
@section('content')
<div class="page-title"><div><p class="eyebrow">Secure intake</p><h1>Upload a file</h1><p>PDF, Word, Excel, JPEG, PNG or WebP. Maximum {{ number_format(config('media.max_upload_kb') / 1024) }} MB. SVG and executable files are blocked.</p></div></div>
@if($errors->any())<div class="error-box"><strong>Correct the highlighted information.</strong><ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
<form class="panel form narrow" method="post" action="{{ route('cms.media.store') }}" enctype="multipart/form-data">@csrf
<label>File<input type="file" name="file" required accept=".pdf,.docx,.xlsx,.jpg,.jpeg,.png,.webp"></label>
<label>Public title<input name="title" value="{{ old('title') }}" required maxlength="255"></label>
<label>Alternative text<textarea name="alt_text" rows="3" maxlength="500">{{ old('alt_text') }}</textarea><small>Required for meaningful images. Describe the information the image conveys.</small></label>
<label class="check"><input type="checkbox" name="is_decorative" value="1" @checked(old('is_decorative'))> This image is decorative and conveys no information</label>
<label>Description<textarea name="description" rows="4">{{ old('description') }}</textarea></label>
<label>Credit or source<input name="credit" value="{{ old('credit') }}" maxlength="255"></label>
<label>Language<select name="language"><option value="en">English</option></select></label>
<label>Accessibility notes<textarea name="accessibility_notes" rows="3">{{ old('accessibility_notes') }}</textarea><small>For documents, note whether the file is tagged, searchable and has a reading order.</small></label>
<button class="button">Quarantine upload</button>
</form>
@endsection
