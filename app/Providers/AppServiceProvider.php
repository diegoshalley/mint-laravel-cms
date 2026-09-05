<?php

namespace App\Providers;

use App\Services\ClamAvScanner;
use App\Services\MalwareScanner;
use App\Models\NavigationItem;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
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

        View::composer('layouts.public', function ($view): void {
            $items = Schema::hasTable('navigation_items')
                ? NavigationItem::with(['children' => fn ($query) => $query->where('is_visible', true)])
                    ->where('location', 'primary')->whereNull('parent_id')->where('is_visible', true)->orderBy('position')->get()
                : collect();
            $view->with('primaryNavigation', $items);
        });
    }
}
