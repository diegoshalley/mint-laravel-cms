<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use App\Models\Redirect;
use App\Services\AuditRecorder;
use App\Services\RedirectGuard;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RedirectController extends Controller
{
    public function index(Request $request): View
    {
        $this->authorizeAccess($request);
        return view('cms.redirects.index', ['redirects' => Redirect::latest()->paginate(25)]);
    }

    public function create(Request $request): View
    {
        $this->authorizeAccess($request);
        return view('cms.redirects.form', ['redirect' => new Redirect]);
    }

    public function store(Request $request, RedirectGuard $guard, AuditRecorder $audit): RedirectResponse
    {
        $this->authorizeAccess($request);
        $data = $this->validated($request, $guard);
        $redirect = Redirect::create([...$data, 'is_active' => $request->boolean('is_active'), 'created_by' => $request->user()->id, 'updated_by' => $request->user()->id]);
        $audit->record('redirect.created', $request->user(), $redirect, null, $redirect->toArray());
        return redirect()->route('cms.redirects.index')->with('status', 'Redirect created.');
    }

    public function edit(Request $request, Redirect $redirect): View
    {
        $this->authorizeAccess($request);
        return view('cms.redirects.form', compact('redirect'));
    }

    public function update(Request $request, Redirect $redirect, RedirectGuard $guard, AuditRecorder $audit): RedirectResponse
    {
        $this->authorizeAccess($request);
        $data = $this->validated($request, $guard, $redirect);
        $before = $redirect->toArray();
        $redirect->update([...$data, 'is_active' => $request->boolean('is_active'), 'updated_by' => $request->user()->id]);
        $audit->record('redirect.updated', $request->user(), $redirect, $before, $redirect->fresh()->toArray());
        return back()->with('status', 'Redirect updated.');
    }

    public function destroy(Request $request, Redirect $redirect, AuditRecorder $audit): RedirectResponse
    {
        $this->authorizeAccess($request);
        $before = $redirect->toArray();
        $redirect->delete();
        $audit->record('redirect.deleted', $request->user(), null, $before);
        return redirect()->route('cms.redirects.index')->with('status', 'Redirect deleted.');
    }

    private function validated(Request $request, RedirectGuard $guard, ?Redirect $redirect = null): array
    {
        $data = $request->validate([
            'source_path' => ['required', 'string', 'max:500', Rule::unique('redirects')->ignore($redirect)],
            'destination_path' => ['required', 'string', 'max:500'], 'status_code' => ['required', Rule::in([301, 302])],
            'is_active' => ['nullable', 'boolean'],
        ]);
        foreach (['source_path', 'destination_path'] as $field) {
            if (! str_starts_with($data[$field], '/') || str_starts_with($data[$field], '//') || str_contains($data[$field], '?')) {
                throw ValidationException::withMessages([$field => 'Use an internal path beginning with /, without a domain or query string.']);
            }
            $data[$field] = $guard->normalize($data[$field]);
        }
        $guard->validateChain($data['source_path'], $data['destination_path'], $redirect);
        return $data;
    }

    private function authorizeAccess(Request $request): void { abort_unless($request->user()->can('redirects.manage'), 403); }
}
