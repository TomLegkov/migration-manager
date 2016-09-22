<?php

namespace TomLegkov\MigrationManager\Commands;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Console\Command;
use \RecursiveTreeIterator;
use \RecursiveIteratorIterator;
use \RecursiveDirectoryIterator;
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

    private $migrationPath;

    private $folder;

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

    private function getFullPath(){
        return $this->migrationPath . DIRECTORY_SEPARATOR . $this->folder;
    }

    /**
     * Creates a unique folder and assigns it to {$this->folder}
     */
    private function createFolder() {
        $this->folder = str_random(24);
        while (file_exists($this->getFullPath())) {
            $this->folder = str_random(24); 
        }
        mkdir($this->getFullPath());
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
        $dest = $this->getFullPath() . DIRECTORY_SEPARATOR;
        foreach ($this->files as $file) {
            copy($file->getRealPath(), $dest . $file->getFileName() );
        }
    }

    private function migrate(){
        Artisan::call('migrate', [
            '--path'    => $this->getFullPath() 
        ]);
    }

    private function removeTraces(){
        array_map('unlink', glob($this->getFullPath() . DIRECTORY_SEPARATOR . '*.*'));
        rmdir($this->getFullPath());
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(){
        $this->findFiles();
        if (count($this->files) === 0) {
            return $this->info('Nothing to migrate!');
        }
        $this->createFolder();
        $this->moveFiles();
        $this->migrate();
        $this->removeTraces();
    }

    /**
     * Reset all components
     */
    public function __destruct(){
        $this->folder = null;
        $this->files = [];
    }
}
