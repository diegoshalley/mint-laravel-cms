<?php

namespace App\Providers;

use App\Services\ClamAvScanner;
use App\Services\MalwareScanner;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Laravel\Fortify\Fortify;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(MalwareScanner::class, ClamAvScanner::class);
    }

    public function boot(): void
    {
        Fortify::ignoreRoutes();
        Paginator::useTailwind();

        Gate::before(fn ($user) => $user->hasRole('Super Administrator') ? true : null);
        Gate::define('access-cms', fn ($user) => $user->hasAnyRole([
            'CMS Administrator', 'Publisher', 'Reviewer', 'Editor', 'Media Manager', 'Auditor',
        ]));
    }
}
