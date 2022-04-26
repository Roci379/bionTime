<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
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

        Blade::directive('jsvue', function ($var) {
            // Json encode
            $json = "json_encode($var,JSON_HEX_APOS|JSON_HEX_QUOT)";
            //$json = "str_replace(\"\\\\\",\"\\\\\\\\\"," . $json . ')';
            return "<?php echo \"'\" . $json . \"'\"; ?>";
        });

        //
    }
}
