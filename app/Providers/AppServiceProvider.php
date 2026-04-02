<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Carbon\Carbon;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        // Set locale Carbon ke Bahasa Indonesia
        Carbon::setLocale('id');
        setlocale(LC_TIME, 'id_ID.UTF-8', 'id_ID', 'Indonesian');

        /**
         * @active('route.name') — returns 'active' jika route cocok
         * Mendukung wildcard: @active('admin.*')
         *
         * Contoh pemakaian di blade:
         *   <a href="..." class="sidebar-link @active('admin.dashboard')">Dashboard</a>
         */
        Blade::directive('active', function (string $expression): string {
            return "<?php echo request()->routeIs({$expression}) ? 'active' : ''; ?>";
        });
    }
}
