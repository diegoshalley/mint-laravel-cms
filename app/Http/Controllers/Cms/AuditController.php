<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use App\Models\AuditEvent;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AuditController extends Controller
{
    public function __invoke(Request $request): View
    {
        abort_unless($request->user()->can('audit.view'), 403);
        return view('cms.audit.index', ['events' => AuditEvent::query()->latest('created_at')->paginate(50)]);
    }
}
