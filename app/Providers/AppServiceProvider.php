<?php

namespace App\Providers;

use App\Models\Dress;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->ensureStorageLink();
        $this->shareFooterData();
    }

    /**
     * Share footer dress slider data with all views that use the app layout.
     */
    private function shareFooterData(): void
    {
        View::composer('layouts.app', function ($view) {
            if (! isset($view->getData()['footerDresses'])) {
                $footerDresses = Dress::with('images')
                    ->available()
                    ->featured()
                    ->latest()
                    ->take(20)
                    ->get()
                    ->filter(fn ($d) => $d->images->isNotEmpty())
                    ->values();

                $view->with('footerDresses', $footerDresses);
            }
        });
    }

    /**
     * Create the public/storage symlink if it does not already exist.
     * This ensures uploaded images are always publicly accessible without
     * requiring a manual `php artisan storage:link` run after every deploy.
     */
    private function ensureStorageLink(): void
    {
        $link   = public_path('storage');
        $target = storage_path('app/public');

        // Skip if a valid symlink or real directory already exists at this path.
        // is_link() must be checked separately because file_exists() returns
        // false for broken symlinks, which would otherwise bypass this guard.
        if (is_link($link) || file_exists($link)) {
            return;
        }

        try {
            symlink($target, $link);
        } catch (\Throwable $e) {
            // Symlink creation failed (e.g. missing permissions on this host).
            // Images will still be accessible if `php artisan storage:link`
            // is run manually or via the deployment script in .cpanel.yml.
        }
    }
}
