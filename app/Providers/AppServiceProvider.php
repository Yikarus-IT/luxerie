<?php

namespace App\Providers;

use App\Models\SiteSetting;
use App\Services\CartManager;
use Illuminate\Auth\Notifications\ResetPassword;
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
        ResetPassword::createUrlUsing(fn (object $notifiable, string $token): string => route('customer.password.reset', ['token' => $token, 'email' => $notifiable->getEmailForPasswordReset()]));

        View::composer('layouts.storefront', function ($view): void {
            $view->with('siteSettings', SiteSetting::resolved())
                ->with('cartItemCount', app(CartManager::class)->current(request(), false)?->itemCount() ?? 0);
        });
    }
}
