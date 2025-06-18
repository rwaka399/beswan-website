<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Auth;


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
        Paginator::useTailwind();
        
        // Force HTTPS in production
        if (config('app.env') === 'production') {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }
        
        // Custom Blade directives for session-based auth data
        Blade::directive('hasPermission', function ($expression) {
            return "<?php if(Auth::guard('web') instanceof \App\Guards\CustomSessionGuard && Auth::guard('web')->hasPermission($expression)): ?>";
        });
        
        Blade::directive('endhasPermission', function () {
            return "<?php endif; ?>";
        });
        
        Blade::directive('userRole', function () {
            return "<?php echo Auth::guard('web') instanceof \App\Guards\CustomSessionGuard ? Auth::guard('web')->getUserRole()['role_name'] ?? '' : ''; ?>";
        });
    }
}
