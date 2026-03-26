<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\DatabaseQuizRepository;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(DatabaseQuizRepository::class, function ($app) {
            return new DatabaseQuizRepository();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
