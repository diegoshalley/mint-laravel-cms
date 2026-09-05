<?php

namespace App\Http\Controllers\Cms;

use App\Enums\PublishingStatus;
use App\Http\Controllers\Controller;
use App\Models\ContentItem;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(Request $request): View
    {
        abort_unless($request->user()->can('content.view'), 403);

        return view('cms.dashboard', [
            'counts' => ContentItem::query()->selectRaw('status, count(*) as total')->groupBy('status')->pluck('total', 'status'),
            'queue' => ContentItem::query()->whereIn('status', [PublishingStatus::InReview, PublishingStatus::Approved])->latest()->limit(8)->get(),
        ]);
    }
}
