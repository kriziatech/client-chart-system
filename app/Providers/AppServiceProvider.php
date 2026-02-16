<?php

namespace App\Providers;

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
        \Illuminate\Support\Facades\Event::subscribe(\App\Listeners\AuditLogSubscriber::class);

        \Illuminate\Support\Facades\Blade::directive('indian_format', function ($expression) {
            return "<?php 
                \$num = floor($expression);
                if (\$num < 1000) {
                    echo number_format($expression);
                } else {
                    \$lastThree = substr(\$num, -3);
                    \$remaining = substr(\$num, 0, -3);
                    \$remaining = preg_replace(\"/\B(?=(\d{2})+(?!\d))/\", \",\", \$remaining);
                    echo \$remaining . \",\" . \$lastThree;
                }
            ?>";
        });
    }
}