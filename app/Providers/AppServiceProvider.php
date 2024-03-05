<?php

namespace App\Providers;

use App\Models\DashboardNotification;
use Illuminate\Support\Facades;
use Illuminate\Support\ServiceProvider;
use Illuminate\View\View;

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
        Facades\View::composer('layouts.index', function ($view) {
            $dashboard_notifications = DashboardNotification::where('status', 0)->orderBy('created_at', 'asc')->get();
            $dashboard_notification_unread = DashboardNotification::where('status', 0)->get()->count();
            
            $view->with(
                [
                    'dashboard_notifications' => $dashboard_notifications,
                    'dashboard_notification_unread' => $dashboard_notification_unread
                ]
            );
        });
    }
}
