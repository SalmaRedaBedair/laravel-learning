<?php

namespace App\Providers;

use App\test\Logger;
use App\test\Mailer;
use App\test\Slack;
use App\test\UserMailer;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(Mailer::class, function($app) {
            return new Mailer();
        });
//
//        $this->app->bind(Logger::class, function ($app) {
//            return new Logger();
//        });
//
//        $this->app->bind(Slack::class, function ($app) {
//            return new Slack();
//        });

        // Bind UserMailer with its dependencies
//        $this->app->singleton(UserMailer::class, function ($app) {
//            return new UserMailer(
//                $app->make(Mailer::class, ['name' => 'Salma']),
//                $app->make(Logger::class),
//                $app->make(Slack::class)
//            );
//        });

        // You can remove the direct instantiation from the register method
//         dd(new UserMailer(app(Mailer::class), app(Logger::class), app(Slack::class)));
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
