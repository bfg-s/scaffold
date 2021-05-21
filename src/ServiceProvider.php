<?php

namespace Bfg\Scaffold;

use Bfg\Scaffold\Commands\ScaffoldClearCommand;
use Bfg\Scaffold\Commands\ScaffoldGenerateCommand;
use Illuminate\Support\ServiceProvider as IlluminateServiceProvider;

/**
 * Class ServiceProvider
 * @package Bfg\Scaffold
 */
class ServiceProvider extends IlluminateServiceProvider
{
    /**
     * Bootstrap services.
     * @return void
     */
    public function boot()
    {
        $this->commands([
            ScaffoldGenerateCommand::class,
            ScaffoldClearCommand::class,
        ]);
    }

    /**
     * Register route settings
     * @return void
     */
    public function register()
    {
        /**
         * Merge config from having by default
         */
        $this->mergeConfigFrom(
            __DIR__.'/../config/scaffold.php', 'scaffold'
        );

        /**
         * Register publisher scaffold configs
         */
        $this->publishes([
            __DIR__.'/../config/scaffold.php' => config_path('scaffold.php'),
        ], 'scaffold');

        /**
         * Register publisher scaffold demos
         */
        $this->publishes([
            __DIR__.'/../demo.json' => database_path('scaffolds.json'),
        ], 'scaffold-demo');
    }
}
