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
            $parts = explode(',', $expression);
            $valExpr = trim($parts[0] ?? '0');
            $decExpr = trim($parts[1] ?? '0');

            return "<?php 
                \$value = (float)($valExpr);
                \$decimals = (int)($decExpr);
                
                \$sign = \$value < 0 ? '-' : '';
                \$abs_value = abs(\$value);
                \$num = (string)floor(\$abs_value);
                
                if (floor(\$abs_value) < 1000) {
                    echo \$sign . number_format(\$abs_value, \$decimals);
                } else {
                    \$lastThree = substr(\$num, -3);
                    \$remaining = substr(\$num, 0, -3);
                    \$remaining = preg_replace('/\\B(?=(\\d{2})+(?!\\d))/', ',', \$remaining);
                    \$main = \$remaining . ',' . \$lastThree;
                    
                    if (\$decimals > 0) {
                        \$formatted = number_format(\$abs_value, \$decimals);
                        \$dec = substr(\$formatted, strpos(\$formatted, '.'));
                        echo \$sign . \$main . \$dec;
                    } else {
                        echo \$sign . \$main;
                    }
                }
            ?>";
        });
    }
}