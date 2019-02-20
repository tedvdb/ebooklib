<?php

namespace App\Console\Commands;

use App\SearchPath;
use Illuminate\Console\Command;

class ListPaths extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ebooks:listpaths';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'List paths currently added in the database';

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
        $this->info("Paths currently in the database:");
        $paths = SearchPath::all();
        foreach($paths as $p) {
            $this->info("- {$p->id}\t{$p->path}");
        }
    }
}
