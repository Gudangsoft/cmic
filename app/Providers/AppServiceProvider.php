<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Menu;
use App\Models\Setting;
use App\Models\Contact;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        // Share all settings in ONE query — registered to ALL views via static cache
        View::composer('*', function ($view) {
            try {
                static $cached = null;
                if ($cached === null) {
                    $cached = Setting::pluck('value', 'key')->toArray();
                }
                $view->with('siteSettings', $cached);
            } catch (\Exception $e) {
                $view->with('siteSettings', []);
            }
        });

        // Share nav menus to frontend layout
        View::composer('layouts.app', function ($view) {
            try {
                $menus = Menu::where('is_active', true)
                    ->whereNull('parent_id')
                    ->orderBy('order')
                    ->with(['children' => fn($q) => $q->where('is_active', true)->orderBy('order')])
                    ->get();
            } catch (\Exception $e) {
                $menus = collect();
            }
            $view->with('globalMenus', $menus);
        });

        // Share unread count to admin layout (single query)
        View::composer('layouts.admin', function ($view) {
            try {
                $view->with('unreadCount', Contact::where('is_read', false)->count());
            } catch (\Exception $e) {
                $view->with('unreadCount', 0);
            }
        });
    }
}

