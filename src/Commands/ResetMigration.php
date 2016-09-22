<?php

namespace TomLegkov\MigrationManager\Commands;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Console\Command;
use \RecursiveTreeIterator;
use \RecursiveIteratorIterator;
use \RecursiveDirectoryIterator;
class ResetMigration extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migman:reset';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrates from the organized migrations';

    private $migrationPath;

    private $files = [];

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

    private function findFiles(){
        $iterator   = new RecursiveIteratorIterator(
                        new RecursiveDirectoryIterator($this->migrationPath), 
                            RecursiveIteratorIterator::SELF_FIRST);
        $files      = [];
        foreach ($iterator as $file) {
            if ($file->isFile() && $file->getExtension() === "php") {
                $files[] = $file;
            }
        }
        $this->files = $files;
    }

    private function moveFiles(){
        foreach ($this->files as $file) {
            copy($file->getRealPath(), $this->migrationPath . DIRECTORY_SEPARATOR . $file->getFileName() );
        }
    }

    private function resetMigration(){
        $this->info('Resetting Migration...');
        Artisan::call('migrate:reset');
    }

    private function removeTraces(){
        array_map('unlink', glob($this->migrationPath . DIRECTORY_SEPARATOR . '*.php'));
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(){
        $this->findFiles();
        if (count($this->files) === 0) {
            return $this->info('Nothing to reset!');
        }
        $this->moveFiles();
        $this->resetMigration();
        $this->removeTraces();
        $this->info('Reset migration successfully!');
    }

    /**
     * Reset all components
     */
    public function __destruct(){
        $this->folder = null;
        $this->files = [];
    }
}
