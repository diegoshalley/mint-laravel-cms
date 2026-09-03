<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use App\Models\NavigationItem;
use App\Services\AuditRecorder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class NavigationController extends Controller
{
    public function index(Request $request): View
    {
        $this->authorizeAccess($request);
        return view('cms.navigation.index', [
            'items' => NavigationItem::with('parent')->orderBy('location')->orderBy('position')->get(),
        ]);
    }

    public function create(Request $request): View
    {
        $this->authorizeAccess($request);
        return view('cms.navigation.form', ['item' => new NavigationItem, 'parents' => $this->parents()]);
    }

    public function store(Request $request, AuditRecorder $audit): RedirectResponse
    {
        $this->authorizeAccess($request);
        $data = $this->validated($request);
        $item = NavigationItem::create([...$data, 'is_visible' => $request->boolean('is_visible'), 'open_in_new_tab' => $request->boolean('open_in_new_tab'), 'created_by' => $request->user()->id, 'updated_by' => $request->user()->id]);
        $audit->record('navigation.created', $request->user(), $item, null, $item->toArray());
        return redirect()->route('cms.navigation.index')->with('status', 'Navigation item created.');
    }

    public function edit(Request $request, NavigationItem $navigation): View
    {
        $this->authorizeAccess($request);
        return view('cms.navigation.form', ['item' => $navigation, 'parents' => $this->parents($navigation)]);
    }

    public function update(Request $request, NavigationItem $navigation, AuditRecorder $audit): RedirectResponse
    {
        $this->authorizeAccess($request);
        $data = $this->validated($request, $navigation);
        $before = $navigation->toArray();
        $navigation->update([...$data, 'is_visible' => $request->boolean('is_visible'), 'open_in_new_tab' => $request->boolean('open_in_new_tab'), 'updated_by' => $request->user()->id]);
        $audit->record('navigation.updated', $request->user(), $navigation, $before, $navigation->fresh()->toArray());
        return back()->with('status', 'Navigation item updated.');
    }

    public function destroy(Request $request, NavigationItem $navigation, AuditRecorder $audit): RedirectResponse
    {
        $this->authorizeAccess($request);
        if ($navigation->children()->exists()) throw ValidationException::withMessages(['navigation' => 'Move or delete child links before deleting this item.']);
        $before = $navigation->toArray();
        $navigation->delete();
        $audit->record('navigation.deleted', $request->user(), null, $before);
        return redirect()->route('cms.navigation.index')->with('status', 'Navigation item deleted.');
    }

    private function validated(Request $request, ?NavigationItem $item = null): array
    {
        $data = $request->validate([
            'location' => ['required', Rule::in(['primary', 'footer'])], 'label' => ['required', 'string', 'max:100'],
            'destination' => ['required', 'string', 'max:500'], 'position' => ['required', 'integer', 'min:0', 'max:999'],
            'parent_id' => ['nullable', 'uuid', Rule::exists('navigation_items', 'id')],
            'is_visible' => ['nullable', 'boolean'], 'open_in_new_tab' => ['nullable', 'boolean'],
        ]);
        if (! str_starts_with($data['destination'], '/') && ! str_starts_with($data['destination'], 'https://')) {
            throw ValidationException::withMessages(['destination' => 'Use a site path beginning with / or a secure https:// address.']);
        }
        if (str_starts_with($data['destination'], '//') || str_contains($data['destination'], "\n")) {
            throw ValidationException::withMessages(['destination' => 'The destination is unsafe.']);
        }
        if ($item && ($data['parent_id'] ?? null) === $item->id) {
            throw ValidationException::withMessages(['parent_id' => 'A navigation item cannot be its own parent.']);
        }
        if ($data['parent_id'] ?? null) {
            $parent = NavigationItem::findOrFail($data['parent_id']);
            if ($parent->parent_id || $parent->location !== $data['location']) {
                throw ValidationException::withMessages(['parent_id' => 'Choose a top-level parent in the same menu.']);
            }
        }
        return $data;
    }

    private function parents(?NavigationItem $ignored = null)
    {
        return NavigationItem::query()->whereNull('parent_id')->when($ignored, fn ($query) => $query->whereKeyNot($ignored->id))->orderBy('label')->get();
    }

    private function authorizeAccess(Request $request): void { abort_unless($request->user()->can('navigation.manage'), 403); }
}
