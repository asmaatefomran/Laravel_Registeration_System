<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;

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
        if (Session::has('locale')) {
            App::setLocale(Session::get('locale'));
        }

        // Share the current locale with all views (always LTR)
        View::composer('*', function ($view) {
            $locale = app()->getLocale();
            
            $view->with([
                'currentLocale' => $locale,
                'direction' => 'ltr' // Always left-to-right
            ]);
        });
    }
}