<?php

namespace TomLegkov\MigrationManager;

use Illuminate\Support\ServiceProvider;

class MigrationManagerServiceProvider extends ServiceProvider
{

    protected $commands = [
        "TomLegkov\MigrationManager\Commands\CleanMigrate",
        "TomLegkov\MigrationManager\Commands\MoveMigration",
    ];

    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        $this->commands($this->commands);
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}