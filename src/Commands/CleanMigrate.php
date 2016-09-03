<?php

namespace TomLegkov\MigrationManager\Commands;

use Illuminate\Console\Command;

class CleanMigrate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migman';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrates from the organized migrations';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // FIRST COMMIT
    }
}
