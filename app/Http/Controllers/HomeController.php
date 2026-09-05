<?php

namespace App\Http\Controllers;

use App\Enums\ContentType;
use App\Models\ContentItem;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __invoke(): View
    {
        $latest = ContentItem::visible()->whereIn('type', [
            ContentType::News, ContentType::PressRelease, ContentType::PublicNotice,
        ])->latest('published_at')->limit(3)->get();
        return view('public.home', compact('latest'));
    }
}
