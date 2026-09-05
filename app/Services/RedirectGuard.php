<?php

namespace App\Services;

use App\Models\Redirect;
use Illuminate\Validation\ValidationException;

class RedirectGuard
{
    public function normalize(string $path): string
    {
        $path = '/'.ltrim(trim($path), '/');
        if ($path !== '/') $path = rtrim($path, '/');
        return $path;
    }

    public function validateChain(string $source, string $destination, ?Redirect $ignored = null): void
    {
        $source = $this->normalize($source);
        $cursor = $this->normalize($destination);
        if ($source === $cursor) $this->fail();

        $visited = [$source => true];
        for ($depth = 0; $depth < 10; $depth++) {
            if (isset($visited[$cursor])) $this->fail();
            $visited[$cursor] = true;
            $next = Redirect::query()->where('is_active', true)->where('source_path', $cursor)
                ->when($ignored, fn ($query) => $query->whereKeyNot($ignored->id))->first();
            if (! $next) return;
            $cursor = $this->normalize($next->destination_path);
        }

        throw ValidationException::withMessages(['destination_path' => 'Redirect chains may not exceed ten steps.']);
    }

    private function fail(): never
    {
        throw ValidationException::withMessages(['destination_path' => 'This destination creates a redirect loop.']);
    }
}
