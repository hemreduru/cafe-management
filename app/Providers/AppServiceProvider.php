<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\App;
use App\Services\StockService;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // StockService'i singleton olarak kaydet
        $this->app->singleton(StockService::class, function ($app) {
            return new StockService();
        });
    }

    public function boot(): void
    {
        // Dil ayarlarını yükle
        if (Session::has('locale')) {
            App::setLocale(Session::get('locale'));
        }

        View::composer('*', function ($view) {
            $darkMode = Session::get('darkmode', false);
            $view->with('darkMode', $darkMode);
            
            if ($darkMode) {
                $view->with('bodyClass', 'dark-mode');
            }
        });
    }
}
