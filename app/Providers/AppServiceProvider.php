<?php

namespace App\Providers;

use App\Models\Setting;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        $this->ensureMediaDirectoryExists();

        Vite::prefetch(concurrency: 3);

        View::composer('app', function ($view) {
            if (!Schema::hasTable('settings')) {
                $view->with('faviconUrl', asset('images/favicon.png'));
                $view->with('primaryColor', null);
                $view->with('secondaryColor', null);
                $view->with('googleAnalyticsId', null);
                $view->with('customHeadScripts', '');
                return;
            }
            $faviconUrl = Setting::getStorageUrl(Setting::get('favicon'));
            $view->with('faviconUrl', $faviconUrl ?? asset('images/favicon.png'));
            $view->with('primaryColor', Setting::get('primary_color'));
            $view->with('secondaryColor', Setting::get('secondary_color'));
            $view->with('googleAnalyticsId', Setting::get('google_analytics_id'));
            $view->with('customHeadScripts', Setting::get('custom_head_scripts') ?? '');
        });
    }

    /**
     * Ensure public/media exists so product images and uploads always have a writable root.
     */
    private function ensureMediaDirectoryExists(): void
    {
        $mediaPath = public_path('media');
        if (! is_dir($mediaPath)) {
            mkdir($mediaPath, 0755, true);
        }
    }
}
