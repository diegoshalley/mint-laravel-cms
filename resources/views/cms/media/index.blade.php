@extends('layouts.cms')
@section('title', 'Media')
@section('content')
<div class="page-title"><div><p class="eyebrow">Controlled library</p><h1>Media and documents</h1><p>Files remain private until malware scanning and independent approval succeed.</p></div>@can('media.manage')<a class="button" href="{{ route('cms.media.create') }}">Upload file</a>@endcan</div>
<section class="panel"><div class="table-wrap"><table><thead><tr><th>File</th><th>Type</th><th>Scan</th><th>Release</th><th>Uploader</th><th>Actions</th></tr></thead><tbody>
@forelse($assets as $asset)<tr><td><strong>{{ $asset->title }}</strong><small class="file-meta">{{ $asset->original_name }} · {{ number_format($asset->size_bytes / 1024, 0) }} KB<br>SHA-256: <code>{{ Str::limit($asset->sha256, 18) }}</code></small></td><td>{{ $asset->kind }}</td><td><span class="badge badge-{{ $asset->scan_status }}">{{ $asset->scan_status }}</span>@if($asset->scan_message)<small class="file-meta">{{ $asset->scan_message }}</small>@endif</td><td><span class="badge">{{ $asset->status }}</span></td><td>{{ $asset->uploader->name }}</td><td class="actions">@can('media.approve')@if($asset->status === 'draft' && $asset->scan_status === 'clean')<form method="post" action="{{ route('cms.media.approve', $asset) }}">@csrf<button class="button">Approve release</button></form>@elseif($asset->status === 'approved')<form method="post" action="{{ route('cms.media.retire', $asset) }}">@csrf<button class="button secondary">Retire</button></form>@endif @endcan</td></tr>
@empty<tr><td colspan="6">No files have been uploaded.</td></tr>@endforelse
</tbody></table></div>{{ $assets->links() }}</section>
@endsection
