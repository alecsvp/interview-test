<?php

namespace App\Providers;

use App\Services\FirstEmailService;
use App\Services\SecondEmailService;
use App\Services\ThirdEmailService;
use Illuminate\Support\ServiceProvider;

class EmailServiceProvider extends ServiceProvider
{
    public function __construct(\Illuminate\Foundation\Application $app)
    {
        $this->app = $app;
    }

    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(FirstEmailService::class, function () {
            return new FirstEmailService();
        });

        $this->app->bind(SecondEmailService::class, function () {
            return new SecondEmailService();
        });

        $this->app->bind(ThirdEmailService::class, function () {
            return new ThirdEmailService();
        });
    }

    public function boot()
    {
    }
}
