<?php

namespace App\Console\Commands;

use App\SearchPath;
use Illuminate\Console\Command;

class AddPath extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ebooks:addpath {path}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add a path to the index';

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
        $path = $this->getPath();
        $sp = new SearchPath();
        $sp->path = $path;
        $sp->save();
        $this->info("'$path' is added to to the index.");
    }

    private function getPath()
    {
        $path = $this->argument('path');
        $path = realpath($path);

        if(!is_dir($path)) throw new \Exception("'$path' is not a valid directory");
        if(!is_readable($path)) throw new \Exception("'$path' is not readable");

        return $path;
    }
}
