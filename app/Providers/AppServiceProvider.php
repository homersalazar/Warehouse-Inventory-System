<?php

namespace App\Providers;

use App\Models\Location;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        View::composer('*', function ($view) {
            $locations = Location::all(); // Retrieve all locations from the database
            $view->with('locations', $locations);
        });
    }
}
