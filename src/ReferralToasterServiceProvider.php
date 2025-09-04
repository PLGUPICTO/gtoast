<?php

namespace Georg\ReferralToaster;

use Illuminate\Support\ServiceProvider;
use Illuminate\View\View;
use Filament\Support\Facades\FilamentView;
use Filament\View\PanelsRenderHook;
use Illuminate\Support\Facades\Blade;

class ReferralToasterServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // Load package views
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'referral-toaster');

        // Allow publishing of views
        $this->publishes([
            __DIR__ . '/../resources/views' => resource_path('views/livewire'),
        ], 'views');

        // Register Filament hook (only if Filament is present)
        FilamentView::registerRenderHook(
            PanelsRenderHook::SCRIPTS_AFTER,
            fn(): View => view('referral-toaster::livewire.components.toast'),
        );

        // Publish Livewire component class
        $this->publishes([
            __DIR__ . '/Livewire/Toast.php' => app_path('Livewire/Toast.php'),
        ], 'livewire');

        // Copy the Event class to app/Events
        $this->publishes([
            __DIR__ . '/Events/FirstEvent.php' => app_path('Events/FirstEvent.php'),
        ], 'referral-event');
    }

    public function register()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                \Georg\ReferralToaster\Console\InstallReferralToaster::class,
            ]);
        }
    }
}
