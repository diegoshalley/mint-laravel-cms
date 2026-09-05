<?php

namespace App\Http\Controllers;

use App\Models\Redirect;
use App\Services\RedirectGuard;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class PublicRedirectController extends Controller
{
    public function __invoke(Request $request, RedirectGuard $guard): RedirectResponse
    {
        $path = $guard->normalize('/'.$request->path());
        $redirect = Redirect::query()->where('source_path', $path)->where('is_active', true)->firstOrFail();
        $redirect->increment('hit_count');
        $redirect->forceFill(['last_hit_at' => now()])->save();
        return redirect($redirect->destination_path, $redirect->status_code);
    }
}
