<?php

namespace App\Providers;

use App\Services\EmailService;
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
        $this->app->singleton(EmailService::class, function () {
            return new EmailService();
        });
    }

    public function boot(){}
}
