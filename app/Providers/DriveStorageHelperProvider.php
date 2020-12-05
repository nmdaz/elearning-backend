<?php

namespace App\Providers;

use App\Includes\DriveStorageHelper;
use Illuminate\Support\ServiceProvider;

class DriveStorageHelperProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(DriveStorageHelper::class, function ($app) {
            return new DriveStorageHelper();
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
