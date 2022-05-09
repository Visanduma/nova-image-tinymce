<?php

namespace Kraftbit\NovaTinymce5Editor;

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
            Nova::script('nova-tinymce5-editor', __DIR__.'/../dist/js/field.js');
            Nova::style('nova-tinymce5-editor', __DIR__.'/../dist/css/field.css');
        });

        $this->publishes([
            __DIR__.'/../config/nova-tinymce5-editor.php' => config_path('nova-tinymce5-editor.php'),
        ], 'config');

        $this->publishes([
            __DIR__.'/../database/migrations/tinymce_images_table.php' => database_path('migrations')
        ],'migration');
    }

    /**
     * Register any application services.
     */
    public function register()
    {
    }
}
