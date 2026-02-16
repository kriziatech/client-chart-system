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
                \$parts = explode(',', $expression);
                \$value = (float)trim(\$parts[0]);
                \$decimals = isset(\$parts[1]) ? (int)trim(\$parts[1]) : 0;
                
                \$num = floor(\$value);
                if (\$num < 1000) {
                    echo number_format(\$value, \$decimals);
                } else {
                    \$lastThree = substr(\$num, -3);
                    \$remaining = substr(\$num, 0, -3);
                    \$remaining = preg_replace(\"/\B(?=(\d{2})+(?!\d))/\", \",\", \$remaining);
                    \$main = \$remaining . \",\" . \$lastThree;
                    
                    if (\$decimals > 0) {
                        \$formatted = number_format(\$value, \$decimals);
                        \$dec = substr(\$formatted, strpos(\$formatted, '.'));
                        echo \$main . \$dec;
                    } else {
                        echo \$main;
                    }
                }
            ?>";
        });
    }
}