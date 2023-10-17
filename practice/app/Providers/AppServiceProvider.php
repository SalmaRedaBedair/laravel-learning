<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Blade::component('test', 'modal');
        view()->share('recentPosts', 'posts');
        view()->composer('test', function ($view) {
            $view->with('mmss', 'mmss');
        });

        Blade::directive('ifok', function () {
            return "<?php if (true): ?>";
        });

        Blade::directive('newlinesToBr', function ($expression) {
            return "<?php echo nl2br({$expression}); ?>";
        });
    }
}
