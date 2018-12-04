<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(\App\Transformer\MatchTransformer::class, function ($app) {
            return new \App\Transformer\MatchTransformer();
        });

        $this->app->alias(\App\Transformer\MatchTransformer::class, 'app.transformer.match');
    }
}
