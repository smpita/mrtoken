<?php

namespace Hackage\MrToken;

use Hackage\MrToken\Facades\MrTokenServiceFacade;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;

class MrTokenServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            realpath(__DIR__ . '/../config/mrtoken.php') => config_path('mrtoken.php')
        ]);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(MrTokenService::class, function () {
            return new MrTokenService();
        });

        // Define alias 'MrToken'
        $this->app->booting(function () {
            $loader = AliasLoader::getInstance();

            $loader->alias('MrToken', MrTokenServiceFacade::class);
        });
    }
}
