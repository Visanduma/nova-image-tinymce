<?php

namespace Visanduma\NovaImageTinymce;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Laravel\Nova\Events\ServingNova;
use Laravel\Nova\Nova;

class FieldServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot()
    {

//        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');

        Route::middleware(['nova'])
            ->prefix('nova-vendor/nova-image-tinymce')
            ->group(__DIR__.'/../routes/api.php');

        Nova::serving(function (ServingNova $event) {
            Nova::script('nova-image-tinymce', __DIR__ . '/../dist/js/field.js');
            Nova::style('nova-image-tinymce', __DIR__ . '/../dist/css/field.css');
        });

        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');


        $this->publishes([
            __DIR__ . '/../config/nova-image-tinymce.php' => config_path('nova-image-tinymce.php'),
        ], 'config');

    }

    /**
     * Register any application services.
     */
    public function register()
    {

    }
}
