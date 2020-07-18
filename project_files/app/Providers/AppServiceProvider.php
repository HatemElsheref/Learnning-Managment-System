<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Blade;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

        Schema::defaultStringLength(191);
        // Make Custom Blade For Check Permission

        Blade::directive('IFACAN', function ($permission) {
/*            return "<?php if (checkDashboardPermissionFromCache(trim(strtolower($permission)))):?>";*/
            return "<?php if (checkPermissions(trim(strtolower($permission)))):?>";
        });
        Blade::directive('ENDACAN', function ($permission) {
            return "<?php endif ?>";
        });

    }
}
