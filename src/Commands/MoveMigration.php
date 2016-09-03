<?php

namespace TomLegkov\MigrationManager\Commands;

use Illuminate\Console\Command;

class MoveMigration extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migman:move {dir}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Moves the last migration to a folder';

    protected $migrationPath = null;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->migrationPath = database_path() . DIRECTORY_SEPARATOR . 'migrations';
    }

    private function findFile(){
        $files = glob($this->migrationPath . DIRECTORY_SEPARATOR . '*.php');
        $files = array_combine($files, array_map('filectime', $files));
        arsort($files);
        return key($files); // the filename
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $dir    = $this->argument('dir');
        $dir    = str_replace(['/', '\\'], '', $dir);
        $file   = $this->findFile();
        $newdir = $this->migrationPath . DIRECTORY_SEPARATOR . $dir;
        $moveTo = $newdir . DIRECTORY_SEPARATOR . basename($file);
        
        if (!file_exists($newdir)) {
            mkdir($newdir, 0777, true);
        }   

        rename($file, $moveTo);
        $this->info('Moved successfully!');
    }
}
