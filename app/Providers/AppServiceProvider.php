<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Paginator::useTailwind();

        Gate::before(fn ($user) => $user->hasRole('Super Administrator') ? true : null);
        Gate::define('access-cms', fn ($user) => $user->hasAnyRole([
            'CMS Administrator', 'Publisher', 'Reviewer', 'Editor', 'Media Manager', 'Auditor',
        ]));
    }
}
