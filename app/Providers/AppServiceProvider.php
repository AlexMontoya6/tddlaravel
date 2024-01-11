<?php

namespace App\Providers;

use App\Sortable;
use Illuminate\Pagination\{LengthAwarePaginator, Paginator};
use Illuminate\Support\Facades\Blade;
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
        Blade::component('shared._card', 'card');


        Paginator::useBootstrap();

        $this->app->bind(LengthAwarePaginator::class, \App\lenghtAwarePaginator::class);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(Sortable::class, function ($app) {
            return new Sortable(request()->url());
        });
    }
}
