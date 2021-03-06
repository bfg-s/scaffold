<?php

namespace Bfg\Scaffold;

use Bfg\Installer\Providers\InstalledProvider;
use Bfg\Scaffold\Commands\FoolFreshCommand;
use Bfg\Scaffold\Commands\ScaffoldClearCommand;
use Bfg\Scaffold\Commands\ScaffoldGenerateCommand;

/**
 * Class ServiceProvider.
 * @package Bfg\Scaffold
 */
class ServiceProvider extends InstalledProvider
{
    /**
     * Set as installed by default.
     * @var bool
     */
    public bool $installed = true;

    /**
     * Executed when the provider is registered
     * and the extension is installed.
     * @return void
     */
    public function installed(): void
    {
        /**
         * Merge config from having by default.
         */
        $this->mergeConfigFrom(
            __DIR__.'/../config/scaffold.php', 'scaffold'
        );

        /**
         * Register publisher scaffold configs.
         */
        $this->publishes([
            __DIR__.'/../config/scaffold.php' => config_path('scaffold.php'),
        ], 'scaffold');

        /**
         * Register publisher scaffold demos.
         */
        $this->publishes([
            __DIR__.'/../demo.json' => database_path('scaffolds.json'),
        ], 'scaffold-demo');
    }

    /**
     * Executed when the provider run method
     * "boot" and the extension is installed.
     * @return void
     */
    public function run(): void
    {
        $this->commands([
            ScaffoldGenerateCommand::class,
            ScaffoldClearCommand::class,
            FoolFreshCommand::class,
        ]);
    }
}
