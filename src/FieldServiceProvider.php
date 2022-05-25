<?php

namespace Visanduma\NovaImageTinymce;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Laravel\Nova\Events\ServingNova;
use Laravel\Nova\Nova;
use Visanduma\NovaTwoFactor\Http\Middleware\Authorize;

class FieldServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot()
    {

//        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');

        Route::middleware(['nova', Authorize::class])
            ->prefix('nova-vendor/nova-media-tinymce')
            ->group(__DIR__.'/../routes/api.php');

        Nova::serving(function (ServingNova $event) {
            Nova::script('nova-image-tinymce', __DIR__ . '/../dist/js/field.js');
            Nova::style('nova-image-tinymce', __DIR__ . '/../dist/css/field.css');
        });

        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');


        $this->publishes([
            __DIR__ . '/../config/nova-image-tinymce.php' => config_path('nova-image-tinymce.php'),
        ], 'config');

         $this->publishes([
            __DIR__.'/../database/migrations/2022_01_01_12526_create_tinymce_images_table' => database_path('migrations')
        ],'migration');
    }

    /**
     * Register any application services.
     */
    public function register()
    {

    }
}
